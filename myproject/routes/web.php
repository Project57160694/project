<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**** check Database connect ******/
Route::get('check-connect',function(){
  if(DB::connection()->getDatabaseName()){
    return "YES! susccesfully connected to the DB: " . DB::connection()->getDatabaseName();
  }
  else{
    return 'connection false !!';
  }
});

/***************************************************************************/

Route::get('/', function () {
    return view('welcome');
});

Route::group( [ 'middleware' => ['web'] ], function ()
{
    //this route will use the middleware of the 'web' group, so session and auth will work here
    Route::get('/', function () {
        dd( Auth::user() );
    });
});
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home');


/*Manageproject*/
Route::resource('manageproject','ManageprojectController',['except' => ['show']]);
Route::get('manageproject/datables','ManageprojectController@getManageproject');
Route::post('manageproject/create','ManageprojectController@store');
Route::get('manageproject/delete/{id_project}', 'ManageprojectController@destroymanage');
Route::post('manageproject/{id_project}', 'ManageprojectController@update');

/*Manage Team */
Route::resource('manageteam', 'ManageteamController',['except' => ['show']]);
Route::get('manageteam/{id_project}', 'ManageteamController@index');
Route::post('manageteam/create', 'ManageteamController@store');
Route::get('manageteam/manageteam/delete/{id}', 'ManageteamController@destroymanageteam');
Route::get('manageteam/manageteam/datables/{id_project}', 'ManageteamController@getTeamDatatables');
Route::post('manageteam/{id}', 'ManageteamController@update');

/*Test Plan */
Route::resource('plantest','TestplanController',['except' => ['show']]);
Route::get('plantest/manageplan/datables','TestplanController@getTestPlanDatatables');
Route::post('plantest/create','TestplanController@store');
Route::get('plantest/delete/{id}', 'TestplanController@destroyplan');
Route::post('plantest/{id}', 'TestplanController@update');
Route::get('plantest/get_molfrompro/{id}', 'TestplanController@getModuleFromProject');
Route::get('plantest/get_funcfrommol/{id}', 'TestplanController@getFunctionFromModule');

/*Manage module*/
Route::resource('module','ModuletestController',['except' => ['show']]);
Route::get('module', 'ModuletestController@index');
Route::get('module/module/datables', 'ModuletestController@getNotifyDetailData');
Route::post('module/create','ModuletestController@store');
Route::get('module/delete/{id}', 'ModuletestController@destroyModule');
Route::post('module/{id}', 'ModuletestController@update');

/*Function*/
Route::resource('functionmanage', 'FunctionController',['except' => ['show']]);
Route::get('functionmanage','FunctionController@index');
Route::get('functionmanage/functionmanage/datables','FunctionController@getFunctiondata');
Route::post('functionmanage/create','FunctionController@store');
Route::get('functionmanage/delete/{id}', 'FunctionController@destroymanagefunction');
Route::get('functionmanage/get_molfrompro/{id}', 'FunctionController@getModuleFromProject');
Route::get('status/change/view/{id}/{status_code}', 'CaseManagementController@changeStatusView');

/*Testcase*/
Route::resource('testcasemanage','ManagecaseController',['except' => ['show']]);
Route::get('testcasemanage', 'ManagecaseController@index');
Route::get('testcasemanage/testcasemanage/datables','ManagecaseController@getTestCaseDatatables');
Route::get('testcasemanage/delete/{id}', 'ManagecaseController@destroycase');
Route::post('testcasemanage/create','ManagecaseController@store');
Route::post('testcasemanage/{id}', 'ManagecaseController@update');
Route::get('testcasemanage/get_molfromplan/{id}', 'ManagecaseController@getModuleFromPlan');
Route::get('testcasemanage/get_planfrompro/{id}', 'ManagecaseController@getPlanFromProject');
Route::get('testcasemanage/get_funcfrommol/{id}', 'ManagecaseController@getFunctionFromModule');
