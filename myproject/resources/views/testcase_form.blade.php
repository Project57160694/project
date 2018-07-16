@if(!empty($testcase->id))
                  <form id="formEditcase" action="{{ url('testcasemanage'). '/' . $testcase->id }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEditcase" action="{{ url('testcasemanage') }}" method="POST">
               @endif
  @csrf
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project_name">Project *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-8" id="project_2" name="project_name" required autofocus>
        @foreach ($project_list as $key => $project_list)
          <option value="{{$key}}" <?= ($testcase->project_id == $key) ? 'selected' : '' ?>>{{$project_list}}</option>
        @endforeach
      </select>
  </div>
  <br><br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-10" for="plan_name">Plan *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-5" id="plan_2" name="plan_name" required autofocus>
        @foreach ($plan_list as $key => $plan_list)
        <option value="{{$plan_list['id']}}" <?= ($testcase->plan_id == $plan_list['id']) ? 'selected' : '' ?>>{{$plan_list['plan_name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-10" for="module_name">Module *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-5" id="module_2" name="module_name" required autofocus>
        @foreach ($module_list as $key => $module_list)
        <option value="{{$module_list['id']}}" <?= ($testcase->module_id == $module_list['id']) ? 'selected' : '' ?>>{{$module_list['module_name']}}</option>
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
        <option value="{{$function_list['id']}}" <?= ($testcase->function_id == $function_list['id']) ? 'selected' : '' ?>>{{$function_list['function_name']}}</option>
        @endforeach
      </select>
    </div>
  </div>
  <br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_code">Case Code *<!--<span class="required">*</span>-->
    </label>
      <div class="col-xs-7">
      <input id="case_code" type="text" class="form-control col-md-7 col-xs-12{{ $errors->has('_case_code') ? ' is-invalid' : '' }}" name="case_code"
      value="{{$testcase->case_code}}" required autofocus>
    </div>
  </div>
</br>
<br><br>
<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="case_name">Test Case *<!--<span class="required">*</span>-->
  </label>
    <div class="col-xs-7">
    <input id="case_name" type="text" class="form-control col-md-7 col-xs-12{{ $errors->has('_case_name') ? ' is-invalid' : '' }}" name="case_name"
    value="{{$testcase->case_name}}" required autofocus>
  </div>
</div>
<br><br>
<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name" >Tester *</label>
              <div class="col-xs-7">
                <select class="form-control" id="name" name="name" required autofocus>
                  @foreach ($tester_list as $key => $tester_list)
                  <option value="{{$tester_list['id']}}" <?= ($testcase->user_id == $tester_list['id']) ? 'selected' : '' ?>>{{$tester_list['name']}}</option>
                  @endforeach
                </select>
              </div>
</div>
<br><br>
<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status_name">Status *</label>
              <div class="col-xs-7">
                <select class="form-control" id="status_name" name="status_name" required autofocus>
                  @foreach ($status_list as $key => $status_list)
                  <option value="{{$key}}" <?= ($testcase->status_code == $key) ? 'selected' : '' ?>>{{$status_list}}</option>
                  @endforeach
                </select>
              </div>
</div>
<br><br>
<br><br><br>
<div class="form-group pull-right" >
  <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
  <button type="button" class="btn btn-sm" data-dismiss="modal">ยกเลิก</button>
</div>
<script>
  $('#project_2').change(function () {
      var project_id = $(this).val();
      get_dropdown_module_2(project_id);
  });
  function get_dropdown_module_2(project_id){
    $.ajax({
      type: 'GET',
      url: '/testcasemanage/get_planfrompro/' + project_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#plan_2').prop('disabled', false);
        $("#plan_2").empty();
        $("#plan_2").append('<option selected disabled value="">' + '--- Choose Plan ---' + '</option>');
        $('#module_2').prop('disabled', false);
        $("#module_2").empty();
        $("#module_2").append('<option selected disabled value="">' + '--- Choose module ---' + '</option>');
        $('#function_2').prop('disabled', false);
        $("#function_2").empty();
        $("#function_2").append('<option selected disabled value="">' + '--- Choose function ---' + '</option>');
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

  $('#plan_2').change(function () {
      var module_id = $(this).val();
      get_dropdown_module_4(module_id);
  });
  function get_dropdown_module_4(module_id){
    $.ajax({
      type: 'GET',
      url: '/testcasemanage/get_funcfrommol/' + module_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#module_2').prop('disabled', false);
        $("#module_2").empty();
        $("#module_2").append('<option selected disabled value="">' + '--- Choose module ---' + '</option>');

        $('#function_2').prop('disabled', false);
        $("#function_2").empty();
        $("#function_2").append('<option selected disabled value="">' + '--- Choose function ---' + '</option>');
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
      url: '/testcasemanage/get_funcfrommol/' + module_id,
      dataType: 'json',
      success: function (data) {
        console.log(data);
        $('#function_2').prop('disabled', false);
        $("#function_2").empty();
        $("#function_2").append('<option selected disabled value="">' + '--- Choose module ---' + '</option>');
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
