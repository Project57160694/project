var oTableManageProject; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
oTableManageProject = $('#tblManageProject').dataTable(
{
  processing: true,
  serverSide: true,
  bFilter: true,
  bLengthChange:false,
  pageLength: 10,
  renderer: 'bootstrap',
  ajax:{
    url: $('#tblManageProject').data('action'),
    data: function (d) {
      d.create = $('#create').val();
      d.update = $('#update').val();
    }
  },
  columns: [
    {data: 'id', name: 'id', bVisible: false ,bFilter: false, bSortable: false},
    {data: 'name', name: 'name'},
    {data: 'role', name: 'role'},
    {data: 'status_name', name: 'status_name'},
    {data: 'num_member', name: 'num_member'},
    {data: 'manage_projects_update', name: 'manage_projects_update'},
    {
      data: function (data) {
        return getActionButton(data);
      }, name: 'manage', bFilter: false, bSortable: false
    }
  ]
});
});

function getActionButton(data){
var _html = '<button class="btn btn-success btn-xs" id="show" type="button"onclick="ShowMember(' + data.id + ')">';
  _html += '<i class="glyphicon glyphicon-user"> View </i>';
  _html += '</button>'
  _html += '<button class="btn btn-primary btn-xs" id="show" type="button"onclick="viewplans(' + data.id + ')">';
  _html += '<i class="glyphicon glyphicon-zoom-in"> View </i>';
  _html += '</button>'
  _html += '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="getEditProject(' + data.id + ')">'
  _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
  _html += '</button>'
  _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteProject(' + data.id + ')">'
  _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
  _html += '</button>'
return _html;
}

// Route::get('manageteam/project/{id}/edit', 'ManageteamController@index');

// function ShowMember(id) {
//     window.location.href = '/manageteam' + '/project/' + id + '/edit';
// }
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
function deleteProject(id) {
confirm("คุณต้องการลบโครงการนี้หรือไม่ ?", function () {
  console.log(id);
  $.ajax({
    url: 'profile/delete' + '/' + id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (data.status) {
      alert("แจ้งเตือน",data.message, function () {
        });
        reloadTableManageProject();
      } else {
        alert("แจ้งเตือน",'ไม่สามารถลบได้');
      }
    }
  });
});
}


function reloadTableManageProject()
{
  oTableManageProject.fnDraw();
}
/*end Delete Project*/
/*
*
*/
function getEditProject(id) {
  Fund_Type_Modal = openModal('Edit project', '/manageproject' + '/' + id + '/edit', 'lg',
  function(){
    $('#formEdit').validate({
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
          type: $("#formEdit").attr('method'),
          url: $("#formEdit").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {

              });
              Fund_Type_Modal.modal('hide');
              reloadTableManageProject();
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
