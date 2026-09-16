<?php

namespace App\Livewire\Presence;

use App\Models\EmploiTemp;
use App\Models\Formateur;
use App\Models\Presences;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedEmploiTempId = '';
    public $filterStatut = '';
    public $presents;
    public $absences;
    public $retards;
    public $presences = [];
    public $edt;
    public $selectedNumeroEnregistrement = '';
    public $enregistrements = [];

    public function updatedSelectedEmploiTempId()
    {
        $this->resetPage();
        $this->selectedNumeroEnregistrement = '';
        $this->loadEnregistrements();
    }

    public function updatedSelectedNumeroEnregistrement()
    {
        $this->resetPage();
        $this->loadPresences();
    }

    public function updatedFilterStatut()
    {
        $this->resetPage();
    }

    public function loadEnregistrements()
    {
        if (!$this->selectedEmploiTempId) {
            $this->enregistrements = [];
            return;
        }

        $this->enregistrements = Presences::where('emploi_temp_id', $this->selectedEmploiTempId)
            ->distinct()
            ->orderByDesc('numero_enregistrement')
            ->get(['numero_enregistrement', 'session_enregistrement_id', 'created_at', 'formateur_id'])
            ->toArray();
    }

    public function loadPresences()
    {
        if (!$this->selectedEmploiTempId) {
            $this->presences = [];
            return;
        }

        $query = Presences::where('emploi_temp_id', $this->selectedEmploiTempId)
            ->with('inscription.aprenant.user', 'formateur.user');

        if ($this->selectedNumeroEnregistrement) {
            $query->where('numero_enregistrement', $this->selectedNumeroEnregistrement);
        }

        if ($this->filterStatut) {
            $query->where('statut', $this->filterStatut);
        }
      

        $this->presences = $query->orderByDesc('numero_enregistrement')
            ->orderBy('id')
            ->get()
            ->toArray();
    }
  public $totalAppel;

    public function render()
    {
        $user = Auth::user();
        $formateur = Formateur::where('user_id', $user->id)->first();
        $query = Presences::query();
        $emploiTemps = [];
        if ($formateur) {
            $emploiTemps = EmploiTemp::where('formateur_id', $formateur->id)
                ->with('module', 'salle')
                ->orderBy('jour')
                ->orderBy('heureDebut')
                ->get();
        }
        if ($this->selectedEmploiTempId) {
            $this->loadEnregistrements();



            $query->where('emploi_temp_id', $this->selectedEmploiTempId)
                ->with('inscription.aprenant.user', 'formateur.user');

            if ($this->selectedNumeroEnregistrement) {
                $query->where('numero_enregistrement', $this->selectedNumeroEnregistrement);
            }

            if ($this->filterStatut) {
                $query->where('statut', $this->filterStatut);
            }

            $this->presences = $query->orderByDesc('numero_enregistrement')
                ->orderBy('id')
                ->get()
                ->toArray();
        }
          $this->presents=Presences::where('statut','present')->count();
          $this->absences=Presences::where('statut','absent')->count();
          $this->retards=Presences::where('statut','retard')->count();
          $this->totalAppel=Presences::all()->count();

        return view('livewire.presence.index', [
            'emploiTemps' => $emploiTemps,
            'statuts' => Presences::getStatuts(),
        ]);
    }
}
