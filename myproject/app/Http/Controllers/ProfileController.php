<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manageproject;
use App\Manageplan;
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


class ProfileController extends Controller
{
    public function index(){
      //return view('admin.manageproject.index2');
      return view('profile');

    }

    public function create()
    {
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
        // if(empty($userproject['role_id'])){
        //   $userproject['role_id'] = '1';
        // }
        // elseif ($userproject['role_id'] = '1') {
        //   // code...
        // }
        // else {
        //   $userproject['role_id'] = '1';
        // }
        $userproject->project_id = $manageproject->id;
        $userproject['updated_at'] = date('Y-m-d H:i:s');
        $userproject['created_at'] = date('Y-m-d H:i:s');
        $userproject->save();
      return redirect()->action('ProfileController@index');
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
      $manageproject = New Manageproject();
      $manageproject = Manageproject::where('id', $id)->first();
      return view('project_form', array('manageprojects' => $manageproject));
    }

    public function update(Request $request, $id){
      $inputs = $request->All();
      $manage['project_name'] = $inputs['project_name'];
      Manageproject::where('id', $id)->limit(1)->update($manage);
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);
    }

    public function destroymanageproject($id)
    {
      $manage_project_check = Managefunction::
                              where('managefunctions.module_id', $id)
                              ->where('managefunctions.status_code','PP-FC')
                              ->get();
      $count = count($manage_project_check);
      if($count >=1){
        $return['status'] = 0;
      }
      else{
        Manageproject::destroy($id);
          $return['status'] = 1;
          $return['message'] = 'ลบรายการสำเร็จ';
      }
      return response($return, 200);

    }

    public function getManagemyProjectDatatables(Request $request)
    {
      $user = Auth::user()->id;
      $manage_project = Manageproject::select(
        'manageprojects.id as id',
        'manageprojects.project_name as name',
        'status.status_name as status_name',
        'userprojects.project_id as project_id',
        'roles.role_name as role',
        DB::raw('DATE_FORMAT(DATE_ADD(manageprojects.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as manage_projects_update'),
        DB::raw('COUNT(userprojects.user_id) as num_member'))
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
