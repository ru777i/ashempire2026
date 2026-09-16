<?php

namespace App\Livewire;

use App\Models\Annonce;
use Livewire\Component;

class AnnonceManager extends Component
{

 public $titre, $description, $prix, $categorie;
    public $annonceId;

    protected $rules = [
        'titre' => 'required|min:3',
        'description' => 'required',
        'prix' => 'required|numeric',
        'categorie' => 'nullable|string'
    ];

    public function createAnnonce()
    {
        $this->validate();
        Annonce::create([
            'titre' => $this->titre,
            'description' => $this->description,
            'prix' => $this->prix,
            'categorie' => $this->categorie,
        ]);
        $this->reset();
    }

    public function editAnnonce($id)
    {
        $annonce = Annonce::findOrFail($id);
        $this->annonceId = $id;
        $this->titre = $annonce->titre;
        $this->description = $annonce->description;
        $this->prix = $annonce->prix;
        $this->categorie = $annonce->categorie;
    }

    public function updateAnnonce()
    {
        $this->validate();
        Annonce::find($this->annonceId)->update([
            'titre' => $this->titre,
            'description' => $this->description,
            'prix' => $this->prix,
            'categorie' => $this->categorie,
        ]);
        $this->reset();
    }

    public function deleteAnnonce($id)
    {
        Annonce::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.annonce-manager', [
            'annonces' => Annonce::latest()->paginate(10)
        ]);
    }
  
}
