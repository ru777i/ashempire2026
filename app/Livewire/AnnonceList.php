<?php

namespace App\Livewire;

use App\Models\Annonce;
use Livewire\Component;
use Livewire\WithPagination;

class AnnonceList extends Component
{
    use WithPagination;

    public $search = '';
    public $categorie = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategorie()
    {
        $this->resetPage();
    }

    public function render()
    {
        $annonces = Annonce::query()
            ->when($this->search, fn($q) => $q->where('titre', 'like', "%{$this->search}%"))
            ->when($this->categorie, fn($q) => $q->where('categorie', $this->categorie))
            ->latest()
            ->paginate(9);

        return view('livewire.annonce-list', compact('annonces'));
    }
}
