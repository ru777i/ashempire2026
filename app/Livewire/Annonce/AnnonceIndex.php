<?php

namespace App\Livewire\Annonce;

use App\Models\Annonce;
use Livewire\Component;

class AnnonceIndex extends Component
{
    public function render()
    {
        $annonces=Annonce::all();
        return view('livewire.annonce.annonce-index', compact('annonces'));
    }
}
