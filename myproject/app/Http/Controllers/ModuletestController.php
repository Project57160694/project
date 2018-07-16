<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manageproject;
use App\Manageplan;
use App\Module;
use App\Status;
use App\Managefunction;
use App\Userproject;
use App\User;
use Symfony\Component\VarDumper\Cloner\Data;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use Carbon\Carbon;


/*Project name : Softtest
Floder : app/Http/Controller/Admins
File : ManageProjectController.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ใชกำหนด Controller ของมอดูลจัดการโปรเจค เพื่อสั่งการให้ laravel ประมวลผล*/

class ModuletestController extends Controller
{
    public function index(){
      // $statuslist = Status::select('status_name','code')
      //     ->where('module_work', 'Testcase')
      //     ->where('type', 'Minor')
      //     ->get();
      $user = Auth::user()->id;
      $managemodule = new Module;
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->get();
      $data = array('project_list' => $projectlist,
                    'managemodule' => $managemodule
                    );
      return view('module', $data);
    }

    public function create(){

    }

    public function store(Request $request)
    {
      $managemodule = new Module;
      $managemodule->project_id = Input::get('project');
      $managemodule->module_name = Input::get("module_name");
      $managemodule['update_by'] = Auth::User()->id;
      $managemodule['create_by'] = Auth::User()->id;
      $managemodule['created_at'] = date('Y-m-d H:i:s');
      $managemodule['updated_at'] = date('Y-m-d H:i:s');
      $managemodule->save();
      return redirect()->action('ModuletestController@index');

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
                'module_name' => 'required|string|max:255',
        ]);
    }

    public function show($id)
    {
       //
    }

    public function edit($id){
      $user = Auth::user()->id;
      $managemodule = New Module();
      $managemodule = Module::where('id', $id)->first();
      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->pluck('project_name', 'id')->toArray();
      $data = array('project_list' => $projectlist,
                    'managemodule' => $managemodule
      );
      return view('module_form', $data);
    }

    public function update(Request $request, $id){
      $inputs = $request->All();

      $managemodule['project_id'] = $inputs['project'];
      $managemodule['module_name'] = $inputs['module_name'];
      $managemodule['update_by'] = Auth::User()->id;
      Module::where('id', $id)->limit(1)->update($managemodule);
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);
    }



    public function destroyModule($id){
      $manage_function_check = Managefunction::
                              where('managefunctions.module_id', $id)
                              ->where('managefunctions.status_code','PP-FC')
                              ->get();
      $manage_function_check2 = Managefunction::select('id')
                              ->where('managefunctions.module_id', $id)
                              ->get()->toArray();
      // dd($manage_function_check2);
      $count = count($manage_function_check);
      if($count>0){
        $return['status'] = 0;
        $return['message'] = 'ไม่สามารถลบรายการนี้ได้ เนื่องจาก Function ใน Plan นี้ผ่านการทดสอบแล้ว';
      }else{
        Module::destroy($id);
        if(sizeof($manage_function_check2)>0){
          foreach ($manage_function_check2 as $key => $value) {
            $test = Managecase::select('id')
                    ->where('function_id', $value['id'])
                    ->get()->toArray();
            Managefunction::destroy($value['id']);
          }
        }
        if((sizeof($manage_function_check2)>0) && $test){
          foreach ($test as $key => $value) {
            Managecase::destroy($value['id']);
          }
        }

        $return['status'] = 1;
        $return['message'] = 'ลบรายการสำเร็จ';
      }
        return response($return, 200);
    }

    // SELECT managemodules.id, managemodules.module_name, manageprojects.id, users.name
    // FROM `manageprojects`
    // RIGHT JOIN managemodules ON manageprojects.id = managemodules.project_id
    // JOIN users ON users.id = managemodules.create_by
    // WHERE manageprojects.id

    public function getNotifyDetailData(Request $request)
    {
        $user = Auth::user()->id;
        $notifies = Manageproject::select(
          'managemodules.update_by',
          'managemodules.id as module_id',
          'managemodules.module_name as name',
          'manageprojects.project_name as p_name',
          'userprojects.project_id as project_id',
          'users.name as user_update',
            DB::raw('DATE_FORMAT(DATE_ADD(managemodules.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as managemodules_update'))
            ->rightjoin('managemodules', 'manageprojects.id', '=', 'managemodules.project_id')
            ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
            ->join('users', 'users.id', '=', 'managemodules.update_by')
            ->distinct()->get(['name'])->toArray();
          $notifies2 = Userproject::select('users.id as ui', 'manageprojects.id', 'manageprojects.project_name', 'users.name')
                                      ->join('manageprojects', 'manageprojects.id','=','userprojects.project_id')
                                      ->join('users','users.id', '=', 'userprojects.user_id')
                                      ->whereIn('users.id',[$user])->get()->toArray();
            $a=array();
            if(!empty($notifies) && !empty($notifies2)){
              foreach ($notifies as $key => $value) {
                if (($value['project_id'] == $user) || ($notifies2[0]['id'] == $value['project_id'])) {
                  array_push($a,$value);
                }
              }
            }else{
              // dd("xxxx");
            }
            if(sizeof($a)>0){
              foreach ($notifies as $key => $value) {
                if ($value['project_id'] != $user && $notifies2[0]['id'] != $value['project_id']){
                  if($a[0]['project_id'] == $value['project_id']){
                    array_push($a,$value);
                  }
                }
              }
            }
            // dd($notifies2[0]);
            // dd($a);


            // dd($a);

            // dd($a);
            // if(){
            //
            // }
        return Datatables::of($a)->make(true);
    }

}
