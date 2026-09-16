<?php

namespace App\Livewire\Recourspublic;

use App\Models\Recour;
use Flux\Flux;
use Illuminate\Support\Traits\ReflectsClosures;
use Livewire\Component;

class CreateCreate extends Component
{
    public $inscription_id = '';
    public  $apprenant_id = '';
    public   $type = '';
    public  $objet = '';
    public  $description = '';
    public   $statut = '';
    public $reponse = '';
    public  $formateur_id = '';
    public  $traite_par = '';
    public   $date_traitement = '';
    public $id = '';

    public  function mount($recour = null)
    {
        if ($recour != null) {
            $this->inscription_id = $recour->inscription_id;
            $this->apprenant_id = $recour->apprenant_id;
            $this->type = $recour->type;
            $this->objet = $recour->objet;
            $this->description = $recour->description;
            $this->statut = $recour->statut;
            $this->reponse = $recour->reponse;
            $this->formateur_id = $recour->formateur_id;
            $this->traite_par = $recour->traite_par;
            $this->date_traitement = $recour->date_traitement;
            $this->id = $recour->id;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'inscription_id' => ['required', 'numeric', 'exists:inscriptions,id'],
            'apprenant_id' => ['required', 'exists:apprenants,id'],
            'type' => ['required', 'string', 'max:20'],
            'objet' => [' required', 'string', 'max:30'],
            'description' => ['required', 'string', ' max:400'],
            'reponse' => ['nullable'],
            'formateur_id' => ['required', 'exits:users,id'],
            'traite_par' => ['nullable', 'exists:users,id'],
            'date_traitement' => ['nulluble', 'date'],
        ]);

        try {
            if ($this->id) {
                Recour::create($validated);
                Flux::toast(variant: 'success', text: ' Recours envoyer ', heading: 'Envoyer');
            } else {
                Recour::findOrFail($this->id)->update($validated);
                Flux::toast(variant: 'success', text: ' Recours mise a jour ', heading: 'Envoyer');
            }
        } catch (\Throwable $th) {
            Flux::toast(variant: 'danger', text: ' Recours non envoyer ' . $th->getMessage(), heading: 'Erreur');
        }
    }
    public function delete($id = null)
    {
        if ($id != null) {
            Recour::destroy($id);
        }
    }


    public function render()
    {
        return view('livewire.recours.create-create');
    }
}
