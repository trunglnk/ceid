<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Menu;
use App\RoleMenu;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('TRUNCATE role_menus, roles, menus RESTART IDENTITY CASCADE;');

        $sysadmin = Role::create(array(
            'name' => 'sysadmin',
            'description'    => 'Manager system',
            'code' => 'sysadmin'
        ));

        User::create(array(
            'name'     => 'System admin',
            'username' => 'sysadmin',
            'email'    => 'sysadmin@ceid.gov.com',
            'password' => Hash::make('12345678'),
            'role_id' => $sysadmin->id,
            'active' => true
        ));



        $menuHeThong = Menu::create(array(
            'name' => 'System',
            'parent_id' => null,
            'router_link' => null,
            'fa_icon' => 'fa-cog',
            'active' => true,
            'order' => 2
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuHeThong->id,
            'home_router' => false
        ));

        $menuChucNang = Menu::create(array(
            'name' => 'Menu',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.menus',
            'fa_icon' => 'fa-reorder',
            'active' => true,
            'order' => 1
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuChucNang->id,
            'home_router' => false
        ));

        $menuQuyen = Menu::create(array(
            'name' => 'Role',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.roles',
            'fa_icon' => 'fa-group',
            'active' => true,
            'order' => 2
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuQuyen->id,
            'home_router' => false
        ));

        $menuBangPhanQuyen = Menu::create(array(
            'name' => 'Function',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.functions',
            'fa_icon' => 'fa-gg',
            'active' => true,
            'order' => 3
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuBangPhanQuyen->id,
            'home_router' => false
        ));

        $menuNguoiDung = Menu::create(array(
            'name' => 'User',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'users',
            'fa_icon' => 'fa-user',
            'active' => true,
            'order' => 4
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuNguoiDung->id,
            'home_router' => true
        ));
    }
}
