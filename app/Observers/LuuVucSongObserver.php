<?php

namespace App\Observers;

use App\LuuVucSong;
use App\Traits\GetDataCache;

class LuuVucSongObserver
{
    use GetDataCache;

    public function created(LuuVucSong $model){
        $this->forgetByName(LuuVucSong::class);
    }

    public function updated(LuuVucSong $model){
        $this->forgetByName(LuuVucSong::class);
    }

    public function deleted(LuuVucSong $model){
        $this->forgetByName(LuuVucSong::class);
    }
}