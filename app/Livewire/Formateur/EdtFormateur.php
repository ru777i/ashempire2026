<?php

namespace App\Livewire\Formateur;

use App\Models\EmploiTemp;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EdtFormateur extends Component
{
    public $seances = [];
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    public $timeSlots = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
      public function slotForTime($heure)
    {
        $normalized = substr($heure, 0, 5);

        return collect($this->timeSlots)->first(function ($slot) use ($normalized) {
            return $slot === $normalized;
        }) ?? $normalized;
    }
    public function render()
    {

        $this->seances = EmploiTemp::where('formateur_id', Auth::user()->formateur->id);
        return view('livewire.formateur.edt-formateur');
    }
}
