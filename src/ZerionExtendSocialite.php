<?php

namespace lonesta\SocialiteProviders\Zerion;

use SocialiteProviders\Manager\SocialiteWasCalled;

class ZerionExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'zerion', __NAMESPACE__ . '\Provider'
        );
    }
}
