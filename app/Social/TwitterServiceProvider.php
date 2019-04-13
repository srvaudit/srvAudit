<?php

namespace App\Social;

use App\User;
use Laravel\Socialite\Facades\Socialite;

class TwitterServiceProvider extends AbstractServiceProvider
{
   /**
     *  Handle Facebook response
     *
     *  @return Illuminate\Http\Response
     */
    public function handle()
    {
        $user = Socialite::driver('twitter')->user();

        $existingUser = User::whereEmail($user->email)->orWhere('settings->twitter_id', $user->id)->first();

        if ($existingUser) {
            $settings = $existingUser->settings;

            if (! isset($settings['twitter_id'])) {
                $settings['twitter_id'] = $user->id;
                $existingUser->settings = $settings;
                $existingUser->save();
            }

            return $this->login($existingUser);
        }

        $newUser = $this->register([
            'name' => $user->name,
            'email' => $user->email,
            'settings' => [
                'twitter_id' => $user->id,
            ]
        ]);

        return $this->login($newUser);
    }
}
