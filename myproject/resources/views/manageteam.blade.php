<!--
Project name : Softtest
Floder :
File : index.blade.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ใช้กำหนดหน้า content
-->
<!--ใช้งาน Template ที่สร้างไว้เป็น template หลัก ใน resources/admin/layouts/tamplate.blade.php-->
@extends('layouts.template')
<!--การเริ่มต้นสคริปที่จะใช้แสดงผลในส่วนของ contentที่ฝังไว้ template.blade.php -->
@section('page_title','Team Management')
@section('content')
<div class="right_col" role="main">
  <div class="row">
    <div class="page-title">
      <div class="title_left">
        <h3>Team Management 
        </h3>
      </div>
      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
              <button class="btn btn-default" type="button">Go!</button>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Add Member For team </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
            <form id="formAddmem" action="{{ url('manageteam/create') }}" method="POST" data-parsley-validate class="form-horizontal form-label-left">
              @csrf
              <!--Web page-->
              <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Member *</label>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                              <select class="form-control" id="name_mem" name="name_mem" required autofocus>
                                <option selected  value="">--- Choose Member ---</option>
                                @foreach ($namemem_list as $key => $namemem_list)
                                <option value="{{$key}}" <?= ($managemember->name_id == $key) ? 'selected' : '' ?>>{{$namemem_list}}</option>
                                @endforeach
                              </select>
                            </div>
              </div>
              <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role *</label>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                              <select class="form-control" id="role" name="role" required autofocus>
                                <option selected  value="">--- Choose Role ---</option>
                                @foreach ($role_list as $key => $role_list)
                                <option value="{{$key}}" <?= ($managemember->role_id == $key) ? 'selected' : '' ?>>{{$role_list}}</option>
                                @endforeach
                              </select>
                            </div>
              </div>
              <!--URL Web page-->

              <div class="form-group ">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button class="btn btn-primary" type="reset">Reset</button>
                  <button type="submit" class="btn btn-success" id="btnInsertweb">Submit</button>
                </div>
              </div>
              <input type ="hidden" name= "project_id" value = "{{$project}}">
            </form>
            <div class="ln_solid"></div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="m_panel">
            <div class="x_content">
              <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row col-sm-12">
                    <table id="tblMember" class="table table-bordered table-striped"
                      data-action="{{action('ManageteamController@getTeamDatatables', $project)}}"style="width: 100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th style="width: 20%">Name</th>
                          <th style="width: 20%">Email</th>
                          <th style="width: 15%">Role</th>
                          <th style="width: 15%">Updated By</th>
                          <th style="width: 15%">Updated</th>
                          <th style="width: 20%">Management</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@stop
@section('scripts')
<script src="{{ asset('js/views/managetest/member/index.js') }}"></script>
@stop
