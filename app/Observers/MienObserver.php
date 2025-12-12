<?php

namespace App\Observers;

use App\Mien;
use App\Traits\GetDataCache;

class MienObserver
{
    use GetDataCache;

    public function created(Mien $model){
        $this->forgetByName(Mien::class);
    }

    public function updated(Mien $model){
        $this->forgetByName(Mien::class);
    }

    public function deleted(Mien $model){
        $this->forgetByName(Mien::class);
    }
}