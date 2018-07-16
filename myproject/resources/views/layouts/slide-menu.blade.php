<!--
Project name : Softtest
Floder : resources/admin/layouts
File : slide-menu.blade.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ใช้เก็บสคริปในส่วนของการแสดงผลเมนูด้านซ้ายของเพจ
-->
<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col menu_fixed">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
      <a href="{{action('ManageprojectController@index')}}" class="site_title"><i class="fa fa-paw"></i> <span>Sotf Test</span></a>
    </div>
    <div class="clearfix"></div>
    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic"><img src="{{asset('gentelella-master/production/images/img.jpg')}}" alt="..." class="img-circle profile_img"></div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2>{{ Auth::user()->name }}</h2>
      </div>
    </div>
    <!-- /menu profile quick info --><br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>MENU</h3>
        <ul class="nav side-menu">
          <li><a><i class="fa fa-edit"></i> Project Management <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{action('ManageprojectController@index')}}">Project Management</a></li>

            </ul>
          </li>
          <li><a><i class="fa fa-book"></i> Test Management <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="{{action('ModuletestController@index')}}">Modules</a></li>
              <li><a href="{{action('FunctionController@index')}}">Functions</a></li>
              <li><a href="{{action('TestplanController@index')}}"> Test Plan </a></li>
              <li><a href="{{action('ManagecaseController@index')}}">Test Case</a></li>
            </ul>
          </li>
      </div>
        </ul>
      </div>

    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>
