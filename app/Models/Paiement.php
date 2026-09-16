<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'inscription_id',
        'montant',
        'methode',
        'reference',
        'datePaiement',
        'statut',
        'note',
        'enregistre_par',
    ];

    protected function casts(): array
    {
        return [
            'datePaiement' => 'date',
        ];
    }

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    public static function getMethodes(): array
    {
        return [
            'espece' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'virement' => 'Virement bancaire',
            'cheque' => 'Chèque',
        ];
    }
}
