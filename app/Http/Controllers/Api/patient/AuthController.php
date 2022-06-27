<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\Patient;
//use Tymon\JWTAuth\Exceptions\JWTException;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use GeneralTrait;
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:patients',
            'password' => 'required|string|min:6|max:50',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $patient = Patient::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'date' => $request->date,
            'phone' => $request->phone,
            'address' => $request->address,
            'blood_type' => $request->blood_type,
            'weight'=>$request->weight,
            'height'=>$request->height,
            ]);
        event(new Registered($patient));
        return response()->json(['message' => 'User created successfully'], 200);
    }
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $token = Auth::guard('patient-api')->attempt($credentials);  //generate token

        if (!$token)
            return $this->returnError('E001', 'Login credentials are invalid');

        $user = Auth::guard('patient-api')->user();
        $user ->api_token = $token;
        //return token
        return $this->returnData('login success','token', $token);  //return json response


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
            $this -> returnError(1111,'some thing went wrongs');
        }

    }

}
