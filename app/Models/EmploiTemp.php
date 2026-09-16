<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiTemp extends Model
{

    protected $fillable = [
        'sessionn_id',
        'module_id',
        'date',
        'jour',
        'type',
        'heureDebut',
        'heureFin',
        'salle_id',
        'formateur_id',
    ];
    public static function verifierLibre($salle, $heureDebut, $heureFin, $date, $jour, $excludeId = null)
    {
        $date = date('Y-m-d', strtotime($date));
        $heureDebut = date('H:i:s', strtotime($heureDebut));
        $heureFin = date('H:i:s', strtotime($heureFin));

        $query = static::where('salle_id', $salle)
            ->where('jour', $jour)
            ->where(function ($query) use ($heureDebut, $heureFin) {
                $query->whereBetween('heureDebut', [$heureDebut, $heureFin])
                    ->orWhereBetween('heureFin', [$heureDebut, $heureFin])
                    ->orWhere(function ($query) use ($heureDebut, $heureFin) {
                        $query->where('heureDebut', '<=', $heureDebut)
                            ->where('heureFin', '>=', $heureFin);
                    });
            });

        if ($excludeId) {
            $query->whereKeyNot($excludeId);
        }

        return !$query->exists();
    }

     public function seances(){
        return $this->hasMany(Seance::class,'emploi_temp_id');
     }
    public function sess()
    {
        return $this->belongsTo(Sessionn::class,'sessionn_id');
    }
    public function module()
    {
        return $this->belongsTo(Module::class,'module_id');
    }
    public function salle()
    {
        return $this->belongsTo(Salle::class,'salle_id');
    }
    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
    public function presences()
    {
        return $this->hasMany(Presences::class);
    }
}
