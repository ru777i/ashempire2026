<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Annonce extends Model
{
    protected $fillable = [
        'title',
        'description',
        'statut',
        'date'
    ];
}
