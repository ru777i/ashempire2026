<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Notifications\Notification;

class PaiementEnregistreNotification extends Notification
{
    public function __construct(public Paiement $paiement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $inscription = $this->paiement->inscription;

        return [
            'titre' => 'Paiement enregistré',
            'message' => "Un paiement de {$this->paiement->montant} a été enregistré pour votre formation.",
            'solde_restant' => $inscription?->soldeRestant(),
            'inscription_id' => $this->paiement->inscription_id,
            'icone' => 'banknotes',
        ];
    }
}
