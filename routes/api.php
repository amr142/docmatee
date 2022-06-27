<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\patient;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//,'assign.guard:doctor-api'

            //********************************************  doctor   ***************************************************
Route::group(['prefix'=>'doctor','middleware'=>['api'],'namespace'=>'Api\doctor'],function (){
    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::post('reset_password','ProfileController@updatepassword');
    Route::post('resetpassword_otp','ProfileController@requestOtp');
    Route::post('doctor','ProfileController@doctor');

    Route::group(['middleware'=>['auth.guard:doctor-api',]],function(){
        Route::get('profile','ProfileController@profile');
        Route::post('profile/change_password','ProfileController@changepassword');
        Route::post('profile/update','ProfileController@update' );
        Route::post('logout','AuthController@logout' );
        //add patient   **********************************************************************
        Route::post('find_patient', 'OtpController@FindPatient');
        Route::post('request_otp', 'OtpController@requestOtp');
        Route::post('verify_otp', 'OtpController@verify');
        Route::get('my_patient', 'OtpController@mypatient');
        Route::group(['middleware'=>['authorized_doctor']],function(){

        //medicine   **********************************************************************
        Route::post('{patient}/medicine', 'MedicineController@create');
        Route::post('{patient}/medicine/{medicineid}', 'MedicineController@update');
        Route::delete('{patient}/medicine/{medicineid}', 'MedicineController@destroy');;

        //prescription   **********************************************************************
        Route::get('{patient}/prescriptionall','PrescriptionController@index' );
        Route::get('{patient}/prescription/{id}','PrescriptionController@show' );
        Route::get('{patient}/prescription','PrescriptionController@create' );
        Route::post('{patient}/prescription/{id}','PrescriptionController@update' );
        Route::delete('{patient}/prescription/{id}','PrescriptionController@destroy' );

        //vaccine   **********************************************************************
        Route::get('{patient}/vaccine','VaccineController@index' );
        Route::get('{patient}/vaccine/{id}','VaccineController@show' );
        Route::post('{patient}/vaccine','VaccineController@create' );
        Route::post('{patient}/vaccine/{id}','VaccineController@update' );
        Route::delete('{patient}/vaccine/{id}','VaccineController@destroy' );

        //surgery   **********************************************************************
        Route::get('{patient}/surgery','SurgeryController@index' );
        Route::get('{patient}/surgery/{id}','SurgeryController@show' );
        Route::post('{patient}/surgery','SurgeryController@create' );
        Route::post('{patient}/surgery/{id}','SurgeryController@update' );
        Route::delete('{patient}/surgery/{id}','SurgeryController@destroy' );

        //Radiology   **********************************************************************
        Route::get('{patient}/radiology','RadiologyController@index' );
        Route::get('{patient}/radiology/{id}','RadiologyController@show' );
        Route::post('{patient}/radiology','RadiologyController@create' );
        Route::post('{patient}/radiology/{id}','RadiologyController@update' );
        Route::delete('{patient}/radiology/{id}','RadiologyController@destroy' );

        //lab   **********************************************************************
        Route::get('{patient}/lab','LabController@index' );
        Route::get('{patient}/lab/{id}','LabController@show' );
        Route::post('{patient}/lab','LabController@create' );
        Route::post('{patient}/lab/{id}','LabController@update' );
        Route::delete('{patient}/lab/{id}','LabController@destroy' );

        //glucos   **********************************************************************
        Route::get('{patient}/glucos','GlucosController@index' );
        Route::get('{patient}/glucos/{id}','GlucosController@show' );
        Route::post('{patient}/glucos','GlucosController@create' );
        Route::post('{patient}/glucos/{id}','GlucosController@update' );
        Route::delete('{patient}/glucos/{id}','GlucosController@destroy' );

        //FamilyHistory   **********************************************************************
        Route::get('{patient}/family_history','FamilyHistoryController@index' );
        Route::get('{patient}/family_history/{id}','FamilyHistoryController@show' );
        Route::post('{patient}/family_history','FamilyHistoryController@create' );
        Route::post('{patient}/family_history/{id}','FamilyHistoryController@update' );
        Route::delete('{patient}/family_history/{id}','FamilyHistoryController@destroy' );

        //Allergy   **********************************************************************
        Route::get('{patient}/allergy','AllergyController@index' );
        Route::get('{patient}/allergy/{id}','AllergyController@show' );
        Route::post('{patient}/allergy','AllergyController@create' );
        Route::post('{patient}/allergy/{id}','AllergyController@update' );
        Route::delete('{patient}/allergy/{id}','AllergyController@destroy' );

        //pressure   **********************************************************************
        Route::get('{patient}/pressure','PressureController@index' );
        Route::get('{patient}/pressure/{id}','PressureController@show' );
        Route::post('{patient}/pressure','PressureController@create' );
        Route::post('{patient}/pressure/{id}','PressureController@update' );
        Route::delete('{patient}/pressure/{id}','PressureController@destroy' );



        });
    });
});

        //********************************************  Patient   ***************************************************

