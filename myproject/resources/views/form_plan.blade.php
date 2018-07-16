@if(!empty($planmanage->id))
                  <form id="formEditplan" action="{{ url('plantest'). '/' . $planmanage->id }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEditplan" action="{{ url('plantest') }}" method="POST">
               @endif
  @csrf
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project">Project *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-8" id="project_2" name="project" required autofocus>
        @foreach ($project_list as $key => $project_list)
        <option value="{{$key}}" <?= ($planmanage->project_id == $key) ? 'selected' : '' ?> >{{$project_list}}</option>
        @endforeach
      </select>
  </div>
</div>

  <br><br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-10" for="module">Module *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-5" id="module_2" name="module" required autofocus>
        @foreach ($module_list as $key => $module_list)
        <option value="{{$module_list['id']}}"  <?= ($planmanage->module_id == $module_list['id']) ? 'selected' : '' ?>>{{$module_list['module_name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="function_name">Function *</label>
    <div class="col-xs-7">
      <select class="form-control" id="function_2" name="function_name" required autofocus>
        @foreach ($function_list as $key => $function_list)
        <option value="{{$function_list['id']}}" <?= ($planmanage->function_id == $function_list['id']) ? 'selected' : '' ?>>{{$function_list['function_name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plan_name">Plan Name *<!--<span class="required">*</span>-->
    </label>
      <div class="col-xs-7">
      <input id="plan_name" type="text" class="form-control col-md-7 col-xs-12{{ $errors->has('_plan_name') ? ' is-invalid' : '' }}" name="plan_name"
      value="{{$planmanage->plan_name}}" required autofocus>
    </div>
  </div>
</br>
<br><br><br>
<div class="form-group pull-right" >
  <button type="submit" class="btn btn-sm btn-success">Submit</button>
  <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
</div>
<script>
  $('#project_2').change(function () {
      var project_id = $(this).val();
      get_dropdown_module_2(project_id);
  });
  function get_dropdown_module_2(project_id){
    $.ajax({
      type: 'GET',
      url: '/plantest/get_molfrompro/' + project_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#module_2').prop('disabled', false);
        $("#module_2").empty();
        $("#module_2").append('<option selected disabled value="">' + '--- Choose Module ---' + '</option>');
        $.each(data, function (index, value) {
          $("#module_2").append('<option selected value="' + index + '">' + value + '</option>');
          $("#module_2").html($('#module_2 option').sort(function (x, y) {
            return $(x).val() < $(y).val() ? -1 : 1;
          }));
          $("#module_2").get(0).selectedIndex = 0;
        });
      }
    });
  }

  $('#module_2').change(function () {
      var module_id = $(this).val();
      get_dropdown_module_3(module_id);
  });
  function get_dropdown_module_3(module_id){
    $.ajax({
      type: 'GET',
      url: '/plantest/get_funcfrommol/' + module_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#function_2').prop('disabled', false);
        $("#function_2").empty();
        $("#function_2").append('<option selected disabled value="">' + '--- Choose Module ---' + '</option>');
        $.each(data, function (index, value) {
          $("#function_2").append('<option selected value="' + index + '">' + value + '</option>');
          $("#function_2").html($('#function_2 option').sort(function (x, y) {
            return $(x).val() < $(y).val() ? -1 : 1;
          }));
          $("#function_2").get(0).selectedIndex = 0;
        });
      }
    });
  }
</script>
</form>
