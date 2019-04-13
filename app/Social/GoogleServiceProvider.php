<?php

namespace App\Social;

use App\User;
use Laravel\Socialite\Facades\Socialite;

class GoogleServiceProvider extends AbstractServiceProvider
{
   /**
     *  Handle Facebook response
     *
     *  @return Illuminate\Http\Response
     */
    public function handle()
    {
        $user = Socialite::driver('google')->user();

        $existingUser = User::whereEmail($user->email)->orWhere('settings->google_id', $user->id)->first();

        if ($existingUser) {
            $settings = $existingUser->settings;

            if (! isset($settings['google_id'])) {
                $settings['google_id'] = $user->id;
                $existingUser->settings = $settings;
                $existingUser->save();
            }

            return $this->login($existingUser);
        }

        $newUser = $this->register([
            'name' => $user->name,
            'email' => $user->email,
            'settings' => [
                'google_id' => $user->id,
            ]
        ]);

        return $this->login($newUser);
    }
}