Route::group(['prefix'=>'patient','middleware'=>['api'],'namespace'=>'Api\patient'],function (){
    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::get('/email/verify/{id}/{hash}', 'VerifyEmailController@__invoke')
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('reset_password','ProfileController@updatepassword');
    Route::post('resetpassword_otp','ProfileController@requestOtp');

    Route::group(['middleware'=>['auth.guard:patient-api']],function() {
        Route::post('profile/change_password','ProfileController@changepassword');
        Route::get('profile','ProfileController@profile');
        Route::post('profile/update','ProfileController@update' );
        Route::post('logout','AuthController@logout' );

        //prescription   **********************************************************************
        Route::get('prescription/','PrescriptionController@index' );
        Route::get('prescription/{id}','PrescriptionController@show' );

        //vaccine   **********************************************************************
        Route::get('vaccine','VaccineController@index' );
        Route::get('vaccine/{id}','VaccineController@show' );
        Route::post('vaccine','VaccineController@create' );
        Route::post('vaccine/{id}','VaccineController@update' );
        Route::delete('vaccine/{id}','VaccineController@destroy' );

        //Radiology   **********************************************************************
        Route::get('radiology','RadiologyController@index' );
        Route::get('radiology/{id}','RadiologyController@show' );
        Route::post('radiology','RadiologyController@create' );
        Route::post('radiology/{id}','RadiologyController@update' );
        Route::delete('radiology/{id}','RadiologyController@destroy' );

        //lab   **********************************************************************
        Route::get('lab','LabController@index' );
        Route::get('lab/{id}','LabController@show' );
        Route::post('lab','LabController@create' );
        Route::post('lab/{id}','LabController@update' );
        Route::delete('lab/{id}','LabController@destroy' );

        //surgery   **********************************************************************
        Route::get('surgery','SurgeryController@index' );
        Route::get('surgery/{id}','SurgeryController@show' );
        Route::post('surgery','SurgeryController@create' );
        Route::post('surgery/{id}','SurgeryController@update' );
        Route::delete('surgery/{id}','SurgeryController@destroy' );

        //glucos   **********************************************************************
        Route::get('glucos','GlucosController@index' );
        Route::get('glucos/{id}','GlucosController@show' );
        Route::post('glucos','GlucosController@create' );
        Route::post('glucos/{id}','GlucosController@update' );
        Route::delete('glucos/{id}','GlucosController@destroy' );

        //Allergy   **********************************************************************
        Route::get('allergy','AllergyController@index' );
        Route::get('allergy/{id}','AllergyController@show' );
        Route::post('allergy','AllergyController@create' );
        Route::post('allergy/{id}','AllergyController@update' );
        Route::delete('allergy/{id}','AllergyController@destroy' );

        //FamilyHistory   **********************************************************************
        Route::get('family_history','FamilyHistoryController@index' );
        Route::get('family_history/{id}','FamilyHistoryController@show' );
        Route::post('family_history','FamilyHistoryController@create' );
        Route::post('family_history/{id}','FamilyHistoryController@update' );
        Route::delete('family_history/{id}','FamilyHistoryController@destroy' );
        //Pressure   **********************************************************************
        Route::get('pressure','PressureController@index' );
        Route::get('pressure/{id}','PressureController@show' );
        Route::post('pressure','PressureController@create' );
        Route::post('pressure/{id}','PressureController@update' );
        Route::delete('pressure/{id}','PressureController@destroy' );
    });
});

