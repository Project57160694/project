<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manageproject;
use App\Manageplan;
use App\Status;
use App\Detailuser;
use App\Managefunction;
use App\Module;
use App\Http\Managecase;
use App\Userproject;
use App\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use Carbon\Carbon;


class ManageprojectController extends Controller
{
    public function index(){

      return view('manageproject');
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
      $manageproject = new Manageproject;
      $manageproject->project_name = Input::get("project_name");
      $manageproject['status_code'] = 'PP';
      $manageproject['created_by'] = Auth::User()->id;
      $manageproject['updated_at'] = date('Y-m-d H:i:s');
      $manageproject['created_at'] = date('Y-m-d H:i:s');
      $manageproject->save();

        $userproject = new Userproject;
        $userproject['user_id'] = Auth::User()->id;
        $userproject['role_id'] = '1';
        $userproject->project_id = $manageproject->id;
        $userproject['update_by'] = Auth::User()->id;
        $userproject['updated_at'] = date('Y-m-d H:i:s');
        $userproject['created_at'] = date('Y-m-d H:i:s');
        $userproject->save();
      return redirect()->action('ManageprojectController@index');

    }

    protected function validator(array $data)
    {

    }


    public function show($id)
    {

    }

    public function edit($id_project){
      $manageproject = New Manageproject();
      $manageproject = Manageproject::where('id', $id_project)->first();
      return view('manageproject_form', array('manageprojects' => $manageproject));

    }

    public function update(Request $request, $id_project){
      $inputs = $request->All();
      $manage['project_name'] = $inputs['project_name'];
      Manageproject::where('id', $id_project)->limit(1)->update($manage);
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);

    }

    public function destroymanage($id_project)
    {
      $manage_function_check = Managefunction::
                              where('managefunctions.module_id', $id_project)
                              ->where('managefunctions.status_code','PP-FC')
                              ->get();
      $count_function = count($manage_function_check);
      $manage_project_check= Manageproject::
                              where('manageprojects.id', $id_project)
                              ->where('manageprojects.status_code','PC')
                              ->get();
      $count_project = count($manage_project_check);
      if($count_function >=1 || $count_project >=1 ){
        $return['status'] = 0;
        $return['message'] = 'ไม่สามารถลบรายการนี้ได้ เนื่องจากโครงการนี้เสร็จสิ้นการทดสอบ หรือมีฟังก์ชันที่ทำการทดสอบเสร็จสิ้นแล้ว';
      }
      else{
        // dd($id_project);
        $nodul = Module::select('id')
                      // ->join('managemodules', 'manageprojects.id', '=', 'managemodules.project_id')
                      ->where('project_id', '=', $id_project)
                      ->get()->toArray();
        //               ->destroy('managemodules.project_id', '=', $id_project);
        $func = Managefunction::select('id')
                      // ->join('managefunctions', 'manageprojects.id', '=', 'managefunctions.project_id')
                      ->where('project_id', '=', $id_project)
                      ->get()->toArray();
        //               ->destroy('managefunctions.project_id', '=', $id_project);
        $plan = Manageplan::select('id')
                      // ->join('manageplan', 'manageprojects.id', '=', 'manageplan.project_id')
                      ->where('project_id', '=', $id_project)
                      ->get()->toArray();
        //               ->destroy('manageplan.project_id', '=', $id_project);
        $case = Managecase::select('id')
                      // ->join('managecases', 'manageprojects.id', '=', 'managecases.project_id')
                      ->where('project_id', '=', $id_project)
                      ->get()->toArray();
        //               ->destroy('managecases.project_id', '=', $id_project);
        $user = Userproject::select('id')
                      // ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
                      ->where('project_id', '=', $id_project)
                      ->get()->toArray();
        //               ->destroy('userprojects.project_id', '=', $id_project);
        // dd($plan);
        if(sizeof($nodul)>0){
          foreach ($nodul as $key => $value) {
            Module::destroy($value['id']);
          }
        }
        if(sizeof($func)>0){
          foreach ($func as $key => $value) {
            Managefunction::destroy($value['id']);
          }
        }
        if(sizeof($plan)>0){
          foreach ($plan as $key => $value) {
            Manageplan::destroy($value['id']);
          }
        }
        if(sizeof($case)>0){
          foreach ($case as $key => $value) {
            Managecase::destroy($value['id']);
          }
        }
        if(sizeof($user)>0){
          foreach ($user as $key => $value) {
            Userproject::destroy($value['id']);
          }
        }
        Manageproject::destroy($id_project);
          $return['status'] = 1;
          $return['message'] = 'ลบโครงการสำเร็จ';
      }
      return response($return, 200);
    }

    public function getManageproject(Request $request)
    {
        $user = Auth::user()->id;
        $manage_project = Manageproject::select(
        'manageprojects.id as id_project',
        'manageprojects.project_name as name',
        'status.status_name as status_name',
        'userprojects.project_id as project_id',
        'roles.role_name as role',
        DB::raw('DATE_FORMAT(DATE_ADD(manageprojects.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as manage_projects_update'))
        // DB::raw('COUNT(userprojects.user_id) as num_member'))
        ->join('userprojects', 'manageprojects.id', '=', 'userprojects.project_id')
        ->join('users', 'users.id', '=', 'userprojects.user_id')
        ->leftjoin('roles', 'roles.id', '=', 'userprojects.role_id')
        ->leftjoin('status', 'manageprojects.status_code', '=' , 'status.code')
        ->groupBy('manageprojects.id')
        ->orderBy('manageprojects.id' , 'asc')
        ->Where('users.id','=',$user)
        ->get();


        // dd($manage_project);
        return Datatables::of($manage_project)->make(true);
    }
}
