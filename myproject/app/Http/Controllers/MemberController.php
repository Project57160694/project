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
      $member = new Userproject;
      
      $member->project_name = Input::get("project_name");
      $manageproject['status_code'] = 'PP';
      $manageproject['created_by'] = Auth::User()->id;
      $manageproject['updated_at'] = date('Y-m-d H:i:s');
      $manageproject['created_at'] = date('Y-m-d H:i:s');
      $manageproject->save();

        $userproject = new Userproject;
        $userproject['user_id'] = Auth::User()->id;
        $userproject['role_id'] = '1';
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
      $manageproject = New Userproject();
      $manageproject = Userproject::where('id', $id)->first();
      dd($manageproject);
      return view('project_form', array('manageprojects' => $manageproject));
    }

    public function update(Request $request, $id){
      $inputs = $request->All();
      $validator = Validator::make($inputs, [
        'project_name' => 'required',
      ]);
      Userproject::where('id', $id)->limit(1)->update($request->all());
      $return['status'] = 1;
      $return['message'] = 'บันทึกสำเร็จ';
      return response($return, 200);
    }

    public function destroymanageproject($id)
    {
        Userproject::destroy($id);
          $return['status'] = 1;
          $return['message'] = 'ลบรายการสำเร็จ';
      return response($return, 200);

    }

    public function getMemberallData(Request $request)
    {

      return Datatables::of($manage_project)->make(true);
    }
}
