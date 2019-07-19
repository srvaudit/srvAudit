<?php

namespace App\Social;

use App\User;
use Laravel\Socialite\Facades\Socialite;

class GithubServiceProvider extends AbstractServiceProvider
{
   /**
     *  Handle Facebook response
     *
     *  @return Illuminate\Http\Response
     */
    public function handle()
    {
        $user = Socialite::driver('github')->user();

        $existingUser = User::whereEmail($user->email)->orWhere('settings->github_id', $user->id)->first();

        if ($existingUser) {
            $settings = $existingUser->settings;

            if (! isset($settings['github_id'])) {
                $settings['github_id'] = $user->id;
                $existingUser->settings = $settings;
                $existingUser->save();
            }

            return $this->login($existingUser);
        }

        $newUser = $this->register([
            'name' => $user->user['name'],
            'email' => $user->email,
            'settings' => [
                'github_id' => $user->id,
            ]
        ]);

        return $this->login($newUser);
    }
}
