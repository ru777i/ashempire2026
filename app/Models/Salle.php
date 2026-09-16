<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom',
        'capacite',
        'localisation',

    ];
     public function emploisTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

}
