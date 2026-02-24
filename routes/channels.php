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

Broadcast::channel('App.Models.Administrator.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal para notificaciones de administradores
Broadcast::channel('admin.{adminId}', function ($user, $adminId) {
    return (int) $user->id === (int) $adminId;
});

// Canal para notificaciones generales de usuarios (si se necesita)
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return true; // Los administradores pueden ver notificaciones de cualquier usuario
});

