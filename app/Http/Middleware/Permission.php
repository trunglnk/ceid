<?php

namespace App\Http\Middleware;

use App\Menu;
use Closure;
use Illuminate\Support\Facades\Request;
use App\Traits\GetDataCache;

class Permission
{    
    use GetDataCache;

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return ResponseForbidden|mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->ajax() && $request->isMethod('get')){
            $user = $request->user();
            $roleMenus = $this->getDataByName(\App\RoleMenu::class);
            $roleMenusForUser = $roleMenus->where('role_id', $user->role_id);
            $menus = $this->getDataByName(\App\Menu::class);            
            $routers = $menus->whereIn('id', $roleMenusForUser->pluck('menu_id'));
            $routeName = Request::route()->getName();           
            
            if(!$routers->contains('router_link', Request::route()->getName()) && $menus->contains('router_link', Request::route()->getName())){
                return response(__('system.error_permission'));
            };   
        }        
        return $next($request);
    }
}
