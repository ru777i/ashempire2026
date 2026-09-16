<?php

namespace App\Livewire\Annonce;

use App\Models\Annonce;
use Flux\Flux;
use Illuminate\Auth\Events\Validated;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use Livewire\Component;

use function Laravel\Prompts\text;

class CreateAnnonce extends Component
{

    public $title = '';
    public $description = '';
    public $id = null;

    public function mount($annonce = null)
    {
        if ($annonce != null) {
            $this->id = $annonce->id;
            $this->title = $annonce->title;
            $this->description = $annonce->description;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => ['string', 'required', 'max:50'],
            'description' => ['string', 'required'],
        ]);
        try {
            if ($this->id != null) {
                Annonce::findOrFail($this->id)->update($validated);
                Flux::toast(variant: 'success', text: 'Annonce modifier',  heading: 'success');
            } else {
                Annonce::create($validated);
            }

            Flux::toast(variant: 'success', text: 'Annonce creer',  heading: 'success');
        } catch (\Throwable $th) {
            Flux::toast(variant: 'danger', text: 'une erreur est survenue' . $th->getMessage(), heading: 'Erreur');
        }
    }
    public function delete($id = null)
    {
        if ($id != null) {
            Annonce::destroy($id);
            Flux::toast(variant: 'danger', text: 'Annonce suprimer',  heading: 'success');
        }
    }

    public function render()
    {
        $annonces = Annonce::all();
        return view('livewire.annonce.create-annonce', compact('annonces'));
    }
}
