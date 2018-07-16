@php
if(!empty($manageprojects->id)){
  $url = url('manageproject'). '/' . $manageprojects->id;
  $method = 'PATCH';
}else{
  $url = url('manageproject');
  $method = 'POST';
}
@endphp

@if(!empty($manageprojects->id))
                  <form id="formEdit" action="{{ url('manageproject'). '/' . $manageprojects->id }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEdit" action="{{ url('manageproject') }}" method="POST">
                @endif
  @csrf
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Project *<!--<span class="required">*</span>-->
    </label>
    <div class="col-md-4 col-sm-12 col-xs-12">
      <input id="project_name" type="text" class="form-control col-md-7 col-xs-12{{ $errors->has('project_name') ? ' is-invalid' : '' }}" name="project_name"
      value="{{$manageprojects->project_name}}" required autofocus>
    </div>
  </div>
  <br><br>
  <div class="form-group pull-right" >
    <button type="submit" class="btn btn-sm btn-success">Submit</button>
    <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
  </div>
</form>
