<?php

namespace App\Observers;

use App\LoaiHinhCoSo;
use App\Traits\GetDataCache;

class LoaiHinhCoSoObserver
{
    use GetDataCache;

    public function created(LoaiHinhCoSo $model){
        $this->forgetByName(LoaiHinhCoSo::class);
    }

    public function updated(LoaiHinhCoSo $model){
        $this->forgetByName(LoaiHinhCoSo::class);
    }

    public function deleted(LoaiHinhCoSo $model){
        $this->forgetByName(LoaiHinhCoSo::class);
    }
}