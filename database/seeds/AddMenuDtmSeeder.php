<?php

use App\Menu;
use App\Role;
use App\RoleMenu;
use Illuminate\Database\Seeder;

class AddMenuDtmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuHeThong = Menu::updateOrCreate(array(
            'name' => 'ĐTM',
            'parent_id' => null,
            'router_link' => 'documents.dtm',
            'fa_icon' => 'fa fa-lg fa-lightbulb-o',
            'active' => true,
            'order' => 15
        ));
        $roles = Role::all();
        foreach ($roles as $role) {
            RoleMenu::updateOrCreate(array(
                'role_id' => $role->id,
                'menu_id' => $menuHeThong->id,
                'home_router' => false
            ), array(
                'role_id' => $role->id,
                'menu_id' => $menuHeThong->id,
                'home_router' => false
            ));
        }
    }
}
