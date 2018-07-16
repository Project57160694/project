<!--
Project name : Softtest
Floder : resources/admin/
File : login.blade.php
Name : Narumol kuntonkidja 57160694
Date : 14/4/2018
Description : ใช้ทำกำหนดหน้า login
-->
<!--ใช้งาน Template ที่สร้างไว้เป็น template หลัก ใน resources/admin/layouts/tamplate.blade.php-->
@extends('admin.layouts.stylesheet')
<!--การเริ่มต้นสคริปที่จะใช้แสดงผลในส่วนของ contentที่ฝังไว้ template.blade.php -->
@section('page_title','login')
<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form action="<?php echo url('login'); ?>" method="POST">
            <br />
            <div><h1>SoftTest Management</h1></div>
            <h1>Sign in</h1>
            <div>
              <input type="text" class="form-control" placeholder="ชื่อผู้ใช้" name="username" id="username" required>
            </div>
            <div>
              <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" id="password" required>
            </div>
            {!! csrf_field() !!}
            @if (session('msg'))
            <div class="alert">  {{ session('msg') }}</div>
            @endif
            <div>
              <button type="submit" class="btn btn-default submit px-4" value="Log in">Log in</button>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <p class="change_link">New to site?
                <a href="#signup" class="to_register"> Create Account </a>
              </p>
              <p class="change_link">or Sign in with</p>
              <div class="flex-c p-b-200">
                <i class="fa fa-facebook-square" style="font-size:48px;color:#00356a"></i>
                <i class="fa fa-google-plus-square" style="font-size:48px;color:#da002b"></i>
              </div>
              <div class="clearfix"></div>
              <br />
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</body>
