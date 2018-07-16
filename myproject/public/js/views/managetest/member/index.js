var oTableMember; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
oTableMember = $('#tblMember').dataTable(
{
  processing: true,
  serverSide: true,
  bFilter: true,
  bLengthChange:false,
  pageLength: 10,
  renderer: 'bootstrap',
  ajax:{
    url: $('#tblMember').data('action'),
    data: function (d) {
      console.log(d);
      d.create = $('#create').val();
      d.update = $('#update').val();
    }
  },
  columns: [
    {data: 'id', name: 'id', bVisible: false ,bFilter: false, bSortable: false},
    {data: 'user_name', name: 'user_name'},
    {data: 'user_email', name: 'user_email'},
    {data: 'role', name: 'role'},
    {data: 'sub', name: 'sub'},
    {data: 'date_update', name: 'date_update'},
    {
      data: function (data) {
        return getActionButton(data);
      }, name: 'manage', bFilter: false, bSortable: false
    }
  ]
});
});

function getActionButton(data){
var _html = '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="getEditmem(' + data.id + ')">'
  _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
  _html += '</button>'
  _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deleteMember(' + data.id + ')">'
  _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
  _html += '</button>'
return _html;
}

function deleteMember(id) {
    confirm("คุณต้องการลบรายการนี้หรือไม่ ?", function () {
        $.ajax({
            url: 'manageteam/'+'delete' + '/' + id,
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data.status) {
                  alert("แจ้งเตือน",data.message, function () {
                  });
                    reloadTableManagemember();
                } else {
                    alert("แจ้งเตือน","ไม่สามารถลบรายการนี้ได้");
                }
            }
        });
    });
  }

$('#formAddmem').validate({
    submitHandler: function (form) {
        $(form).ajaxSubmit({
            type: $("#formAddmem").attr('method'),
            url: $("#formAddmem").attr('action'),
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    alert("แจ้งเตือน",data.message, function () {
                    });
                    reloadTableManagemember();
                } else {
                    alert("แจ้งเตือน",data.message);
                }
            }
        });
    }
});



function reloadTableManagemember()
{
  oTableMember.fnDraw();
}
/*end Delete Project*/
/*
*
*/
// function getEditmem(id) {
//   Fund_Type_Modal = openModal('Edit member', '/manageteam' + '/' + id + 'edit' , 'lg',
//   function(){
//     $('#formEditmem').validate({
//       errorElement: 'em',
//       errorPlacement: function (error, element) {
//         error.addClass('form-control-feedback');
//         if (element.prop('type') === 'checkbox') {
//           error.insertAfter(element.parent('label'));
//         } else {
//           error.insertAfter(element);
//         }
//       },
//       highlight: function (element, errorClass, validClass) {
//         $(element).addClass('form-control-danger').removeClass('form-control-success');
//         $(element).parents('.form-group').addClass('has-danger').removeClass('has-success');
//       },
//       unhighlight: function (element, errorClass, validClass) {
//         $(element).addClass('form-control-success').removeClass('form-control-danger');
//         $(element).parents('.form-group').removeClass('has-danger');
//       },
//       submitHandler: function (form) {
//         $(form).ajaxSubmit({
//           type: $("#formEditmem").attr('method'),
//           url: $("#formEditmem").attr('action'),
//           dataType: 'JSON',
//           success: function (data) {
//             if (data.status) {
//               alert("แจ้งเตือน",data.message, function () {
//               });
//               Fund_Type_Modal.modal('hide');
//               reloadTableManagemember();
//             } else {
//               alert("แจ้งเตือน",data.message);
//             }
//           }
//         });
//       }
//     });
//   }
//   , function () {
//   });
// }


function getEditmem(id) {
  console.log(id);
  Fund_Type_Modal = openModal('Edit Member', '/manageteam' + '/' + id + '/edit', 'md',
  function(){
    $('#formEditmem').validate({
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
          type: $("#formEditmem").attr('method'),
          url: $("#formEditmem").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {
              });
              Fund_Type_Modal.modal('hide');
              reloadTableManagemember();
            } else {
              alert('แจ้งเตือน',data.message);
            }
          }
        });
      }
    });
  }
  , function () {
  });
}
