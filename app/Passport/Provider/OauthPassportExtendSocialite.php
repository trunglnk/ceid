<?php

namespace App\Passport\Provider;

use SocialiteProviders\Manager\SocialiteWasCalled;

class OauthPassportExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('oauth', OauthProvider::class);
    }
}
