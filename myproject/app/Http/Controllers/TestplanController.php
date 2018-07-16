<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manageproject;
use App\Manageplan;
use App\Status;
use App\Managemodule;
use App\Managefunction;
use App\Userproject;
use App\Module;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use Carbon\Carbon;


/*Project name : Softtest
Floder : app/Http/Controller/Admins
File : TestplanController.php
Name : Narumol kuntonkidja 57160694
Date : 28/4/2018
Description : ใชกำหนด Controller ของมอดูลจัดการวางแผนการทดสอบ เพื่อสั่งการให้ laravel ประมวลผล*/

class TestplanController extends Controller
{
    public function index(){
      //return view('admin.manageproject.index2');
      $user = Auth::user()->id;
      $planmanage = new Manageplan;
      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->get();
      $modellist = Module::pluck('module_name', 'id')->toArray();
      $functionlist = Managefunction::pluck('function_name', 'id')->toArray();


      $data = array('project_list' => $projectlist,
                    'module_list' => $modellist,
                    'function_list' => $functionlist,
                    'planmanage' => $planmanage,
      );
      return view('testplan', $data);
    }

    public function create(){

    }

    public function store(Request $request)
    {
      $planmanage = new Manageplan;
      $planmanage->project_id = Input::get('project');
      // $function->module_id = Input::get('module');
      $planmanage->module_id = Input::get("module");
      $planmanage->function_id = Input::get("function_name");
      $planmanage->plan_name = Input::get("plan_name");
      $planmanage['update_by'] = Auth::User()->id;
      $planmanage['updated_at'] = date('Y-m-d H:i:s');
      $planmanage['created_at'] = date('Y-m-d H:i:s');
      $planmanage->save();

      // dd($function);
      return redirect()->action('TestplanController@index');


    }

    public function show($id)
    {
       //
    }

    public function edit($id){
      $user = Auth::user()->id;
      $planmanage = new Manageplan;
      $planmanage = Manageplan::where('id', $id)->first();
      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->pluck('project_name', 'id')->toArray();
      $modellist = Module::select('managemodules.id','managemodules.module_name')
                      ->join('manageprojects', 'manageprojects.id', '=', 'managemodules.project_id')
                      ->where('manageprojects.id','=', $planmanage->project_id )->get();
      $functionlist = Managefunction::select('managefunctions.id','managefunctions.function_name')
                      ->join('managemodules','managemodules.id','=','managefunctions.module_id')
                      ->where('managefunctions.module_id','=', $planmanage->module_id )->get();
                      // dd($functionlist);
      $data = array('project_list' => $projectlist,
                    'module_list' => $modellist,
                    'function_list' => $functionlist,
                    'planmanage' => $planmanage,
      );
      return view('form_plan',$data);
    }

    public function update(Request $request, $id){
      $inputs = $request->All();

      // $function->module_id = Input::get('module');
      $planmanage['project_id'] = $inputs['project'];
      $planmanage['module_id'] = $inputs['module'];
      $planmanage['function_id'] = $inputs['function_name'];
      $planmanage['plan_name']= $inputs['plan_name'];
      Manageplan::where('id', $id)->limit(1)->update($planmanage);
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);
    }

    public function destroyplan($id){
      $manage_plan_check = Manageplan::select('module_id','function_id')
                              ->where('id', $id)
                              ->get()->toArray();
      $manage_func_check = Managefunction::select('status_code')
                                            ->where('id',$manage_plan_check[0]['function_id'])
                                            ->where('status_code','PP-FC')
                                            ->get()->toArray();
      if(sizeof($manage_func_check)>0){
        $return['status'] = 0;
        $return['message'] = 'ไม่สามารถลบรายการนี้ได้ เนื่องจาก Function ใน Plan นี้ผ่านการทดสอบแล้ว';
      }else{
        Manageplan::destroy($id);
        $return['status'] = 1;
        $return['message'] = 'ลบรายการสำเร็จ';
      }

      return response($return, 200);
    }

    public function getTestPlanDatatables(Request $request)
    {
      $user = Auth::user()->id;
      $planmanage = Manageplan::select(
      'manageplans.id as plan_id',
      'manageplans.plan_name as plan_n',
      'managefunctions.function_name as name_funct',
      'managemodules.module_name as name_module',
      'manageprojects.project_name as pro_name',
      'userprojects.project_id as project_id',
        DB::raw('DATE_FORMAT(DATE_ADD(manageplans.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as plans_update'))
        ->leftjoin('managefunctions', 'manageplans.function_id', '=', 'managefunctions.id')
        ->leftjoin('manageprojects', 'manageplans.project_id', '=', 'manageprojects.id')
        ->leftjoin('managemodules', 'manageplans.module_id', '=', 'managemodules.id')
        ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
        ->join('users', 'users.id', '=', 'manageplans.update_by')
        ->distinct()
        ->get(['name_module'])->toArray();
        $planmanage2 = Userproject::select('users.id as ui', 'manageprojects.id', 'manageprojects.project_name', 'users.name')
                                    ->join('manageprojects', 'manageprojects.id','=','userprojects.project_id')
                                    ->join('users','users.id', '=', 'userprojects.user_id')
                                    ->whereIn('users.id',[$user])->get()->toArray();
          $a=array();
          if(!empty($planmanage) && !empty($planmanage2)){
            foreach ($planmanage as $key => $value) {
              if (($value['project_id'] == $user) || ($planmanage2[0]['id'] == $value['project_id'])) {
                array_push($a,$value);
              }
            }
          }else{
            // dd("xxxx");
          }
          if(sizeof($a)>0){
            foreach ($planmanage as $key => $value) {
              if ($value['project_id'] != $user && $planmanage2[0]['id'] != $value['project_id']){
                if($a[0]['project_id'] == $value['project_id']){
                  array_push($a,$value);
                }
              }
            }
          }

        // dd($planmanage);
        // dd($functionmanage);
        return Datatables::of($a)->make(true);
    }
    public function getModuleFromProject($id){
      $modulelist = Module::where('project_id', $id)
            ->pluck('module_name', 'id')->toArray();

        return response($modulelist, 200);
    }
    public function getFunctionFromModule($id){
      $functionlist = Managefunction::where('module_id', $id)
            ->pluck('function_name', 'id')->toArray();
        return response($functionlist, 200);
    }
}
