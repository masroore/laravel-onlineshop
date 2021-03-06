<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use Laravel\Socialite\Contracts\User as ProviderUser;
use App\SocialAccount;
use App\User;

/**
 * Description of SocialAccountService
 *
 * @author NTHanh
 */
class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialAccount::whereProvider('facebook')
                ->whereProviderUserId($providerUser->getId())
                ->first();

        if ($account)
        {
            return $account->user;
        }
        else
        {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user)
            {

                $user = User::create([
                            'email' => $providerUser->getEmail(),
                            'name' => $providerUser->getName(),
                            'activated' => true,
                            'avatar'    => $providerUser->getAvatar()
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }

}
