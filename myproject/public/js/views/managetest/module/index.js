var oTableManageModule; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
oTableManageModule = $('#tblManagemodule').dataTable(
{
  processing: true,
  serverSide: true,
  bFilter: true,
  bLengthChange:false,
  pageLength: 10,
  renderer: 'bootstrap',
  ajax:{
    url: $('#tblManagemodule').data('action'),
    data: function (d) {
      d.create = $('#create').val();
      d.update = $('#update').val();
    }
  },
  columns: [
    {data: 'module_id', name: 'module_id', bVisible: false ,bFilter: false, bSortable: false},
    {data: 'p_name', name: 'p_name'},
    {data: 'name', name: 'name'},
    {data: 'user_update', name: 'user_update'},
    {data: 'managemodules_update', name: 'managemodules_update'},
    {
      data: function (data) {
        return getActionButton(data);
      }, name: 'manage', bFilter: false, bSortable: false
    }
  ]
});
});


function getActionButton(data){
var _html = '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="getEditModule(' + data.module_id + ')">'
  _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
  _html += '</button>'
  _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteModule(' + data.module_id + ')">'
  _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
  _html += '</button>'
return _html;
}



/*Delete Project*/
function deleteModule(module_id) {
confirm("คุณต้องการลบมอดูลนี้หรือไม่ ?", function () {
  console.log(module_id);
  $.ajax({
    url: 'module/delete' + '/' + module_id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (data.status) {
      alert("แจ้งเตือน",data.message, function () {
        });
        reloadTableManagemodule();
      } else {
        alert("แจ้งเตือน",data.message, function (){});
      }
    }
  });
});
}


function reloadTableManagemodule()
{
  oTableManageModule.fnDraw();
}
/*end Delete Project*/
/*
*
*/

function getEditModule(id) {
  Fund_Type_Modal = openModal('Edit Module', '/module' + '/' + id + '/edit', 'md',
  function(){
    $('#formEditmodule').validate({
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
          type: $("#formEditmodule").attr('method'),
          url: $("#formEditmodule").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {

              });
              Fund_Type_Modal.modal('hide');
              reloadTableManagemodule();
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
