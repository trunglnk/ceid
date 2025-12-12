<?php

namespace App\Observers;

use App\Dieu;
use App\Traits\GetDataCache;

class DieuObserver
{
    use GetDataCache;

    public function created(Dieu $model){
        $this->forgetByName(Dieu::class);
    }

    public function updated(Dieu $model){
        $this->forgetByName(Dieu::class);
    }

    public function deleted(Dieu $model){
        $this->forgetByName(Dieu::class);
    }
}