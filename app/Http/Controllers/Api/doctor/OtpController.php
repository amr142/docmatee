<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Mypatient;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\myTestMail;
use Exception;

class OtpController extends Controller
{
    public function FindPatient(Request $request){
        $patient = Patient::where('email','=',$request->email)->get();
        if(!$patient->isEmpty()){
            return  PatientResource::collection($patient);
        }
        return response( ['message' => 'please check patient email again'],404);
    }


    public function mypatient(Request $request){
        $doctorid=Auth::guard('doctor-api')->user()->id;
        $patientid = Mypatient::select('patient_id')->where(['doctor_id'=>$doctorid])->get();
        $id=$patientid->pluck('patient_id');
        $patient=Patient::whereIn('id',$id)->get();
        return PatientResource::collection($patient);
    }


    public function requestOtp(Request $request)
    {

        $otp = rand(1000,9999);
        //Log::info("otp = ".$otp);
        $patient = Patient::where('email','=',$request->email)->update(['otp' => $otp]);

        if($patient){
            // send otp in the email
            $mail_details = [
                'subject' => 'Testing Application OTP',
                'body' => 'Your OTP is : '. $otp
            ];

            \Mail::to($request->email)->send(new myTestMail($mail_details));

            return response(["status" => 200, "message" => "OTP sent successfully"]);
        }
        else{
            return response(["status" => 401, 'message' => 'Invalid']);
        }
    }

    public function verify(Request $request){

        $patient  = Patient::where([['email','=',$request->email],['otp','=',$request->otp]])->first();
        if($patient){
            $patientid=Patient::select('id')->where('email', '=', $request->email)->get();
            $doctor =Auth::guard('doctor-api')->user()->id;
            $patientid=$patientid[0]['id'];
            $mypatient=Mypatient::create([
                'doctor_id'=>$doctor,
                'patient_id'=>$patientid,
            ]);

            return response(["status" => 200, "message" => "Success",'user']);
        }
        else{
            return response(["status" => 401, 'message' => 'Invalid OTP Code']);
        }
    }
}
