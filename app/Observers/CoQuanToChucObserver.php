<?php

namespace App\Observers;

use App\CoQuanToChuc;
use App\Traits\GetDataCache;

class CoQuanToChucObserver
{
    use GetDataCache;

    public function created(CoQuanToChuc $model){
        $this->forgetByName(CoQuanToChuc::class);
    }

    public function updated(CoQuanToChuc $model){
        $this->forgetByName(CoQuanToChuc::class);
    }

    public function deleted(CoQuanToChuc $model){
        $this->forgetByName(CoQuanToChuc::class);
    }
}