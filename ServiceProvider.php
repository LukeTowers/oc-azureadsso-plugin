<?php

namespace LukeTowers\AzureADSSO;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SocialiteProviders\Azure\AzureExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, [
            AzureExtendSocialite::class, 'handle'
        ]);
    }
}
