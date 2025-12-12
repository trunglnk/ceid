<?php

namespace App\Observers;

use App\QuanHuyen;
use App\Traits\GetDataCache;

class QuanHuyenObserver
{
    use GetDataCache;

    public function created(QuanHuyen $model){
        $this->forgetByName(QuanHuyen::class);
    }

    public function updated(QuanHuyen $model){
        $this->forgetByName(QuanHuyen::class);
    }

    public function deleted(QuanHuyen $model){
        $this->forgetByName(QuanHuyen::class);
    }
}