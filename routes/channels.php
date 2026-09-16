<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Apprenant.{id}', function ($apprenant, $id) {
    return (int) $apprenant->id === (int) $id;
});
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
