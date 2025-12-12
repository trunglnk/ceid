<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait GetDanhMuc {      
    public function getDataByName($name) {
        if($this->has('danh_muc', $name)) {
            return $this->get('danh_muc', $name);
        }     
        $data = $name::query()->get();
        $this->forever('danh_muc', $name, $data);
        return $data;      
    }

    public function forgetByName($name) {  
        if($this->has('danh_muc', $name)) {
            return $this->forget('danh_muc', $name);
        }             
    }

}