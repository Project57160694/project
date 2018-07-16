<!--
Project name : Softtest
Floder : resources/admin/dashboard
File : index.blade.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ใช้กำหนดหน้า content
-->
<!--ใช้งาน Template ที่สร้างไว้เป็น template หลัก ใน resources/admin/layouts/tamplate.blade.php-->
@extends('admin.layouts.template')
<!--การเริ่มต้นสคริปที่จะใช้แสดงผลในส่วนของ contentที่ฝังไว้ template.blade.php -->
@section('page_title','Module and Function')
@section('content')
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-6">
            <h3>Module & Function</h3>
            <div class="row">
              <div class="col-sm-6">
                <div class="dataTables_length" id="datatable_length">
                <button type="button" class="btn btn-primary" id="btnAddProject">Create module</button></a>
                <button type="button" class="btn btn-primary" id="btnAddProject">Create function</button></a>
                  <a href="{{ URL('/testplan/create') }}" class="btn btn-info"><i class="glyphicon glyphicon-print"></i> Report Test</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="m_panel">
          <div class="x_content">
            <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
              <!-- D I E B Y J A N E L O V E  -->
                <div class="row col-sm-12">
                  <table id="tblTestplan" class="table table-bordered table-striped"
                    data-action="{{action('TestplanController@getTestPlanDatatables')}}" style="width: 100%">
                    <thead>
                      <tr>
                        <th style="width: 5%" colspan="4"></th>
                        <th style="width: 30%"></th>
                      </tr>
                      <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">Function</th>
                        <th>Short name</th>
                        <th>Updated</th>
                        <th style="width: 20%">Managemen</th>
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
        <div class="clearfix"></div>
      </div>
    </div>

  </div>
  <br />

</div>
@stop
@section('scripts')
<script src="{{ asset('js/views/managetest/moduletest/index.js') }}"></script>
@stop
