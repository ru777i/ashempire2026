<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    protected $fillable = [
        'module_id',
        'inscription_id',
        'statut',
        'sessionn_id',
        'pourcentage'
    ];

    public function progresModule(){
        return $this->belongsTo(Module::class,'module_id');
    }

    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }
}
