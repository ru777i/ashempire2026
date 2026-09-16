<?php

namespace App\Livewire\Apprenant;

use App\Models\Apprenant;
use App\Models\Inscription;
use App\Models\Presences as ModelsPresences;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Presences extends Component
{
    public $presences = [];
    public $presents;
    public $absences;
    public $retards;
    public $statuts  = ['absent', 'present', 'retard', 'justifie'];
    public $statut = '';
    public $justifiers;
    public function render()
    {
        if (Auth::check() &&  Auth::user()->role == 'apprenant') {
            $inscriptions = Inscription::where('apprenant_id', Auth::user()->apprenant->id)->pluck('id');
            $query = ModelsPresences::whereIn('inscription_id', $inscriptions);
            $this->absences  = ModelsPresences::whereIn('inscription_id', $inscriptions)->where('statut', 'absent')->get()->count();
            $this->retards  = ModelsPresences::whereIn('inscription_id', $inscriptions)->where('statut', 'retard')->get()->count();
            $this->justifiers  = ModelsPresences::whereIn('inscription_id', $inscriptions)->where('statut', 'justifie')->get()->count();
            $this->presents  = ModelsPresences::whereIn('inscription_id', $inscriptions)->where('statut', 'present')->get()->count();
            if ($this->statut) {
                $query->where('statut', $this->statut);
                # code...
            }

            $this->presences = $query->get();
        }
        return view('livewire.apprenant.presences');
    }
}
