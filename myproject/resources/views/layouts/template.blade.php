<!--
Project name : Softtest
Floder : resources/admin/layouts
File : tamplate.blade.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ไฟล์นี้ใช้สำหรับการประกอบร่างของทุก ๆ ไฟล์ที่ได้สร้างไว้ข้างต้น และเป็นส่วนของการเก็บโครงสร้าง html หลักเอาไว้
-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.ico" type="image/ico" />
  <title>Soft Test - @yield('page_title')</title>
 <!--include file stylesheet.bladde.php-->
  @include('layouts.stylesheet')
  <!--ใช้แสดงการดึงไฟล์ Css ของแต่ละเพจที่ใช้งานไม่เหมือนกันออกมาแสดงผล-->
  @yield('stylesheet')
</head>
<body>
  <body class="nav-sm">
    <div class="container body">
    <div class="main_container">
      <!--include file slide-menu.bladde.php-->
      @include('layouts.slide-menu')
      <!--include file header.bladde.php-->
      @include('layouts.header')
      <!-- Page Content -->
      <!--แสสดงเนื้อหาของเพจซึ่งจะแสดงผลต่าง ๆ กันไปขึ้นอยู่กับการสั่งงานผ่าน route และ controller-->
      @yield('content')
    </div>
    <footer>
      <div class="pull-right">
        Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- <script type="text/javascript">
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script> -->
<!--include file scripts.bladde.php-->
@include('layouts.scripts')
<!-- แสดงการดึงไฟล์ jquery, javascript ของแต่ละเพจที่ใช้งานไม่เหมือนกันออกมาแสดงผล-->
@yield('scripts')
</body>
</html>
