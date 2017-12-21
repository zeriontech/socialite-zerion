<?php

namespace lonesta\SocialiteProviders\Zerion;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'ZERION';

    const AUTH_URL = 'https://apis.zerion.io/oauth/authorize/';

    const TOKEN_URL = 'https://apis.zerion.io/oauth/token/';

    const USER_DATA_URL = 'https://apis.zerion.io/v1/project/access/';


    /**
     * @param $state
     * @return mixed
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            self::AUTH_URL, $state
        );
    }


    /**
     * @param $token
     * @return mixed
     */
    protected function getUserByToken($token)
    {
        $token = 'Bearer ' . $token;

        $response = $this->getHttpClient()->get(
            self::USER_DATA_URL,
            [
                'headers' => [
                    'Authorization' => $token,
                ],
            ]
        );

        return json_decode($response->getBody(), true);
    }


    /**
     * @return string
     */
    protected function getTokenUrl()
    {
        return self::TOKEN_URL;
    }

    /**
     * @param array $user
     * @return mixed
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'    => $user['UUID'],
            'email' => $user['email'],
        ]);
    }


    /**
     * @param $code
     * @return array
     */
    protected function getTokenFields($code)
    {
        return [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
            'grant_type'    => 'authorization_code',
        ];
    }

    /**
     * @param null $state
     * @return array
     */
    protected function getCodeFields($state = null)
    {
        $fields = [
            'client_id'     => $this->clientId,
            'callback'      => urlencode($this->redirectUrl),
            'response_type' => 'code',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return array_merge($fields, $this->parameters);
    }
}
