<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class AnneAcademique extends Model
{
    protected $fillable = [
        'libelle',
        'dateDebut',
        'dateFin',
        'statut'
    ];
    #[Override]
    public function casts()
    {
        return [
            'dateDebut' => 'date',
            'dateFin' => 'date',
        ];
    }
     public function sessions()
    {
        return $this->hasMany(Sessionn::class,'anne_academique_id');
    }

}
