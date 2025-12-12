<?php

namespace App\Observers;

use App\VungKinhTeTrongDiem;
use App\Traits\GetDataCache;

class VungKinhTeTrongDiemObserver
{
    use GetDataCache;

    public function created(VungKinhTeTrongDiem $model){
        $this->forgetByName(VungKinhTeTrongDiem::class);
    }

    public function updated(VungKinhTeTrongDiem $model){
        $this->forgetByName(VungKinhTeTrongDiem::class);
    }

    public function deleted(VungKinhTeTrongDiem $model){
        $this->forgetByName(VungKinhTeTrongDiem::class);
    }
}