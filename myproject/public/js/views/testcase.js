var oTableTestcase; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
  oTableTestcase =  $('#tblTestcase').dataTable({
      // "columnDefs": [
      //   { className: "dt-head-center", "targets": [ 0 ] }
      // ]


      columnDefs : 'dt-head-center',
      processing: true,
      serverSide: true,
      bFilter: true,
      bLengthChange:false,
      pageLength: 10,
      renderer: 'bootstrap',
      ajax:{
        url: $('#tblTestcase').data('action'),
        data: function (d) {
          d.create = $('#create').val();
          d.update = $('#update').val();
        }
      },
      columns: [
          {data: 'id', name: 'id', bVisible: false ,bFilter: false, bSortable: false},
          {data: 'pro_name', name: 'pro_name'},
          {data: 'name_module', name: 'name_module'},
          {data: 'name_funct', name: 'name_funct'},
          {data: 'plan_n', name: 'plan_n'},
          {data: 'case_name', name: 'case_name'},
          {data: 'tester', name: 'tester'},
          {data: 'case_status', name: 'case_status'},
          {data: 'case_update', name: 'case_update'},
          {
            data: function (data) {
              return getActionButton(data);
            }, name: 'manage', bFilter: false, bSortable: false
          }
      ]
    });
});

function getActionButton(data){
  var
    _html = '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="editTestcase(' + data.id + ')">'
    _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
    _html += '</button>'
    _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteCase(' + data.id + ')">'
    _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
    _html += '</button>'
  return _html;
}

function deleteCase(id) {
  confirm("คุณต้องการลบกรณีการทดสอบนี้หรือไม่ ?", function () {
    console.log(id);
    $.ajax({
      url: 'testcasemanage/delete' + '/' + id,
      type: "GET",
      dataType: "json",
      success: function (data) {
        if (data.status) {
        alert("แจ้งเตือน",data.message, function () {
          });
          reloadTableCase();
        } else {
          alert("แจ้งเตือน",data.message, function (){});
        }
      }
    });
  });
}
function add() {
  $('#testcase').validate({
    submitHandler: function (form) {
      $(form).ajaxSubmit({
        type: $("#testcase").attr('method'),
        url: $("#testcase").attr('action'),
        dataType: 'JSON',
        success: function (data) {
          if (data.status) {
            alert("แจ้งเตือน",data.message, function () {
                // window.location.reload(true);
            });
            reloadTableCase();
          }
          // setTimeout(function(){ location.reload(); }, 1000);
        }
      });
      $('#testcase')[0].reset();
    }
  });

}
function reloadTableCase(){
  oTableTestcase.fnDraw();
}

