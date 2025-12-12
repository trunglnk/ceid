<?php

namespace App\Observers;

use App\KhuCongNghiep;
use App\Traits\GetDataCache;

class KhuCongNghiepObserver
{
    use GetDataCache;

    public function created(KhuCongNghiep $model){
        $this->forgetByName(KhuCongNghiep::class);
    }

    public function updated(KhuCongNghiep $model){
        $this->forgetByName(KhuCongNghiep::class);
    }

    public function deleted(KhuCongNghiep $model){
        $this->forgetByName(KhuCongNghiep::class);
    }
}