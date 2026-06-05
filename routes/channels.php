<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Make sure this is at the top of the file
Broadcast::routes(['middleware' => ['web', 'auth']]);

// Your channels
Broadcast::channel('notifications.{id}', function ($user, $id) {
    // \Log::info("Channel auth check", [
    //     'authenticated_user_id' => $user->id,
    //     'requested_channel_id' => $id,
    //     'match' => (int) $user->id === (int) $id
    // ]);
    return (int) $user->id === (int) $id;
});
