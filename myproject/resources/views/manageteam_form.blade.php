@if(!empty($managemember->id))
<style>
input[type=text]:disabled {
    background: #dddddd;
}
</style>
                  <form id="formEditmem" action="{{ url('manageteam'). '/' . $managemember['id'] }}" method="POST">
                  <input name="_method" type="hidden" value="PUT">
                @else
                  <form id="formEditmem" action="{{ url('manageteam') }}" method="POST">
               @endif
               @csrf
               <input name="project_id" type="hidden" value="{{ !empty($managemember->project_id) ? $managemember->project_id : '' }}" >
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name_mem">Member *</label>
    <div class="col-xs-7">
      <!-- <select class="form-control col-xs-8" id="name_mem" name="name_mem">
        <option selected  value="">--- Choose members ---</option>
        @foreach ($namemem_list as $key => $namemem_list)
        <option value="{{$key}}" <?= ($managemember->user_id == $key) ? 'selected' : '' ?>>{{$namemem_list}}</option>
        @endforeach
      </select> -->
      <input type="text" class="form-control col-xs-8" value="{{ $namemem_list['name'] }}" disabled>
      <input type="hidden" name="name_mem" value="{{ $namemem_list['id'] }}">
  </div>
</div>
  <br><br><br>
  <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-10" for="role">Roles *</label>
    <div class="col-xs-7">
      <select class="form-control col-xs-5" id="role" name="role" required autofocus>
        <option selected  value="">--- Choose Roles ---</option>
        @foreach ($role_list as $key => $role_list)
        <option value="{{$key}}" <?= ($managemember->role_id == $key) ? 'selected' : '' ?>>{{$role_list}}</option>
        @endforeach
      </select>
    </div>
  </div>

<br><br><br>
<div class="form-group pull-right" >
  <button type="submit" class="btn btn-sm btn-success">Submit</button>
  <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
</div>
</form>
