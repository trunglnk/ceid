<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Menu;
use App\RoleMenu;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if (empty($user)) {
            return redirect('login');
        }

        $menuHome = RoleMenu::query()
            ->where('home_router', true)
            ->where('role_id', $user->role_id)
            ->firstOrFail();
        if (isset($menuHome->menu->router_link)) {
            return redirect()->route($menuHome->menu->router_link);
        }

        return redirect('login');
    }

    public function indexWelcome()
    {
        $user = Auth::user();
        if (isset($user)) {
            return redirect('home');
        }
        return redirect('login');
    }

    function checkLogin()
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'Thành công',
                'result' => true,
            ], 200, []);
        } else {
            return response()->json([
                'message' => 'Thành công',
                'result' => false,
            ], 200, []);
        }
    }
}
