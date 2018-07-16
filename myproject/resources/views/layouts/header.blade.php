<!--
Project name : Softtest
Floder : resources/admin/layouts
File : header.blade.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : จะเก็บสคริปในส่วนของ header banner ที่แสดงผลชื่อเว็บ และเมนูด้านบนของเพจ
-->

<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="{{asset('gentelella-master/production/images/img.jpg')}}">{{ Auth::user()->name }}
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->
