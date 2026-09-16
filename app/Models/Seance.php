<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Override;

class Seance extends Model
{
    protected $fillable = [
        'dateSeance',
        'heureDebut',
        'heureFin',
        'emploi_temp_id',
        'statut'
    ];
    #[Override]
    public function casts()
    {
        return [
            'dateSeance' =>'date'
        ];
    }


    public function emploiTemp(){
        return $this->belongsTo(EmploiTemp::class,'emploi_temp_id');
    }
    public function presences(){
        return  $this->belongsTo(Presences::class);
    }
}
