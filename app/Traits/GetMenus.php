<?php

namespace App\Traits;
use App\Menu;

trait GetMenus {    
    public function getMenusForUser($user) {
        $menus = collect([]);
        if(!empty($user->role_id)) {
            if($this->has('menu', $user->role_id)) {
                $menus = $this->get('menu', $user->role_id);
            }
            else{
                $menus = Menu::query()            
                    ->ofRole($user->role_id)
                    ->with(['children' => function($query) use($user) {
                        $query->ofRole($user->role_id)
                            ->orderBy('order');
                    }, 'roles'])
                    ->firstLevel()                        
                    ->orderBy('order')
                    ->get();
                $this->put('menu', $user->role_id, $menus);
            }
        }  
        return $menus;      
    }

    public function forgetMenuForRole($roleId) {
        $this->forget('menu', $roleId);
    }

    public function forgetMenuAll() {
        $roles = $this->getDataByName(\App\Role::class);
        foreach($roles as $role) {
            $this->forget('menu', $role->id);
        }        
    }
}