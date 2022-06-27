<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use GeneralTrait;
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password','union_id','department');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'password' => 'required|string|min:6|max:50',
            'union_id' => 'required',
            'department' => 'required|string',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'union_id' => $request->union_id,
            'department' => $request->department,
            ]);
        event(new Registered($doctor));
        return response()->json(['message' => 'User created successfully'], 200);
    }


    public function login(Request $request){

        $credentials = $request->only('union_id', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'union_id' => 'required',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $token = Auth::guard('doctor-api')->attempt($credentials);  //generate token

        if (!$token)
            return $this->returnError('E001', 'Login credentials are invalid');

        $user = Auth::guard('doctor-api')->user();
        $user ->api_token = $token;
        //return token
        return $this->returnData('login success','token', $token);

    }


    public function logout(Request $request)
    {
        $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError(111,'some thing went wrongs');
            }
            return $this->returnSucess('Logged out successfully');
        }else{
            $this -> returnError(404,'some thing went wrongs');
        }

    }

}
