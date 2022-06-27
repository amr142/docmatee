<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Mail\myTestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $patient =Auth::guard('patient-api')->user();
        return new PatientResource($patient);
    }
    public function update(Request $request)
    {
        $patient =Auth::guard('patient-api')->user();
        if (!$patient)
            return response()->json(['error' => 'not found'], 404);
        if($request->hasFile('image')) {
            $file = time() . '.' . request()->file('image')->extension();
            request()->file('image')->storeAs('public', $file);
            $result = "http://docmate.herokuapp.com/storage/" . $file;
            $patient->update([
                'name' => $request->name,
                'email' => $request->email,
                'date' => $request->date,
                'phone' => $request->phone,
                'address' => $request->address,
                'blood_type' => $request->blood_type,
                'weight' => $request->weight,
                'height' => $request->height,
                'image' => $result,
            ]);
            return new PatientResource($patient);
        }

        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
            'date' => $request->date,
            'phone' => $request->phone,
            'address' => $request->address,
            'blood_type' => $request->blood_type,
            'weight' => $request->weight,
            'height' => $request->height,
        ]);
        $patient =Auth::guard('patient-api')->user();
        return new PatientResource($patient);
    }

    public function changepassword(Request $request){
        $hashedPassword =Auth::guard('patient-api')->user();

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
        $patient=Patient::where($matchThese);
        if (!$patient->get()->isEmpty()){
            $patient->update([
                'password' => bcrypt($request->password),
            ]);
            return response()->json(['success' => 'password updated'], 200);
        }
        return response(['message' => 'invalid otp'],200);
    }
    public function requestOtp(Request $request)
    {
        $patient=Patient::where(["email"=>$request->email]);
        $otp = rand(1000,9999);
        $patient=$patient->update(['otp' => $otp]);

        if($patient){
            // send otp in the email
            $mail_details = [
                'subject' => 'Reset Password OTP',
                'body' => 'Your OTP is : '. $otp
            ];

            \Mail::to($request->email)->send(new myTestMail($mail_details));

            return response([ "message" => "OTP sent successfully"],200);
        }
        else{
            return response([ 'message' => 'email not found'],200);
        }
    }
}
