<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'volumeHoraire',
        'formation_id',
        'formateur_id',
        'statut',


    ];

     public  function sessionns(){
        return $this->belongsToMany(Sessionn::class,'session_modules');
     }
     public function sessionModule(){
        return $this->hasMany(SessionModule::class);
     }
    public function pro(){
        return $this->hasOne(Mprogression::class);
    }
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
    public function sessionn(){
        return $this->belongsTo(Sessionn::class);
    }
    public function formateurs(){
        return $this->belongsToMany(Formateur::class,'fomateur_modules');
    }
public function formateur(){
        return $this->belongsTo(Formateur::class);
    }
    public function formateurModule()
    {
        return $this->hasMany(FormateurModule::class);
    }
    public function emploiTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function emploisTemps()
    {
        return $this->hasMany(EmploiTemp::class);
    }

    // public function progressions()
    // {
    //     return $this->hasMany(Progr::class);
    // }

    public function resultats()
    {
        return $this->hasMany(ResultatModule::class);
    }

    // public function documents()
    // {
    //     return $this->hasMany(Document::class);
    // }

    // public function formateurs()
    // {
    //     return $this->belongsToMany(
    //         Utilisateur::class,
    //         'affectation_formateurs'
    //     )
    //     ->withPivot([
    //         'session_formation_id',
    //         'date_debut',
    //         'date_fin'
    //     ]);
    // }

}
