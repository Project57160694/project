var oTableFunction; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
oTableFunction = $('#tblFunction').dataTable(
{
  processing: true,
  serverSide: true,
  bFilter: true,
  bLengthChange:false,
  pageLength: 10,
  renderer: 'bootstrap',
  ajax:{
    url: $('#tblFunction').data('action'),
    data: function (d) {
      d.create = $('#create').val();
      d.update = $('#update').val();
    }
  },
  columns: [
    {data: 'funct_id', name: 'funct_id', bVisible: false ,bFilter: false, bSortable: false},
    {data: 'pj_name', name: 'pj_name'},
    {data: 'module_name', name: 'module_name'},
    {data: 'ft_name', name: 'ft_name'},
    {data: 'sh_name', name: 'sh_name'},
    {data: 'st_name', name: 'st_name'},
    {data: 'ft_update', name: 'ft_update'},
    {
      data: function (data) {
        return getActionButton(data);
      }, name: 'manage', bFilter: false, bSortable: false
    }
  ]
});
});

function getActionButton(data){
  console.log(data);
var
  _html = '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="getEditFunction(' + data.funct_id +','+ data.pj_id +')">'
  _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
  _html += '</button>'
  _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteFunction(' + data.funct_id + ')">'
  _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
  _html += '</button>'
return _html;
}

/*Buttom show --> manage_plans*/
function viewplans(id) {
  window.location.href = 'module' + '/' + id;
}

// function notifylistdetail(ng_id) {
//     bf_pg = $("#bf_pg").val();
//     window.location.href = url('notice/detail/') + bf_pg +'/'+ ng_id + '/edit';
// }

/*Buttom show --> manage_plans*/
function manageteam(id) {
  window.location.href = url('manageteam') + id;
}


/*Delete Project*/
function deleteFunction(funct_id) {
confirm("คุณต้องการลบฟังก์ชันนี้หรือไม่ ?", function () {
  console.log(funct_id);
  $.ajax({
    url: 'functionmanage/delete' + '/' + funct_id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (data.status) {
      //   confirm("คุณต้องการลบฟังก์ชันนี้หรือไม่ ?");
        alert("แจ้งเตือน",data.message, function () {
        });
        reloadTableFunction();
      } else {
        alert("แจ้งเตือน",data.message, function (){});
      }
    }
  });
});
}

function add() {
  $('#formModule').validate({
    submitHandler: function (form) {
      $(form).ajaxSubmit({
        type: $("#formModule").attr('method'),
        url: $("#formModule").attr('action'),
        dataType: 'JSON',
        success: function (data) {
          if (data.status) {
            alert('แจ้งเตือน',data.message, function () {
            });
          }
          setTimeout(function(){ location.reload(); }, 1000);
        }
      });
    }
  });
}
/*end Delete Function*/

/*Edit Function*/
function getEditFunction(funct_id,pj_id) {
  Fund_Type_Modal = openModal('Edit Function', '/functionmanage' + '/' + funct_id + '/edit', 'md',
  function(){

    $('#formEditfunction').validate({
      errorElement: 'em',
      errorPlacement: function (error, element) {
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
          type: $("#formEditfunction").attr('method'),
          url: $("#formEditfunction").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {
                // Fund_Type_Modal.modal('hide');
                // reloadTableFunction();
              });
              Fund_Type_Modal.modal('hide');
              reloadTableFunction();
            } else {
              alert(data.message);
            }
          }
        });
      }
    });
  }
  , function () {
  });
}
/*end Edit Function*/

function reloadTableFunction(){
  oTableFunction.fnDraw();
}

/*dependent Select Drop down project*/
$('#project').change(function () {
    var project_id = $(this).val();
    var module_id = null;
    get_dropdown_module(project_id, module_id);
});
function get_dropdown_module(project_id, module_id){
  $.ajax({
    type: 'GET',
    url: '/functionmanage/get_molfrompro/' + project_id,
    dataType: 'json',
    success: function (data) {
      $('#module').prop('disabled', false);
      $("#module").empty();
      $("#module").append('<option selected disabled value="">' + '--- Choose Module ---' + '</option>');
      $.each(data, function (index, value) {
        $("#module").append('<option selected value="' + index + '">' + value + '</option>');
        $("#module").html($('#module option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#module").get(0).selectedIndex = 0;
      });
    }
  });
}
/*End dependent Select Drop down project*/

/*dependent Select Drop down Module*/
// $('#module').change(function () {
//
//     get_dropdown_module(project_id, module_id);
// });
