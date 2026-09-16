<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Recour extends Model
{
    protected $fillable = [
        'inscription_id',
        'apprenant_id',
        'type',
        'objet',
        'description',
        'statut',
        'reponse',
        'formateur_id',
        'traite_par',
        'date_traitement',
    ];

    protected $casts = [
        'date_traitement' => 'datetime',
    ];

    /**
     * Inscription concernée par le recours.
     */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    /**
     * Apprenant ayant créé le recours.
     */
    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class, 'apprenant_id');
    }
    public function  formateur(){
        return $this->belongsTo(Formateur::class);
    }

    /**
     * Utilisateur ayant traité le recours.
     */
    public function agent()
    {
        return $this->belongsTo(Formateur::class, 'traite_par');
    }
}
