@if(!empty($managemodule->id))
                  <form id="formEditmodule" action="{{ url('module'). '/' . $managemodule->id }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEditmodule" action="{{ url('module') }}" method="POST">
                @endif
  @csrf
  <!--Web page-->
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project">Project *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-8" id="project" name="project" required autofocus>
        <option selected  value="">--- Choose project ---</option>
        @foreach ($project_list as $key => $project_list)
        <option value="{{$key}}" <?= ($managemodule->project_id == $key) ? 'selected' : '' ?>>{{$project_list}}</option>
        @endforeach
      </select>
  </div>
  <br><br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Module *<!--<span class="required">*</span>-->
    </label>
    <div class="col-xs-7">
      <input id="module_name" type="text" class="form-control col-md-7 col-xs-12{{ $errors->has('module_name') ? ' is-invalid' : '' }}" name="module_name"
      value="{{$managemodule->module_name}}" required autofocus>
    </div>
  </div>
  <br><br><br><br>
  <div class="form-group pull-right" >
    <button type="submit" class="btn btn-sm btn-success">Submit</button>
    <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
  </div>
</form>
