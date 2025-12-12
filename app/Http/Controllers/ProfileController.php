<?php

namespace App\Http\Controllers;

use App\Menu;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Exception;
use App\Traits\GetDataCache;
use App\UserToken;
use DB;

class ProfileController extends Controller
{
    use GetDataCache;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
     $user = Auth::user();
        return view('auth.profile',['user'=>$user]);
    }

    /**
     * Handle a update profile request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $info = $request->only('name');

        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'email' => 'max:255|email',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.invalid'));
        }

        $user = Auth::user();

        User::query()->find($user->id)
            ->update($info);

        return back()->withInput()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.update_success'));
    }

    public function changePassword(Request $request)
    {
        $info = $request->all();

        $validator = Validator::make($info, [
            'password' => 'required',
            'new_password' => 'required|max:255|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.invalid'));
        }

        $user = Auth::user();

        if (!Hash::check($info['password'], $user->password)) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.password_wrong'));
        }
        
        DB::beginTransaction();
        try {
            User::query()
                ->find($user->id)
                ->update(['password' => Hash::make($info['new_password'])]);            
            DB::commit();
            Auth::logout();
            return redirect('login');
        }
        catch(Exeption $exception){
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.update_error'));
        }            
    }
}
