<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Presences extends Model
{
    protected $fillable = [
        'inscription_id',
        'emploi_temp_id',
        'seance_id',
        'date_seance',
        'datePresence',
        'statut',
        'session_enregistrement_id',
        'numero_enregistrement',
        'observations',
        'formateur_id'
    ];

    // Constantes pour les statuts
    const PRESENT = 'present';
    const ABSENT = 'absent';
    const RETARD = 'retard';
    const JUSTIFIE = 'justifie';

    protected $casts = [
        'date_seance' => 'date',
        'datePresence' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function emploiTemps()
    {
        return $this->belongsTo(EmploiTemp::class, 'emploi_temp_id');
    }
    public function seance(){
        return $this->hasOne(Seance::class);
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    public function apprenant()
    {
        return $this->hasOneThrough(
            Apprenant::class,
            Inscription::class,
            'id',
            'id',
            'inscription_id',
            'apprenant_id'
        );
    }

    public static function getStatuts()
    {
        return [
            self::PRESENT => 'Présent',
            self::ABSENT => 'Absent',
            self::RETARD => 'Retard',
            self::JUSTIFIE => 'Justifié',
        ];
    }

    public function isPresent()
    {
        return $this->statut === self::PRESENT;
    }

    public function isAbsent()
    {
        return $this->statut === self::ABSENT;
    }

    /**
     * Obtenir tous les enregistrements pour une séance (emploi de temps)
     */
    public static function getSessionsEnregistrement($emploiTempId)
    {
        return self::where('emploi_temp_id', $emploiTempId)
            ->distinct()
            ->pluck('session_enregistrement_id', 'numero_enregistrement')
            ->sort()
            ->reverse();
    }

    /**
     * Obtenir le dernier enregistrement pour une séance ET date
     */
    public static function getLatestSession($emploiTempId, $dateSeance = null)
    {
        $query = self::where('emploi_temp_id', $emploiTempId);

        if ($dateSeance) {
            $query->where('date_seance', $dateSeance);
        }

        return $query->orderByDesc('numero_enregistrement')->first();
    }

    /**
     * Obtenir les absences pour un enregistrement spécifique ET date
     */
    public static function getBySession($emploiTempId, $sessionId, $dateSeance = null)
    {
        $query = self::where('emploi_temp_id', $emploiTempId)
            ->where('session_enregistrement_id', $sessionId);

        if ($dateSeance) {
            $query->where('date_seance', $dateSeance);
        }

        return $query->with('inscription.aprenant.user', 'formateur.user')->get();
    }

    /**
     * Obtenir les dates pour lesquelles des enregistrements existent
     */
    public static function getDatesSeance($emploiTempId)
    {
        return self::where('emploi_temp_id', $emploiTempId)
            ->distinct()
            ->orderByDesc('date_seance')
            ->pluck('date_seance')
            ->unique();
    }

    /**
     * Obtenir les enregistrements pour une date spécifique
     */
    public static function getByDate($emploiTempId, $dateSeance)
    {
        return self::where('emploi_temp_id', $emploiTempId)
            ->where('date_seance', $dateSeance)
            ->distinct()
            ->orderByDesc('numero_enregistrement')
            ->get(['numero_enregistrement', 'session_enregistrement_id', 'created_at', 'formateur_id'])
            ->toArray();
    }
}
