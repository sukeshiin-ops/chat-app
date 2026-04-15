<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat-channel.{id}', function ($user, $id) {
    if ((int) $user->id === (int) $id) {   // matching login user_id  with channel id 
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
    return false;
});


Broadcast::channel('online-users', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
