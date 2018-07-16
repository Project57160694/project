var oTableSelector; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
  oTableSelector = $('#tblSelector').dataTable(
  {
    processing: true,
    serverSide: true,
    bFilter: true,
    bLengthChange:false,
    pageLength: 10,
    renderer: 'bootstrap',
    ajax:{
      url: $('#tblSelector').data('action'),
      data: function (d) {
        d.create = $('#create').val();
        d.update = $('#update').val();
      }
    },
    columns: [
      {data: 'selector_id', name: 'selector_id', bVisible: false ,bFilter: false, bSortable: false},
      {data: 'name', name: 'name'},
      {data: 'url_web', name: 'url_web'},
      {data: 'selectors_update', name: 'selectors_update'},
      {
        data: function (data) {
          return getActionButton(data);
        }, name: 'manage', bFilter: false, bSortable: false
      }
    ]
  });
});
function getActionButton(data){
  // var _html = '<button class="btn btn-primary btn-xs" id="show" type="button"onclick="location.href = 'https://myurl'" >';
  var _html = '<button class="btn btn-primary btn-xs" id="show" type="button" onclick="Openwebsite(' + data.selector_id + ' ,\'' + data.url_web + '\')">'
    _html += '<i class="glyphicon glyphicon-play"> Selector </i>';
    _html += '</button>'
    _html += '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="getEditSelector(' + data.selector_id + ')">'
    _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
    _html += '</button>'
    _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteSelector(' + data.selector_id + ')">'
    _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
    _html += '</button>'
  return _html;
}

function reloadTableSelector() {
  oTableSelector.fnDraw();
}

function deleteSelector(selector_id){
  confirm("คุณต้องการลบรายการนี้หรือไม่ ?", function(){
    $.ajax({
      url: 'selector/deleteselector' + '/' + selector_id,
      type: "GET",
      dataType: "json",
      success: function (data){
        if(data.status){
          alert("แจ้งเตือน",data.message, function () {
          });
          reloadTableSelector();
        }else {
          alert("ไม่สามารถลบรายการนี้ได้");
        }
      }
    });
    });
}

function Openwebsite(selector_id,url_web){
  //  console.log(selector_id);
    //console.log(url_web);
    var website = url_web;
    window.open(website);
}



function getEditSelector(selector_id) {
  openModal('Edit Selector','/selector' + '/' + selector_id + '/editSelectors', 'lg',
  function(){
    $('#formSelector')
  }
);
}


function getEditSubDistrict(id) {
  openModal('แก้ไขตำบล/แขวง', url("subdistrict") + '/' + id + '/edit', 'lg',
  function(){
    $("#province2, #district2").select2();

    $('#formSubDistrict').validate({
      rules: {
        name: 'required',
        district_id: 'required',
        province_id: 'required',
      },
      messages: {
        name: 'กรุณากรอกชื่อตำบล/แขวง',
        district_id: 'กรุณาเลือกอำเภอ/เขต',
        province_id: 'กรุณาเลือกจังหวัด',
      },
      errorElement: 'em',
      errorPlacement: function (error, element) {
        // Add the `help-block` class to the error element
        error.addClass('form-control-feedback');
        if (element.prop('type') === 'checkbox') {
          error.insertAfter(element.parent('label'));
        } else {
          error.insertAfter(element);
        }
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('form-control-danger').removeClass('form-control-success');
        $(element).parents('.form-group').addClass('has-danger').removeClass('has-success');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).addClass('form-control-success').removeClass('form-control-danger');
        $(element).parents('.form-group').removeClass('has-danger');
      },
      submitHandler: function (form) {
        $(form).ajaxSubmit({
          type: $("#formSubDistrict").attr('method'),
          url: $("#formSubDistrict").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert(data.message, function () {
                $('.modal').modal('hide');
                reloadTableSubDistrict();
              });
            } else {
              alert(data.message);
            }
          }
        });
      }
    });

    $('#province2').change(function () {
      var province_id = $(this).val();
      var district_id = null;
      get_dropdown_district(province_id, district_id);
    });
    function get_dropdown_district(province_id, district_id) {
      $.ajax({
        type: 'GET',
        url: url('/court/get_disfrompro/') + province_id,
        dataType: 'json',
        success: function (data)
        {
          $('#district2').prop('disabled', false);
          $("#district2").empty();
          $.each(data, function (index, value) {
            $("#district2").append('<option value="' + index + '">' + value + '</option>');
            $("#district2").html($('#district2 option').sort(function (x, y) {
              return $(x).val() < $(y).val() ? - 1 : 1;
            }));
            $("#district2").get(0).selectedIndex = 0;
          });
          $("#district2").select2('val', district_id);
        }
      });
    }

  }
  , function () {
  });
}
