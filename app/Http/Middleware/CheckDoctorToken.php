<?php

namespace App\Http\Middleware;

use App\Models\Mypatient;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckDoctorToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $doctorid=Auth::guard('doctor-api')->user()->id;
        $matchThese = ['doctor_id' => $doctorid, 'patient_id' => $request->patient];
        $check=Mypatient::select('id')->where($matchThese)->get();
        if ($check->isNotEmpty()){
            return $next($request);}
        return response()->json(['error' => 'not authorized'], 404);
    }
}
