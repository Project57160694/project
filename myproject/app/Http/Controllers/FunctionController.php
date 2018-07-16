<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manageproject;
use App\Manageplan;
use App\Http\Managecase;
use App\Module;
use App\Status;
use App\Detailuser;
use App\Managefunction;
use App\Userproject;
use App\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use Carbon\Carbon;


class FunctionController extends Controller
{
    public function index(){
      $user = Auth::user()->id;
      $function = new Managefunction;
      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->get();
      $modellist = Module::pluck('module_name', 'id')->toArray();

      $data = array('project_list' => $projectlist,
                    'module_list' => $modellist,
                    'function' => $function
      );
      return view('functionmanage', $data);
    }

    public function create()
    {
      // $modellist = Module::leftjoin('managefunctions','managemodules.id','=','managefunctions.module_id')
      //   ->get();
      // $data = array('module_namelist' => $modellist);
      // dd($modellist);
      // return view('functionmanage', $data);
    }

    public function store(Request $request)
    {
      $function = new Managefunction;
      $function->project_id = Input::get('project');
      // $function->module_id = Input::get('module');
      $function->function_name = Input::get("function_name");
      $function->short_name = Input::get("short_name");
      $function->module_id = Input::get("module");
      $function['status_code'] = 'PP-FP';
      $function['update_by'] = Auth::User()->id;
      $function['updated_at'] = date('Y-m-d H:i:s');
      $function['created_at'] = date('Y-m-d H:i:s');
      $function->save();
      $pro = Manageproject::select('id', 'status_code')
                            ->where('id','=',$function->project_id)
                            ->get()->toArray();
      $check = Managefunction::select('status_code','project_id')
                                ->where('project_id', '=', $function->project_id)
                                ->get()->toArray();
      $i=0;
      foreach ($check as $key => $value) {
        if($value['status_code'] == 'PP-FC'){
          $i++;
        }
      }
      if($i == sizeof($check)){
        if($pro[0]['status_code'] != 'PC'){
          Manageproject::where('id', $function->project_id)->update(['status_code'=>'PP']);
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ';
        }else{
          Manageproject::where('id', $function->project_id)->update(['status_code'=>'PC']);
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ Project ได้เปลี่ยนสถานะเป็น Complete';
        }
      }else{
        if($pro[0]['status_code'] == 'PC'){
          Manageproject::where('id', $function->project_id)->update(['status_code'=>'PP']);
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ Project ได้เปลี่ยนสถานะเป็น Process';
        }else{
          Manageproject::where('id', $function->project_id)->update(['status_code'=>'PP']);
          $return['status'] = 1;
          $return['message'] = 'บันทึกสำเร็จ';
        }
      }
      // dd($function);
      return response($return, 200);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'project_name' => 'required|string|max:255',
        ]);
    }


    public function show($id)
    {

    }

    public function edit($id){
      $user = Auth::user()->id;
      $function = New Managefunction();
      $function = Managefunction::where('id', $id)->first();
      // $projectlist = Manageproject::pluck('project_name','id')->toArray();
      $projectlist = Manageproject::select('project_name','manageprojects.id', 'userprojects.project_id as project_id')
                      ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->join('users', 'users.id', '=', 'userprojects.user_id')
                      ->where('users.id','=', $user)
                      ->pluck('project_name', 'id')->toArray();
      $modellist = Module::select('managemodules.id','managemodules.module_name')
                      ->join('manageprojects', 'manageprojects.id', '=', 'managemodules.project_id')
                      ->where('manageprojects.id','=', $function->project_id )->get();
      // echo '<pre>';
      // foreach ($modellist as $key => $value) {
      //   echo $value['id'];
      // }die;
      $data = array('project_list' => $projectlist,
                    'module_list' => $modellist,
                    'function' => $function
      );
      return view('function_form',$data);
    }

    public function update(Request $request, $id){
      $inputs = $request->All();
      //dd($inputs);
      $function['project_id'] = $inputs['project'];
      $function['function_name'] = $inputs['function_name'];
      $function['short_name'] = $inputs['short_name'];
      $function['module_id'] = $inputs['module'];
      Managefunction::where('id', $id)->limit(1)->update($function);
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);
    }

    public function destroymanagefunction($id){
      $manage_function_check = Managefunction::
                              where('managefunctions.id', $id)
                              ->where('managefunctions.status_code','PP-FC')
                              ->get();
      $pro_id = Managefunction::select('project_id')
              ->where('managefunctions.id','=', $id)
              ->get()->toArray();
      $test = Managecase::select('id','status_code')
                          ->where('function_id', $id)
                          ->get()->toArray();
      // dd($test);
      $count = count($manage_function_check);
      if($count >=1){
        $return['status'] = 0;
        $return['message'] = 'ไม่สามารถลบรายการนี้ได้ เนื่องจาก Function นี้ผ่านการทดสอบแล้ว';
      }
      else{
        Managefunction::destroy($id);
        if(sizeof($test)>0){
          foreach ($test as $key => $value) {
            Managecase::destroy($value['id']);
          }
        }
        $pro = Manageproject::select('id', 'status_code')
            ->where('id','=', $pro_id[0]['project_id'])
            ->get()->toArray();
        $check = Managefunction::select('status_code','project_id')
              ->where('project_id', '=', $pro_id[0]['project_id'])
              ->get()->toArray();
        $i=0;
        foreach ($check as $key => $value) {
          if($value['status_code'] == 'PP-FC'){
            $i++;
          }
        }
        if($i == sizeof($check)){
          if($pro[0]['status_code'] == 'PP'){
            // Manageproject::where('id', $pro_id[0]['project_id'])->update(['status_code'=>'PC']);
            $return['status'] = 1;
            $return['message'] = 'ลบรายการสำเร็จ Project ได้เปลี่ยนสถานะเป็น Complete';
          }else{
            $return['status'] = 1;
            $return['message'] = 'ลบรายการสำเร็จ';
          }
        }else{
          Manageproject::where('id', $pro_id[0]['project_id'])->update(['status_code'=>'PP']);
          $return['status'] = 1;
          $return['message'] = 'ลบรายการสำเร็จ';
        }
        // Managecase::where('function_id', '=', $id)->delete();
      }
      return response($return, 200);
    }

    public function getFunctiondata(Request $request)
    {
        $user = Auth::user()->id;
        $functionmanage = Managefunction::select(
          'managefunctions.id as funct_id',
          'managefunctions.function_name as ft_name',
          'managefunctions.short_name as sh_name',
          'managemodules.module_name as module_name',
          'manageprojects.project_name as pj_name',
          'manageprojects.id as project_id',
          'status.status_name as st_name',
            DB::raw('DATE_FORMAT(DATE_ADD(managefunctions.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as ft_update'))
            ->leftjoin('status', 'managefunctions.status_code', '=', 'status.code')
            ->leftjoin('manageprojects', 'managefunctions.project_id', '=', 'manageprojects.id')
            ->leftjoin('managemodules', 'managefunctions.module_id', '=', 'managemodules.id')
            ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
            ->join('users', 'users.id', '=', 'managefunctions.update_by')
            ->distinct()
            ->get('ft_name')->toArray();
            $functionmanage2 = Userproject::select('users.id as ui', 'manageprojects.id', 'manageprojects.project_name', 'users.name')
                                        ->join('manageprojects', 'manageprojects.id','=','userprojects.project_id')
                                        ->join('users','users.id', '=', 'userprojects.user_id')
                                        ->whereIn('users.id',[$user])->get()->toArray();
            $check = Managecase::select('managefunctions.id', 'managefunctions.function_name','managecases.status_code')
                                  ->join('managefunctions','managefunctions.id','=','managecases.function_id')
                                  ->get()->toArray();


            $a=array();
            if(!empty($functionmanage) && !empty($functionmanage2)){
              foreach ($functionmanage as $key => $value) {
                if (($value['project_id'] == $user) || ($functionmanage2[0]['id'] == $value['project_id'])) {
                  array_push($a,$value);
                }
              }
            }else{
              // dd("xxxx");
            }
            if(sizeof($a)>0){
              foreach ($functionmanage as $key => $value) {
                if ($value['project_id'] != $user && $functionmanage2[0]['id'] != $value['project_id']){
                  if($a[0]['project_id'] == $value['project_id']){
                    array_push($a,$value);
                  }
                }
              }
            }
            return Datatables::of($a)->make(true);
    }

    public function getModuleFromProject($id){
      $module_list = Module::where('project_id', $id)
            ->pluck('module_name', 'id')->toArray();

        return response($module_list, 200);
    }

}
