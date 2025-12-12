<?php

namespace App\Observers;

use App\RoleMenu;
use App\Traits\GetDataCache;

class RoleMenuObserver
{
    use GetDataCache;

    public function created(RoleMenu $model){        
        $this->forgetMenuForRole($model->role_id);
    }

    public function updated(RoleMenu $model){        
        $this->forgetMenuForRole($model->role_id);
    }

    public function deleted(RoleMenu $model){        
        $this->forgetMenuForRole($model->role_id);
    }
}