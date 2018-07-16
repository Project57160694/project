var oTableTestplan; //ตัวแปรที่ใช้เก็บข้อมูล
$(function()
{
  oTableTestplan =  $('#tblTestplan').dataTable(
    {
      processing: true,
      serverSide: true,
      bFilter: true,
      bLengthChange:false,
      pageLength: 10,
      renderer: 'bootstrap',
      ajax:{
        url: $('#tblTestplan').data('action'),
        data: function (d) {
          d.create = $('#create').val();
          d.update = $('#update').val();
        }
      },
      columns: [
          {data: 'plan_id', name: 'plan_id', bVisible: false ,bFilter: false, bSortable: false},
          {data: 'pro_name', name: 'pro_name'},
          {data: 'name_module', name: 'name_module'},
          {data: 'name_funct', name: 'name_funct'},
          {data: 'plan_n', name: 'plan_n'},
          {data: 'plans_update', name: 'plans_update'},
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
    _html = '<button class="btn btn-warning btn-xs" id="edit" type="button" onclick="editProject(' + data.plan_id + ')">'
    _html += '<i class="glyphicon glyphicon-pencil"> Edit </i>';
    _html += '</button>'
    _html += '<button class="btn btn-danger btn-xs" id="delete" type="button" onclick="deletePlan(' + data.plan_id + ')">'
    _html += '<i class="glyphicon glyphicon-trash"> Delete </i>';
    _html += '</button>'
  return _html;
}

function deletePlan(plan_id) {
confirm("คุณต้องการลบแผนนี้หรือไม่ ?", function () {
  console.log(plan_id);
  $.ajax({
    url: 'plantest/delete' + '/' + plan_id,
    type: "GET",
    dataType: "json",
    success: function (data) {
      if (data.status) {
      alert("แจ้งเตือน",data.message, function () {
        });
        reloadTablePlan();
      } else {
        alert("แจ้งเตือน",data.message, function () {
          });
      }
    }
  });
});
}

function reloadTablePlan()
{
  oTableTestplan.fnDraw();
}

function editProject(plan_id) {
  Fund_Type_Modal = openModal('Edit Plan', '/plantest' + '/' + plan_id + '/edit', 'lg',
  function(){
    $('#formEditplan').validate({
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
          type: $("#formEditplan").attr('method'),
          url: $("#formEditplan").attr('action'),
          dataType: 'JSON',
          success: function (data) {
            if (data.status) {
              alert('แจ้งเตือน',data.message, function () {

              });
              Fund_Type_Modal.modal('hide');
              reloadTablePlan();
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
    get_dropdown_module(project_id, module_id);
});
function get_dropdown_module(project_id, module_id){
  $.ajax({
    type: 'GET',
    url: '/plantest/get_molfrompro/' + project_id,
    dataType: 'json',
    success: function (data) {
      $('#module').prop('disabled', false);
      $("#module").empty();
      $('#function_id').prop('disabled', false);
      $("#function_id").empty();
      $("#module").append('<option selected value="">' + '--- Choose Module ---' + '</option>');
      $("#function_id").append('<option selected value="">' + '--- Choose Function ---' + '</option>');
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
        $("#function_id").append('<option selected value="' + index + '">' + value + '</option>');
        $("#function_id").html($('#function_id option').sort(function (x, y) {
          return $(x).val() < $(y).val() ? -1 : 1;
        }));
        $("#function_id").get(0).selectedIndex = 0;
      });
    }
  });
}

/*End dependent Select Drop down Module*/
