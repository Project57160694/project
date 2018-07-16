<?php

namespace App\Http\Controllers\Auth;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/manageproject';
    protected $username;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }


    /**
    * Get the login username to be used by the controller.
    *
    * @return string
    */
    public function findUsername()
    {
     $login = request()->input('login');

     $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

     request()->merge([$fieldType => $login]);

     return $fieldType;
    }
   /**
   * Get username property.
   *
   * @return string
   */
    public function username()
    {
      return $this->username;
    }
    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request) {
      Auth::logout();
      return redirect('/login');
    }

}
