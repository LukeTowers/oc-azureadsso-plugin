<?php
namespace Luketowers\Azureadsso\Controllers;

use Backend\Models\User;
use Illuminate\Http\RedirectResponse as RedirectResponseAlias;
use Laravel\Socialite\Facades\Socialite;
use Luketowers\Azureadsso\Classes\UserHelper;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthController
{
    public function handleOauthResponse(): RedirectResponseAlias
    {
        $azureUser = Socialite::driver('azure')->user();
        $authUser = $this->findOrCreateUser($azureUser);
        auth()->login($authUser, true);

        return redirect(\Backend::url());
    }

    protected function findOrCreateUser(SocialiteUser $azureUser): User
    {
        $authUser = \Backend\Models\User::where('azure_id', $azureUser->id)->first();

        if ($authUser) {
            return $authUser;
        }

        return UserHelper::getAuthUser($azureUser);
    }
}
