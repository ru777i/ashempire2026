<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    protected $fillable = [
        'inscription_id',
        'numero',
        'type',
        'mention',
        'moyenneGenerale',
        'dateDelivrance',
        'cheminFichier',
        'genere_par',
    ];

    protected function casts(): array
    {
        return [
            'dateDelivrance' => 'date',
        ];
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function genePar()
    {
        return $this->belongsTo(User::class, 'genere_par');
    }

    /**
     * Genere un numero unique de certificat, ex: ATT-2026-000123
     */
    public static function genererNumero(string $type = 'attestation'): string
    {
        $prefixe = $type === 'certificat' ? 'CERT' : 'ATT';
        $annee = now()->format('Y');

        $dernier = static::where('numero', 'like', "{$prefixe}-{$annee}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = 1;

        if ($dernier) {
            $dernieresChiffres = (int) substr($dernier->numero, -6);
            $sequence = $dernieresChiffres + 1;
        }

        return sprintf('%s-%s-%06d', $prefixe, $annee, $sequence);
    }
}
