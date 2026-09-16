<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'apprenant_id',
        'sessionn_id',
        'dateInscription',
        'statut',
    
        'montantTotal',
    ];



    // public function progressions()
    // {
    //     return $this->hasMany(ProgressionApprenantModule::class);
    // }
    public function aprenant()
    {
        return $this->belongsTo(Apprenant::class,'apprenant_id');
    }

    public function sessionn()
    {
        return $this->belongsTo(Sessionn::class);
    }

    // public function paiements()
    // {
    //     return $this->hasMany(Paiement::class);
    // }

    public function presences()
    {
        return $this->hasMany(Presences::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }

    public function resultatsModules()
    {
        return $this->hasMany(ResultatModule::class);
    }

    public function resultatFormation()
    {
        return $this->hasOne(ResultatFinal::class);
    }
    public function emploisTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

    public function certificat()
    {
        return $this->hasOne(Certificat::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function totalPaye(): float
    {
        return (float) $this->paiements()->where('statut', 'valide')->sum('montant');
    }

    public function soldeRestant(): float
    {
        return max(0, (float) $this->montantTotal - $this->totalPaye());
    }

    public function estSolde(): bool
    {
        return $this->soldeRestant() <= 0;
    }
}
