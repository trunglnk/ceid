<?php

namespace App\Observers;

use App\CheDoThanhTra;
use App\Traits\GetDataCache;

class CheDoThanhTraObserver
{
    use GetDataCache;

    public function created(CheDoThanhTra $model){
        $this->forgetByName(CheDoThanhTra::class);
    }

    public function updated(CheDoThanhTra $model){
        $this->forgetByName(CheDoThanhTra::class);
    }

    public function deleted(CheDoThanhTra $model){
        $this->forgetByName(CheDoThanhTra::class);
    }
}