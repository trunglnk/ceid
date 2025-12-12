<?php

// SocialAuthFacebookController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Passport\Models\SocialAccount;
use App\Service\SocialAccountService;
use App\User;
use App\Role;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */

    public function redirect($action, $social)
    {
        Session::put('check', $action);
        return Socialite::driver($social)->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback($social)
    {
        $check = Session::get('check');
        $user = $this->createOrGetUser(Socialite::driver($social)->user(), $social, $check);
        if (!is_string($user)) {
            auth()->login($user);
            return redirect()->to('/');
        } else {
            return redirect()->to('/login');
        }
    }
    public  function createOrGetUser(ProviderUser $providerUser, $social, $check)
    {
        if ($social == 'oauth') {
            return $this->createOrGetOauthUser($providerUser, $check);
        }
    }

    public  function createOrGetOauthUser(ProviderUser $providerUser, $check)
    {
        $account = SocialAccount::whereProvider('oauth')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account && $check != 'login') {
            return 'Email này đã được sử dụng. Vui lòng thử lại';
        }
        $user = User::whereEmail($providerUser->getEmail())->first();
        if (!isset($account) && $user) {
            return 'Email này đã được sử dụng. Vui lòng thử lại';
        }

        $role = Role::query()->select('id')->where('code', $providerUser->role_code)->first();
        $userRaw = $providerUser->user;
        if ($account)
            $user = $account->user;
        else {
            $user = new User();
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'oauth'
            ]);
            $user->fill([
                'password' => md5(rand(1, 10000)),
                'email_token' => str_random(20),
            ]);
        }
        DB::beginTransaction();
        try {
            $user->fill([
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'username' => $userRaw['username'],
                'active' => true,
                'role_id' => isset($role) ? $role->id : null,
                'avatar_url' => $userRaw['avatar_url']
            ]);
            $user->save();
            $account->user()->associate($user);
            $account->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            return 'Lỗi';
        }
    }
}
