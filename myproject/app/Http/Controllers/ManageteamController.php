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
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use DB;
use Auth;
use Carbon\Carbon;


class ManageteamController extends Controller
{
    public function index($id_project){

      // $check = $id_project;
      // dd($check);
      $managemember = new Userproject;
      $role_mem = Role::pluck('role_name','id')->toArray();
      $name_mem = User::pluck('name','id')->toArray();
      $data = array('role_list' => $role_mem,
                    'namemem_list' =>$name_mem,
                    'managemember' => $managemember,
                    'project' => $id_project
      );
      return view('manageteam', $data);
    }

    public function create(){
        $userproject = New Userproject();

    }

    public function store(Request $request)
    {
      $managemember = new Userproject;
      $managemember->user_id = Input::get('name_mem');
      $managemember['update_by'] = Auth::User()->id;
      $managemember['updated_at'] = date('Y-m-d H:i:s');
      $managemember['created_at'] = date('Y-m-d H:i:s');
      $useradd = $managemember->user_id;
      $managemember->role_id = Input::get('role');
      $managemember->project_id = $request->project_id;
      $proadd = $managemember->project_id;
      if($useradd){
        $checkMember = Userproject::where('project_id',$proadd)
                                  ->where('user_id',$useradd)
                                  ->first();
        if(!empty($checkMember)){
          $return = array(
            'status' => 0,
            'message' => 'ไม่สามารถเพิ่มสมาชิกท่านนี้ได้ เนื่องจากเป็นสมาชิกในโครงการนี้อยู่แล้ว'
          );
        }
        else {
          $managemember->save();
          $return = array(
          'status' => 1,
          'message' => 'เพิ่มสมาชิกสำเร็จ');
        }
      }
      return response($return,200);
    }

    public function show($id)
    {

    }

    public function edit($id){
      $managemember = new Userproject;
      $managemember = Userproject::where('id', $id)
                                  ->first();
      $role_mem = Role::pluck('role_name','id')->toArray();
      $name_mem = User::select('*')
                        ->where('id', '=', $managemember->user_id )
                        ->get();
      $data = array('role_list' => $role_mem,
                    'namemem_list' =>$name_mem,
                    'managemember' => $managemember,
      );
      // dd($name_mem[0]['name']);
      // foreach ($name_mem as $value) {
      //   echo $value['name'];
      // }die;
      return view('manageteam_form',$data);
    }

    public function update(Request $request, $id){
      $inputs = $request->All();
      $managemember['user_id'] = $inputs['name_mem'];
      // dd($managemember);
      $useredit = $managemember['user_id'];
      $managemember['role_id'] = $inputs['role'];
      Userproject::where('id',$id)->limit(1)->update($managemember);
      $return['status'] = 1;
      $return['message'] = 'แก้ไขข้อมูลสมาชิกสำเร็จ';
      // $proedit = $managemember['project_id'];
      // if(empty($managemember['project_id'])){
      //   $managemember['project_id'] = $request->project_id;
      // }
      // else{
      //   $checkMember = Userproject::where('id',$id)
      //                             ->where('project_id',$proedit)
      //                             ->where('user_id',$useredit)
      //                             ->first();
      //   if(!empty($checkMember)){
      //     $return = array(
      //       'status'=> 0,
      //       'message' => 'ไม่สามารถแก้ไขข้อมูลโดยการเปลี่ยนแปลงเป็นสมากชิกท่านนี้ได้ เนื่องจากเป็นสมาชิกในโครงการนี้อยู่แล้ว'
      //     );
      //   }
      //   else{
      //     Userproject::where('id',$id)->limit(1)->update($managemember);
      //     return array(
      //       'status' => 1,
      //       'message' => 'แก้ไขข้อมูลสมาชิกสำเร็จ'
      //     );
      //   }
      // }
      return response($return,200);
    }

    public function destroymanageteam($id)
    {

      Userproject::destroy($id);
        $return['status'] = 1;
        $return['message'] = 'ลบรายการสำเร็จ';
      return response($return, 200);
    }


    public function getTeamDatatables(Request $request, $project)
    {
      $i=1;
      $user = Auth::user()->id;
      // dd($user);
      $sub = User::select("users.name")->where("users.id","=", "userprojects.update_by")->toSql();
      $managemember = Userproject::select(
      'userprojects.id as id',
      'manageprojects.project_name as p_name',
      'users.name as user_name',
      'users.email as user_email',
      'roles.role_name as role',
      // 'userprojects.update_by as user_update',
      DB::raw('DATE_FORMAT(DATE_ADD(userprojects.updated_at, INTERVAL 543 YEAR), "%d-%m-%Y %H:%i:%s") as date_update'),
      DB::raw("(select `users`.`name` from `users` where `users`.`id` = 1) as sub"))
      ->leftjoin('manageprojects', 'userprojects.project_id', '=', 'manageprojects.id')
      ->leftjoin('users', 'userprojects.user_id', '=', 'users.id')
      ->join('roles', 'userprojects.role_id', '=', 'roles.id')
      ->Where('userprojects.project_id', '=', $project)
      // ->WhereNotIn('userprojects.user_id', function($query)
      //               {$query->select('userprojects.user_id')
      //               ->from('userprojects')
      //               ->where('userprojects.project_id', '!=', '1');})
      ->get();
      // dd($managemember);
      // array_push($i,$managemember);
      // foreach ($data['managemember'] as $key => $value) {
      //   $data['create_by'] = Userproject::select('users.name','users.id')
      //                             ->join('users','userprojects.user_id','=','users.id')
      //                             ->where('userprojects.user_id',$value['user_update'])
      //                             ->get()->toArray();
      // }
      // $create_by = Userproject::select('users.name','users.id')
      //                           ->join('users','userprojects.user_id','=','users.id')
      //                           ->where('userprojects.user_id',$managemember['user_update'])
      //                           ->get()->toArray();
      // dd($data['create_by']);
      // foreach ($managemember as $key => $value) {
      //   if($value['edit_by'] != 0){
      //     $create_by = Userproject::select('users.name','users.id')
      //                               ->join('users','userprojects.user_id','=','users.id')
      //                               ->where('userprojects.user_id',$value['edit_by'])
      //                               ->distinct()
      //                               ->get(['id'])->toArray();
      //   array_push($i,$create_by);
      //   }
      // }
      // dd($managemember);
      return Datatables::of($managemember)->make(true);
    }
}
