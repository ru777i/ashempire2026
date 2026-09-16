<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sessionn extends Model
{
    protected $fillable = [
        'code',
        'formation_id',
        'annee_id',
        'nom',
        'capacite',
        'dateDebut',
        'dateFin',
        'statut',
        'type',
        'anne_academique_id'

    ];

    public function casts()
    {
        return [
            'dateDebut' => 'date',
            'dateFin' => 'date',
        ];
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class,'session_modules')->withPivot(
            'formateur_id',
            'statut',
            'volumeHoraire'
        );
    }

    public function sessionnModule()
    {
        return $this->hasMany(sessionModule::class);
    }

    public function evaluations()
    {
        return $this->hasOne(Evaluation::class);
    }
    public function progressi()
    {
        return $this->hasMany(Mprogression::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function annee()
    {
        return $this->belongsTo(AnneAcademique::class, 'anne_academique_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function emploisTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

    public function formateurs()
    {
        return $this->belongsToMany(
            Formateur::class,
            'formateur_sessions'
        )
            ->withPivot([
                'module_id',
            ]);
    }
}
