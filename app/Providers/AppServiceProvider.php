<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\RoleObserver;
use App\Observers\MenuObserver;
use App\Observers\RoleMenuObserver;
use App\Observers\KhuCongNghiepObserver;
use App\Traits\GetDataCache;
use App\Role;
use App\Menu;
use App\RoleMenu;
use App\CustomerType;
use App\KhuCongNghiep;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    use GetDataCache;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Role::observe(RoleObserver::class);
        Menu::observe(MenuObserver::class);
        RoleMenu::observe(RoleMenuObserver::class);                            
        KhuCongNghiep::observe(KhuCongNghiepObserver::class);                            
        \App\CoQuanToChuc::observe(\App\Observers\CoQuanToChucObserver::class);                            
        \App\Dieu::observe(\App\Observers\DieuChucObserver::class);                            
        \App\LoaiHinhCoSo::observe(\App\Observers\LoaiHinhCoSoChucObserver::class);                            
        \App\TinhThanh::observe(\App\Observers\TinhThanhChucObserver::class);                            
        \App\QuanHuyen::observe(\App\Observers\QuanHuyenChucObserver::class);                            
        \App\LuuVucSong::observe(\App\Observers\LuuVucSongChucObserver::class);                            
        \App\Mien::observe(\App\Observers\MienChucObserver::class);                            
        \App\VungKinhTeTrongDiem::observe(\App\Observers\VungKinhTeTrongDiemChucObserver::class);                            
        \App\CheDoThanhTra::observe(\App\Observers\CheDoThanhTraObserver::class);                            
        \App\ChuyenDoiDonVi::observe(\App\Observers\ChuyenDoiDonViObserver::class);
        View::share('roles', $this->getDataByName(Role::class));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
