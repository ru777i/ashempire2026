<?php

namespace App\Notifications;

use App\Models\Presences;
use Illuminate\Notifications\Notification;

class AbsenceSignaleeNotification extends Notification
{
    public function __construct(public Presences $presence)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titre' => 'Absence enregistrée',
            'message' => 'Une absence a été enregistrée pour vous à la séance du '
                . optional($this->presence->date_seance)->format('d/m/Y') . '.',
            'presence_id' => $this->presence->id,
            'icone' => 'exclamation-triangle',
        ];
    }
}
