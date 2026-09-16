<?php

namespace App\Livewire\Formateur;

use App\Models\Inscription;
use App\Models\SessionModule;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Avancement extends Component
{
    public $sessionModules = [];

    public $progressions = [];




    public function render()
    {

        if (Auth::user()->role == 'apprenant') {
            $ids = Inscription::where('apprenant_id', Auth::user()->aprenant->id)->pluck('sessionn_id')->unique('sessionn_id');
            $this->sessionModules = SessionModule::whereIn('sessionn_id', $ids)->get();
        }
        $this->sessionModules = SessionModule::where('formateur_id', Auth::user()->formateur->id)->get();

        return view('livewire.formateur.avancement');
    }
}
