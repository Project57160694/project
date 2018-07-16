<?php
namespace App\Http\Requests\Admins;
use Illuminate\Foundation\Http\FormRequest;
/*Project name : Softtest
Floder : app/Http/Controller/
File : loginController.php
Name : Narumol kuntonkidja 57160694
Date : 13/4/2018
Description : ทำการ Validate ค่าว่างของ Input Form*/
class LoginRequest extends FormRequest {
  public function rules(){
    return [
             'username' => 'required',
             'password' => 'required'
           ];
  }
  public function authorize(){
    return true;
  }
}
