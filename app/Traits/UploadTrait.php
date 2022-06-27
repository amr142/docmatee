<?php

namespace App\Traits;

trait UploadTrait{

    public function uploadd($file){
        $file = time() .'.'.request()->file('image')->extension();
        request ()->file('image')->storeAs('public',$file);
        $result="http://docmate.herokuapp.com/storage/".$file;
        return $result;
    }

}
