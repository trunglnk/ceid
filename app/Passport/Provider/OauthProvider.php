<?php

namespace App\Passport\Provider;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Illuminate\Support\Arr;

class OauthProvider extends AbstractProvider
{
    public const IDENTIFIER = 'oauth';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getLaravelPassportUrl('authorize_uri'), $state);
    }
    protected function getTokenUrl()
    {
        return $this->getLaravelPassportUrl('token_uri');
    }
    protected function getLaravelPassportUrl($type)
    {
        if ($type == 'authorize_uri')
            return rtrim(config('services.oauth.app_url'), '/') . '/' . ltrim(($this->getConfig($type, Arr::get([
                'authorize_uri' => 'oauth/authorize',
                'token_uri'     => 'oauth/token',
                'userinfo_uri'  => 'api/me',
            ], $type))), '/');
        return rtrim(config('services.oauth.app_api_url'), '/') . '/' . ltrim(($this->getConfig($type, Arr::get([
            'authorize_uri' => 'oauth/authorize',
            'token_uri'     => 'oauth/token',
            'userinfo_uri'  => 'api/me',
        ], $type))), '/');
    }
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getLaravelPassportUrl('userinfo_uri'), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return (array) json_decode($response->getBody(), true);
    }
    protected function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUrl,
                'code' => $code,
            ]
        ]);
        return (array) json_decode($response->getBody(), true);
    }
    protected function mapUserToObject(array $user)
    {
        $key = 'data';
        $data = is_null($key) === true ? $user : Arr::get($user, $key, []);
        return (new User())->setRaw($data)->map([
            'id'       => $data['id'],
            'name'     => $data['first_name'] . ' ' . $data['last_name'],
            'email'    => $data['email'],
            'avatar'   => $data['avatar_url'],
            'role_code'   =>  $this->convertRole($data['roles'])
        ]);
    }
    public function convertRole($roles)
    {
        if (empty($roles)) {
            return null;
        }
        $role = $roles[0]['code'];
        return $role;
    }
    public function user()
    {

        $response = $this->getAccessToken($this->getCode());

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = $this->parseAccessToken($response)
        ));

        $this->credentialsResponseBody = $response;

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->credentialsResponseBody);
        }

        return $user->setToken($token)
            ->setRefreshToken($this->parseRefreshToken($response))
            ->setExpiresIn($this->parseExpiresIn($response));
    }
}
