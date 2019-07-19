<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('session.{sid}.commands', function ($user, $sid) {
    if ($session = App\Session::where('sid', '=', $sid)->first()) {
        if ($user->id === $session->user_id) {
            return $user->id === $session->user_id;
        } else {
            return false;
        }
    } else {
        return false;
    }
});
