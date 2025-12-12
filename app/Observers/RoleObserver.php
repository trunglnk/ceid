<?php

namespace App\Observers;

use App\Role;
use App\Traits\GetDataCache;

class RoleObserver
{
    use GetDataCache;

    public function created(Role $model){
        $this->forgetByName(Role::class);
    }

    public function updated(Role $model){
        $this->forgetByName(Role::class);
    }

    public function deleted(Role $model){
        $this->forgetByName(Role::class);
    }
}