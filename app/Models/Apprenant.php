<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Apprenant extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'prenom',
        'matricule',
        'sexe',
        'dateNaissance',
        'user_id'

    ];
    public function dipomes(){
    return $this->hasMany(Diplome::class);
    }
     public function user() {
        return $this->belongsTo(User::class);
    }

    public function inscriptions() {
        return $this->hasMany(Inscription::class);
    }
    //
}
