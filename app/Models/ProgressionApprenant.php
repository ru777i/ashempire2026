<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressionApprenant extends Model
{
    protected $fillable = [
        'inscription_id',
        'module_id',
        'pourcentage',
        'statut',
        'commentaire',
    ];

public function inscription(){
    return $this->belongsTo(Inscription::class);
}

public function module(){
    return $this->belongsTo(Module::class);
}






}
