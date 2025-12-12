<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\GetDataCache;

class MenuComposer
{
    use GetDataCache;

    protected $menus;

    public function __construct(Request $request)
    {
        if (Auth::check()) {
            $this->menus = $this->getMenusForUser(Auth::user());
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('menus', $this->menus);
    }
}
