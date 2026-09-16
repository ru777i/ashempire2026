<?php

namespace App\Notifications;

use App\Models\Certificat;
use Illuminate\Notifications\Notification;

class ResultatPublieNotification extends Notification
{
    public function __construct(public Certificat $certificat)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titre' => 'Votre document est disponible',
            'message' => "Votre {$this->certificat->type} (n° {$this->certificat->numero}) est prêt au téléchargement.",
            'certificat_id' => $this->certificat->id,
            'icone' => 'document-text',
        ];
    }
}
