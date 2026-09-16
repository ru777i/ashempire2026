<?php

namespace App\Livewire\Formateur;

use App\Models\Recour;
use Livewire\Component;
use Livewire\WithPagination;

class GestionRecour extends Component
{
use WithPagination;

public $search='';

public $filtre='';

public $selectedRecour;

public $showModal=false;

public $reponse='';

public $statut='';

public $total;

public $enAttente;

public $traites;

public $rejetes;
// mount()

// render()

// show()

// saveResponse()

// closeModal()

// updatedSearch()

// updatedFiltre()

// chargerStatistiques()
    public function render()
    {
        $recours =Recour::with([
'apprenant.user',
'inscription.sessionn',
'formateur'
])->paginate(20);
        return view('livewire.formateur.gestion-recour', compact('recours'));
    }
}
