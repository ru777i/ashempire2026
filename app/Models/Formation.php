<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    //
    protected $fillable = [
        'code',
        'nom',
        'description',
        'volume_horaire',
        'statut'

    ];
    public function inscriptions()
{
    return $this->hasManyThrough(
        Inscription::class,
        Sessionn::class,
        'formation_id', // Clé étrangère sur la table sessionns
        'sessionn_id',  // Clé étrangère sur la table inscriptions
        'id',           // Clé primaire de formations
        'id'            // Clé primaire de sessionns
    );
}
    public function sessions(){
        return $this->hasMany(Sessionn::class);
    }
    public function modules(){
        return $this->hasMany(Module::class);
    }
}
