<?php

namespace App\Traits;

trait GeneralTrait{
    public function returnError($errNum,$msg){
        return response()->json([
            'status'=>false,
            'errnum'=>$errNum,
            'message'=>$msg,
        ]);
    }
    public function returnSucess($msg=""){
        return [
            'status'=>true,
            'message'=>$msg
        ];
    }
    public function returnData($msg,$key,$value){
        return response()->json([
            'status'=>true,
            'message'=>$msg,
            $key=>$value
        ]);
    }
    public function retirnValidationError($validator,$code="E001"){
        return $this->returnError($code,$validator->errors()->first());
    }
    public function returnCodeAccordingToInput($validator){
        $input=array_keys($validator->errors()->toArray());
        $code=$this->getErrorcode($input[0]);
        return $code;
    }
    public function getErrorcode($input){
        if ($input =='name'){
            return 'E0011';
        }
        else if ($input == 'password'){
            return 'E002';
        }
        else if ($input == 'mobile'){
            return 'E003';
        }
        else if ($input == 'union_id'){
            return 'E004';
        }
    }

}
