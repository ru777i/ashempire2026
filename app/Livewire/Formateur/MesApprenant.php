<?php

namespace App\Livewire\Formateur;

use App\Models\Inscription;
use App\Models\SessionModule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MesApprenant extends Component
{
    public $apprenants = [];
    public $search = '';
    public $sessionId = '';
    public $sessionModules = [];
    public function render()
    {
        $this->sessionModules = SessionModule::where('formateur_id', Auth::user()->formateur->id)->get()->unique('sessionn_id');

        $tab = SessionModule::where('formateur_id', Auth::user()->formateur->id)->pluck('sessionn_id')->unique('sessionn_id');
           if ($this->sessionId) {
        $inscriptions = Inscription::where('sessionn_id', $this->sessionId);
          
        }
        else{
        $inscriptions = Inscription::whereIn('sessionn_id', $tab);
        }
        if ($this->search) {
            $inscriptions->whereRelation('aprenant', 'prenom', 'like', '%' . $this->search . '%')
                ->orwhereHas('aprenant', function ($q) {
                    $q->whereRelation('user', 'name', 'like', '%' . $this->search . '%');
                });
        }
     
        $inscriptions = $inscriptions->get();

        $this->apprenants = $inscriptions->map(function ($q) {
            return $q->aprenant;
        });

        return view('livewire.formateur.mes-apprenant');
    }
}