function editTestcase(id) {
  Fund_Type_Modal = openModal('Edit Test case', '/testcasemanage' + '/' + id + '/edit', 'lg',
  function(){
    $('#formEditcase').validate({
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
          type: $("#formEditcase").attr('method'),
          url: $("#formEditcase").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {

              });
              Fund_Type_Modal.modal('hide');
              reloadTableCase();
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

/*dependent Select Drop down project*/
$('#project').change(function () {
    var project_id = $(this).val();
    var module_id = null;
    get_dropdown_plan(project_id, plan_id);
});
function get_dropdown_plan(project_id, plan_id){
  $.ajax({
    type: 'GET',
    url: '/testcasemanage/get_planfrompro/' + project_id,
    dataType: 'json',
    success: function (data) {
      console.log(data);
      $('#plan_id').prop('disabled', false);
      $("#plan_id").empty();
      $('#module').prop('disabled', false);
      $("#module").empty();
      $('#function_id').prop('disabled', false);
      $("#function_id").empty();
      $('#name').prop('disabled', false);
      $("#name").empty();
      $("#plan_id").append('<option selected value="">' + '--- Choose Test Plan ---' + '</option>');
      $("#module").append('<option selected value="">' + '--- Choose Module ---' + '</option>');
      $("#function_id").append('<option selected value="">' + '--- Choose Function ---' + '</option>');
      $("#name").append('<option selected value="">' + '--- Choose Tester ---' + '</option>');
      var i=0;
      var j=0;
      $.each(data['planlist'], function (index, value) {
        // console.log(i);
        $("#plan_id").append('<option value="' + data.planlist[i]['id'] + '">' + data.planlist[i]['plan_name'] + '</option>');
        $("#plan_id").html($('#plan_id option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#plan_id").get(0).selectedIndex = 0;
        i++;
      });
      $.each(data['project'], function (index, value) {
        console.log(j);
        $("#name").append('<option value="' + data.project[j]['id'] + '">' + data.project[j]['name'] + '</option>');
        $("#name").html($('#name option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#name").get(0).selectedIndex = 0;
        j++;
      });
    }
  });
}
/*End dependent Select Drop down project*/

/*dependent Select Drop down Plan*/
$('#plan_id').change(function () {
    var plan_id = $(this).val();
    var module_id = null;
    get_dropdown_module(plan_id, module_id);
});
function get_dropdown_module(plan_id, module_id){
  $.ajax({
    type: 'GET',
    url: '/testcasemanage/get_molfromplan/' + plan_id,
    dataType: 'json',
    success: function (data) {
      $('#module').prop('disabled', false);
      $("#module").empty();
      $('#function_id').prop('disabled', false);
      $("#function_id").empty();
      $("#module").append('<option selected value="">' + '--- Choose Module ---' + '</option>');
      $("#function_id").append('<option selected value="">' + '--- Choose Function ---' + '</option>');
      $.each(data, function (index, value) {
        $("#module").append('<option value="' + index + '">' + value + '</option>');
        $("#module").html($('#module option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#module").get(0).selectedIndex = 0;
      });
    }
  });
}
/*End dependent Select Drop down Plan*/

/*dependent Select Drop down Module*/
$('#module').change(function () {
    var module_id = $(this).val();
    var function_id = null;
    get_dropdown_function(module_id, function_id);
});
function get_dropdown_function(module_id, function_id){
  $.ajax({
    type: 'GET',
    url: '/plantest/get_funcfrommol/' + module_id,
    dataType: 'json',
    success: function (data) {
      $('#function_id').prop('disabled', false);
      $("#function_id").empty();
      $("#function_id").append('<option selected value="">' + '--- Choose Function ---' + '</option>');
      $.each(data, function (index, value) {
        $("#function_id").append('<option value="' + index + '">' + value + '</option>');
        $("#function_id").html($('#function_id option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#function_id").get(0).selectedIndex = 0;
      });
    }
  });
}

/*End dependent Select Drop down Module*/


/*Chang Status Function When Test Pass all*/
$('.status').change(function () {
  var function_id = $(this).parent().parent().find($('.hidFunctID')).val();
  var status_code = $(this).val();
  var old_status = $(this).parent().parent().find($('.old_status')).val();
  if (status_code == '') {
  }
  else {
      changeStatusFunctionModal = openModal('เปลี่ยนสถานะ', url('status/change/view') + '/' + function_id + '/' + status_code, 'lg', function () {
        $('#formChangeStatus').validate({
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
            $(element).parents('.form-group').removeClass('has-danger');
          },
          submitHandler: function (form) {
            $(form).ajaxSubmit({
              type: $(form).attr('method'),
              url: $(form).attr('action'),
              dataType: 'JSON',
              success: function (data) {
              if (data.status) {
                alert(data.message, function () {
                  changeStatusFunctionModal.modal('hide');
                  changeStatusFunctionModal = null;
                  if (data.check_notice == 1) {
                      confirm('คุณต้องการเปลี่ยนสถานะงานเป็นจบงานหรือไม่', function () {
                        changeCaseStatus(data.function_id);
                      }, function () {
                        location.reload();
                      });
                  } else {
                    location.reload();
                  }
                });
                } else {
                    alert(data.message);
                } //if (data.status)
              } //success
        });
      } //submitHandler
    }); //formChangeStatus
  } //openmodal
  , function () {
          $('#status').val(old_status);
          changeStatusFunctionModal = null;
  });//changeStatusFunctionModal
}//else first
}); //status
/*End Chang Status Function When Test Pass all*/
