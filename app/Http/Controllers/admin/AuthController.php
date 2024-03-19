<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait ;
    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('admin')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');

            $admin = Auth::guard('admin')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('admin', $admin);

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }


   
    public function regester (Request $request){
       $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }else{
            Admin::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password
            ]);
            return \response()->json([
                'status' => 'true'
            ]);
        }


        
     }


     public function logout(){

        \auth()->logout();
        return $this->returnSuccessMessage('logeed out successfully');
     }

     
    public function profile(){

        $user =   \auth()->guard('admin')->user();
        if(!$user){
            return $this->returnError(404, 'some thing went wrong');
        }
        return $this->returnData('user',$user);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
