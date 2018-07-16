
@if(!empty($function->id))
                  <form id="formEditfunction" action="{{ url('functionmanage'). '/' . $function->id }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEditfunction" action="{{ url('functionmanage') }}" method="POST">
                 @endif

  @csrf
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project">Project *</label>
    <div class="col-xs-7">
      <select class="form-control select2-single col-xs-8" id="project_2" name="project" required autofocus>
        @foreach ($project_list as $key => $project_list)
        <option value="{{$key}}" <?= ($function->project_id == $key) ? 'selected' : '' ?>>{{$project_list}}</option>
        @endforeach
      </select>
  </div>
  <br><br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-10" for="project">Module *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-5" id="module_2" name="module" required autofocus>
        @foreach ($module_list as $key => $module_list)
        <option value="{{$module_list['id']}}" <?= ($function->module_id == $module_list['id']) ? 'selected' : '' ?>>{{$module_list['module_name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="function_name">Function *<!--<span class="required">*</span>-->
    </label>
    <div class="col-xs-7">
      <input id="function_name" type="text" class="form-control col-xs-8{{ $errors->has('_function_name') ? ' is-invalid' : '' }}" name="function_name"
      value="{{$function->function_name}}" required autofocus>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module_name">Short Name *<!--<span class="required">*</span>-->
    </label>
    <div class="col-xs-7">
      <input id="short_name" type="text" class="form-control col-xs-8{{ $errors->has('_short_name') ? ' is-invalid' : '' }}" name="short_name"
      value="{{$function->short_name}}" required autofocus>
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
      url: '/functionmanage/get_molfrompro/' + project_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#module_2').prop('disabled', false);
        $("#module_2").empty();
        $("#module_2").append('<option selected disabled value="">' + '--- Choose module ---' + '</option>');
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
</script>
</form>
