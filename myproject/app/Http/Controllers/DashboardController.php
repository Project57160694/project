<?php
namespace App\Http\Controllers; // กำหนดที่อยู่ของ Controller
use Illuminate\Http\Request;

/*Project name : Softtest
Floder : app/Http/Controller
File : dashboardController.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ใชกำหนด Controller เพื่อสั่งการให้ laravel ประมวลผล*/


class DashboardController extends Controller
{

  public function getIndex(){
    return view('admin.dashboard.index');
  }

}
