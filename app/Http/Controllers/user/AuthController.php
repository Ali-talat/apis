<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait ;
    public function login (Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }
        $credentials = $request->only(['email', 'password']);
        $token = Auth::guard('api')->attempt($credentials);
       if(!$token){
        return $this->returnError(400,'some thing went wrong');
       }
            $user = \auth()->user();
            $user ->api_token = $token ;
            return $this->returnData('token',$user);

    //     $user = JWTAuth::user();
    //    $user->token_api = $token ;
    //    return response()->json( $user);
   

        
    }


    public function regester (Request $request){
       $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }
         $user =User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password
            ]);
            if(!$user){
                return $this->returnError(400,'some thing went wrong');
            }
        return $this->returnSuccessMessage('user registered successfully');
        


        
     }


     public function logout(Request $request){

        $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        }else{
            $this -> returnError('','some thing went wrongs');
        }

     }

     
    public function profile(){

        $user =   \auth()->user();
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
