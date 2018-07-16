<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Managecase;
use App\Manageproject;
use App\Manageplan;
use App\Status;
use App\Managemodule;
use App\Managefunction;
use App\Userproject;
use App\Module;
use App\User;
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

class ManagecaseController extends Controller
{
    public function index(){
      $user = Auth::user()->id;
      $testcase = new Managecase;
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->get();
      $modulelist = Module::select('*')->get();
      $functionlist = Managefunction::pluck('function_name', 'id')->toArray();
      $planlist = Manageplan::select('*')->get();
      // foreach ($planlist as $key => $value) {
      //   echo '<pre>';
      //   echo $value;
      // }die;
      // $statuslist = Status::pluck('status_name', 'id')->toArray();
      // $testerlist = User::pluck('name', 'id')->toArray();
      $statuslist = Status::select('status_name','code')
          ->where('module_work', 'Test case')
          ->where('type', 'Minor')
          ->get();
          // dd($statuslist);
      $testerlist = Userproject::select('users.name', 'users.id', 'userprojects.project_id', 'userprojects.role_id')
                    ->join('manageprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                    ->join('users', 'users.id', '=', 'userprojects.user_id')
                    ->where('userprojects.project_id','=', $user)
                    ->where('userprojects.role_id', '=', '4')
                    ->get()->toArray();
        // echo '<pre>';
        // foreach ($testerlist as $key => $value) {
        //   print_r($value);
        // }die;
                    // dd($testcase);
      // dd($testerlist);0
      $data = array('project_list' => $projectlist,
                    'module_list' => $modulelist,
                    'function_list' => $functionlist,
                    'plan_list' => $planlist,
                    'status_list'=> $statuslist,
                    'tester_list' => $testerlist,
                    'testcase' => $testcase,
      );
      return view('testcasemanage',$data);
    }


    public function create(){

    }

    public function store(Request $request){
      $testcase = new Managecase;
      $testcase->project_id = Input::get('project');
      // $function->module_id = Input::get('module');
      $testcase->module_id = Input::get("module");
      $testcase->function_id = Input::get("function_name");
      $testcase->plan_id = Input::get("plan_name");
      $testcase->case_name = Input::get("casename");
      $testcase->case_code = Input::get("code_no");
      $testcase->status_code = Input::get("status");
      $testcase->user_id = Input::get("name");
      $testcase->save();
      $check_func = Managefunction::select('managefunctions.id','managefunctions.function_name','managefunctions.status_code')
                                    ->join('managecases', 'managecases.function_id', '=', 'managefunctions.id')
                                    ->where('managecases.function_id', '=', $testcase->function_id)
                                    ->distinct()
                                    ->get(['id'])->toArray();
      $check = Managecase::where('function_id', $testcase->function_id)->count();
      $check_status = Managecase::select('status_code')
                                  ->where('function_id', '=', $testcase->function_id)
                                  ->get()->toArray();

      if($check>0){
        $i=0;
        $n=0;
        foreach ($check_status as $key => $value) {
          if($testcase->status_code == 'PP-TP'){
            if($value['status_code'] == 'PP-TP'){
              $i++;
            }
          }
        }
        foreach ($check_status as $key => $value) {
          if($value['status_code'] != 'PP-TP'){
            $n++;
          }
        }
      }

      $pro = Manageproject::select('id', 'status_code')
                            ->where('id','=',$testcase->project_id)
                            ->get()->toArray();
      // dd($check_status[0]['status_code']);
      if($check>0){
        if($i == sizeof($check_status)){
          Managefunction::where('id', $testcase->function_id)->update(['status_code'=>'PP-FC']);
          // Manageproject::where('id', $testcase['project_id'])->update(['status_code'=>'PC']);
          $check_pro = Managefunction::select('status_code', 'project_id')
                                        ->where('project_id', '=', $testcase->project_id)
                                        ->get()->toArray();
          $j=0;
          foreach ($check_pro as $key => $value) {
            if($value['status_code'] == 'PP-FC'){
              $j++;
            }
          }
          if($j == sizeof($check_pro)){
            if($pro[0]['status_code'] != 'PC'){
              $return['status'] = 1;
              $return['message'] = 'บันทึกสำเร็จ Function และ Project ได้เปลี่ยนสถานะเป็น Complete';
            }else {
              $return['status'] = 1;
              $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Complete';
            }
            Manageproject::where('id', $testcase->project_id)->update(['status_code'=>'PC']);
          }else{
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Complete';
          }
        }else{
          Manageproject::where('id', $testcase->project_id)->update(['status_code'=>'PP']);
          Managefunction::where('id',$testcase->function_id)->update(['status_code'=>'PP-FP']);
          $check_pro = Managefunction::select('status_code', 'project_id')
                                        ->where('project_id', '=', $testcase->project_id)
                                        ->get()->toArray();
          if($pro[0]['status_code'] == 'PC'){
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Function และ Project ได้เปลี่ยนสถานะเป็น Process';
          }else {
            if($testcase['status_code'] == 'PP-TP'){
              $return['status'] = 1;
              $return['message'] = 'บันทึกสำเร็จ';
            }else{
              if($check_pro[0]['status_code'] == 'PP-FP'){
                $return['status'] = 1;
                $return['message'] = 'บันทึกสำเร็จ';
              }else{
                $return['status'] = 1;
                $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Process';
              }
            }
          }
        }
      }else{
        if ($testcase->status_code == 'PP-TP') {
          if($check_func[0]['status_code'] != 'PP-FC'){
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Complete';
          }else{
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ';
          }
          Managefunction::where('id',$testcase->function_id)->update(['status_code'=>'PP-FC']);
        }else{
          if($pro[0]['status_code'] == 'PC'){
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Project ได้เปลี่ยนสถานะเป็น Process';
            Manageproject::where('id', $testcase->project_id)->update(['status_code'=>'PP']);
          }
        }
      }
      // $testcase['updated_at'] = date('Y-m-d H:i:s');
      // dd($function);
      // return redirect()->action('ManagecaseController@index',$return);
      return response($return, 200);
    }

    public function show($id)
    {
       //
    }

    public function edit($id){
      // dd($id);
      $user = Auth::user()->id;
      $testcase = new Managecase;
      $testcase = Managecase::where('id', $id)->first();
      $statuslist = Status::select('status_name','code','status.id')
          ->where('module_work', 'Test case')
          ->where('type', 'Minor')
          ->pluck('status_name','code')->toArray();

      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->pluck('project_name', 'id')->toArray();
      $modulelist = Manageplan::select('managemodules.id','managemodules.module_name')
                      ->join('managemodules', 'manageplans.module_id', '=', 'managemodules.id')
                      ->where('manageplans.id','=', $testcase->plan_id)->get();
      $functionlist = Managefunction::select('managefunctions.id','managefunctions.function_name')
                      ->join('managemodules','managemodules.id','=','managefunctions.module_id')
                      ->where('managefunctions.module_id','=', $testcase->module_id )->get();
      $planlist = Manageplan::select('manageplans.id', 'manageplans.plan_name')
                      ->join('manageprojects','manageprojects.id', '=', 'manageplans.project_id')
                      ->where('manageprojects.id', '=', $testcase->project_id)->get();
      // $statuslist = Status::pluck('status_name', 'id')->toArray();
      // $testerlist = User::pluck('name', 'id')->toArray();
      $testerlist = Managecase::select('users.id','users.name')
                                ->join('users','managecases.user_id','users.id')
                                // ->where('managecases.id',$id)
                                ->get()->toArray();
      // dd($testerlist);
      $data = array('project_list' => $projectlist,
                    'module_list' => $modulelist,
                    'function_list' => $functionlist,
                    'tester_list' => $testerlist,
                    'plan_list' => $planlist,
                    'status_list'=> $statuslist,
                    'testcase' => $testcase,
      );
      // dd($testerlist);
      return view('testcase_form',$data);
    }

    public function update(Request $request, $id){
      $inputs = $request->All();

      // $function->module_id = Input::get('module');
      $testcase['project_id'] = $inputs['project_name'];
      $testcase['module_id'] = $inputs['module_name'];
      $testcase['function_id'] = $inputs['function_name'];
      $testcase['plan_id'] = $inputs['plan_name'];
      $testcase['case_code'] = $inputs['case_code'];
      $testcase['case_name'] = $inputs['case_name'];
      $testcase['status_code'] = $inputs['status_name'];
      $testcase['user_id'] = $inputs['name'];

      Managecase::where('id', $id)->limit(1)->update($testcase);
      $check = Managecase::where('function_id', $testcase['function_id'])->count();
      $check_status = Managecase::select('status_code')
                                  ->where('function_id', '=', $testcase['function_id'])
                                  ->get()->toArray();

      $i=0;
      foreach ($check_status as $key => $value) {
        if($testcase['status_code'] == 'PP-TP'){
          if($value['status_code'] == 'PP-TP'){
            $i++;
          }
        }
      }

      $n=0;
      foreach ($check_status as $key => $value) {
        if($value['status_code'] == 'PP-TT'){
          $n++;
        }
      }
      $pro = Manageproject::select('id', 'status_code')
                            ->where('id','=',$testcase['project_id'])
                            ->get()->toArray();
      // dd($check_status[0]['status_code']);
      if($i == sizeof($check_status)){
        Managefunction::where('id', $testcase['function_id'])->update(['status_code'=>'PP-FC']);
        // Manageproject::where('id', $testcase['project_id'])->update(['status_code'=>'PC']);
        $check_pro = Managefunction::select('status_code', 'project_id')
                                      ->where('project_id', '=', $testcase['project_id'])
                                      ->get()->toArray();
        $j=0;
        foreach ($check_pro as $key => $value) {
          if($value['status_code'] == 'PP-FC'){
            $j++;
          }
        }
        if($j == sizeof($check_pro)){
          if($pro[0]['status_code'] != 'PC'){
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Function และ Project ได้เปลี่ยนสถานะเป็น Complete';
          }else {
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Complete';
          }
          Manageproject::where('id', $testcase['project_id'])->update(['status_code'=>'PC']);
        }else{
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Complete';
        }
      }else{
        Manageproject::where('id', $testcase['project_id'])->update(['status_code'=>'PP']);
        Managefunction::where('id',$testcase['function_id'])->update(['status_code'=>'PP-FP']);
        if($pro[0]['status_code'] == 'PC'){
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ Function และ Project ได้เปลี่ยนสถานะเป็น Process';
        }else {
          if($testcase['status_code'] == 'PP-TP'){
            $return['status'] = 1;
            $return['message'] = 'บันทึกสำเร็จ';
          }else {
            if($n == sizeof($check_status)){
              $return['status'] = 1;
              $return['message'] = 'บันทึกสำเร็จ Function ได้เปลี่ยนสถานะเป็น Process';
            }else{
              $return['status'] = 1;
              $return['message'] = 'บันทึกสำเร็จ';
            }
          }
        }
      }
      return response($return, 200);
    }

    public function destroycase($id){
      $manage_check = Managecase::select('function_id','project_id')
                                  ->where('managecases.id', $id)
                                  ->where('managecases.status_code','PP-TP')
                                  ->get()->toArray();
      $manage_check2 = Managecase::select('function_id','project_id')
                                  ->where('managecases.id', $id)
                                  ->get()->toArray();
      $count = count($manage_check);
      if($count >=1){
        $return['status'] = 0;
        $return['message'] = 'ไม่สามารถลบรายการนี้ได้ เนื่องจากสถานะการทดสอบผ่านแล้ว';
      }else{
      Managecase::destroy($id);
      $check_status_func = Managecase::select('status_code')
                                        ->where('function_id', '=', $manage_check2[0]['function_id'])
                                        ->get()->toArray();
        $i=0;
        foreach ($check_status_func as $key => $value) {
          if($value['status_code'] == 'PP-TP'){
            $i++;
          }
        }//check test case of function when status pp-tp num of count

        if(($i == sizeof($check_status_func))&&($i !=0)){ //check
          Managefunction::where('id',$manage_check2[0]['function_id'])->update(['status_code'=>'PP-FC']);
          $check_status_pro = Managefunction::select('status_code')
                                              ->where('project_id', '=', $manage_check2[0]['project_id'])
                                              ->get()->toArray();
          $j=0;
          foreach ($check_status_pro as $key => $value) {
            if($value['status_code'] == 'PP-FC'){
              $j++;
            }
          }
          if($j == sizeof($check_status_pro)){
            Manageproject::where('id', $manage_check2[0]['project_id'])->update(['status_code'=>'PC']);
            $return['status'] = 1;
            $return['message'] = 'ลบสำเร็จแล้ว Function และ Project ได้เปลี่ยนสถานะเป็น Complete';
          }else {
            Managefunction::where('id',$manage_check2[0]['function_id'])->update(['status_code'=>'PP-FC']);
            $return['status'] = 1;
            $return['message'] = 'ลบสำเร็จแล้ว Function ได้เปลี่ยนสถานะเป็น Complete';
          }
        }else if($i == 0){
          $return['status'] = 1;
          $return['message'] = 'ลบสำเร็จแล้ว';
        }else {
          $return['status'] = 1;
          $return['message'] = 'ลบสำเร็จแล้ว';
        }
      }
      return response($return, 200);
    }

    public function getTestCaseDatatables(Request $request)
    {
      $user = Auth::user()->id;
      $testcase = Managecase::select(
      'managecases.id as id',
      'managecases.case_name as case_name',
      'managecases.case_code as case_no',
      'manageplans.plan_name as plan_n',
      'managefunctions.function_name as name_funct',
      'userprojects.project_id as project_id',
      'managemodules.module_name as name_module',
      'manageprojects.project_name as pro_name',
      'status.status_name as case_status',
      'users.name as tester',
        DB::raw('DATE_FORMAT(DATE_ADD(Managecases.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as case_update'))
        ->leftjoin('status', 'managecases.status_code', '=', 'status.code')
        ->leftjoin('users','managecases.user_id', '=', 'users.id')
        ->leftjoin('manageplans', 'managecases.plan_id', '=', 'manageplans.id')
        ->leftjoin('managefunctions', 'managecases.function_id', '=', 'managefunctions.id')
        ->leftjoin('manageprojects', 'managecases.project_id', '=', 'manageprojects.id')
        ->leftjoin('managemodules', 'managecases.module_id', '=', 'managemodules.id')
        ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
        // ->Where('users.id','=',$user)
        // ->WHERE('tester.id')
        ->distinct()
        ->get(['case_name'])->toArray();
        // dd($testcase);

        $testcase2 = Userproject::select('users.id as ui', 'manageprojects.id', 'manageprojects.project_name', 'users.name')
                                    ->join('manageprojects', 'manageprojects.id','=','userprojects.project_id')
                                    ->join('users','users.id', '=', 'userprojects.user_id')
                                    ->whereIn('users.id',[$user])->get()->toArray();
          $a=array();
          if(!empty($testcase) && !empty($testcase2)){
            foreach ($testcase as $key => $value) {
              if (($value['project_id'] == $user) || ($testcase2[0]['id'] == $value['project_id'])) {
                array_push($a,$value);
              }
            }
          }else{
            // dd("xxxx");
          }
          if(sizeof($a)>0){
            foreach ($testcase as $key => $value) {
              if ($value['project_id'] != $user && $testcase2[0]['id'] != $value['project_id']){
                if($a[0]['project_id'] == $value['project_id']){
                  array_push($a,$value);
                }
              }
            }
          }
        return Datatables::of($a)->make(true);
    }

    public function getPlanFromProject($id){
      $project = Userproject::select('users.name', 'users.id', 'userprojects.project_id', 'userprojects.role_id')
                    ->join('manageprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                    ->join('users', 'users.id', '=', 'userprojects.user_id')
                    ->where('userprojects.project_id','=', $id)
                    ->where('userprojects.role_id', '=', '4')
                    ->get()->toArray();
                    // dd($planlist);
      $planlist = Manageplan::select('id','plan_name')
                              ->where('project_id', $id)
                              ->get()->toArray();
      $data['project'] = $project;
      $data['planlist'] = $planlist;

      // dd($data);

        return response($data, 200);
    }
    public function getModuleFromPlan($id){
      $modulelist = Manageplan::select('*')
                    ->join('managemodules', 'manageplans.module_id', '=', 'managemodules.id')
                    ->where('manageplans.id','=', $id )
            ->pluck('module_name', 'id')->toArray();

        return response($modulelist, 200);
    }
    public function getFunctionFromModule($id){
      $functionlist = Managefunction::where('module_id', $id)
            ->pluck('function_name', 'id')->toArray();
        return response($functionlist, 200);
    }

}
