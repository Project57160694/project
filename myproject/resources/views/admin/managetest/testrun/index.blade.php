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
@section('page_title','Management Project')
@section('content')
<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
        <div class="row x_title">
          <div class="col-md-6">
            <h3>Project Management</h3>
            <div class="row">
              <div class="col-sm-6">
                <div class="dataTables_length" id="datatable_length">
                  <a href="{{ URL('/manageproject/create') }}" class="create_project_modal"><button type="button" class="btn btn-info btn-lg">Create Project</button></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="m_panel">
          <div class="x_content">
            <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <div class="row"><div class="col-sm-12">
              <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info"
              data-action="{{action('NoticeGroupController@getdataNotifyGroup')}}">
              <thead>
                <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 20px;">#</th>
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 200px;">Project Name</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 50px;">Member</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 120px;">status</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 97px;">Created</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 5px;">Management</th></thead>
              <tbody>
                  <tr role="row" class="odd">
                    <td class="sorting_1">1</td>
                    <td>fff</td>
                    <td>4 <a href="#" class="show_project_modal btn">
                      <i class="glyphicon glyphicon-user"></i>
                    </a></td>
                    <td>fff</td>
                    <td>fff</td>
                    <center><td >
                      <a href="#" class="show_project_modal btn btn-info">
                        <i class="glyphicon glyphicon-zoom-in"></i>
                      </a>
                      <a href="#" class="edit_project_modal btn btn-warning">
                        <i class="glyphicon glyphicon-pencil"></i>
                      </a>
                      <a href="#" class="delete_project_modal btn btn-danger">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                    </td></center>
                  </tr>
            </tbody>
            </table></div></div>
            <div class="row">
              <div class="col-sm-5">
                <div class="dataTables_info" id="datatable-checkbox_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
              </div>
              <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="datatable-checkbox_paginate">
                <ul class="pagination">
                <li class="paginate_button previous disabled" id="datatable-checkbox_previous"><a href="#" aria-controls="datatable-checkbox" data-dt-idx="0" tabindex="0">Previous</a></li>
                <li class="paginate_button active"><a href="#" aria-controls="datatable-checkbox" data-dt-idx="1" tabindex="0">1</a></li>
                <li class="paginate_button "><a href="#" aria-controls="datatable-checkbox" data-dt-idx="2" tabindex="0">2</a></li>
                <li class="paginate_button "><a href="#" aria-controls="datatable-checkbox" data-dt-idx="3" tabindex="0">3</a></li>
                <li class="paginate_button "><a href="#" aria-controls="datatable-checkbox" data-dt-idx="4" tabindex="0">4</a></li>
                <li class="paginate_button "><a href="#" aria-controls="datatable-checkbox" data-dt-idx="5" tabindex="0">5</a></li>
                <li class="paginate_button "><a href="#" aria-controls="datatable-checkbox" data-dt-idx="6" tabindex="0">6</a></li>
                <li class="paginate_button next" id="datatable-checkbox_next"><a href="#" aria-controls="datatable-checkbox" data-dt-idx="7" tabindex="0">Next</a></li>
              </ul>
              </div>
        </div>
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
@stop
