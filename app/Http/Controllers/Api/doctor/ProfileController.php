<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Mail\myTestMail;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $doctor =Auth::guard('doctor-api')->user();;
        return new DoctorResource($doctor);
    }
    public function update(Request $request)
    {
        $doctor =Auth::guard('doctor-api')->user();
        if (!$doctor)
            return response()->json(['error' => 'not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $doctor->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'union_id' => $request->union_id,
                'department' => $request->department,
                'image'=>$result,
            ]);
            return new DoctorResource($doctor);
        }
        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'union_id' => $request->union_id,
            'department' => $request->department,
        ]);
        $doctor =Auth::guard('doctor-api')->user();
        return new DoctorResource($doctor);
    }

    public function changepassword(Request $request){
        $hashedPassword =Auth::guard('doctor-api')->user();

        if (\Hash::check($request->oldpassword , $hashedPassword['password'] )) {
            $hashedPassword->update([
                'password' => bcrypt($request->password),
            ]);
            return response()->json(['success' => 'password updated'], 200);
        }
        return response([ 'message' => 'please check old password again'],200);

    }

    public function updatepassword(Request $request)
    {
        $matchThese = ['email' => $request->email, 'otp' => $request->otp];
        $doctor=Doctor::where($matchThese);
        if (!$doctor->get()->isEmpty()){
            $doctor->update([
                'password' => bcrypt($request->password),
            ]);
            return response()->json(['success' => 'password updated'], 200);
        }
        return response(["status" => 401, 'message' => 'invalid otp']);
    }
    public function requestOtp(Request $request)
    {
        $doctor=Doctor::where(["email"=>$request->email]);
        $otp = rand(1000,9999);
        $doctor=$doctor->update(['otp' => $otp]);

        if($doctor){
            // send otp in the email
            $mail_details = [
                'subject' => 'Reset Password OTP',
                'body' => 'Your OTP is : '. $otp
            ];
            \Mail::to($request->email)->send(new myTestMail($mail_details));
            return response(["status" => 200, "message" => "OTP sent successfully"]);
        }
        else{
            return response(["status" => 401, 'message' => 'email not found']);
        }
    }
}
