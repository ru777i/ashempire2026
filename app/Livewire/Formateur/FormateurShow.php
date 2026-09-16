<?php

namespace App\Livewire\Formateur;

use App\Models\Formateur;
use Flux\Flux;
use Livewire\Component;

class FormateurShow extends Component
{
    public $name;
    public $search='';
    public $email;
    public $telephone;
    public $specialite;
    public $id;
    public function delete($id)
    {
        $formateur = Formateur::find($id);
        if ($formateur) {
            $user = $formateur->user;
            $formateur->delete();
            if ($user) {
                $user->delete();
            }
            Flux::toast('Formateur supprimé avec succès !', 'success');
        } else {
            Flux::toast('Formateur introuvable.', 'error');
        }
    }
    public function mount($id = null)
    {
        if ($id) {
            $formateur = Formateur::find($id);
            if ($formateur) {
                $this->id = $formateur->id;
                $this->name = $formateur->user->name;
                $this->email = $formateur->user->email;
                $this->telephone = $formateur->user->telephone;
                $this->specialite = $formateur->specialite;
            }
        } else {
            $this->reset();
        }

    }
    public function saveFormateur()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
        ]);
  if ($this->id) {
            $formateur = Formateur::find($this->id);
            if ($formateur) {
                $formateur->user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'telephone' => $this->telephone,
                ]);
                $formateur->update([
                    'specialite' => $this->specialite,
                ]);
            }
            Flux::toast('Formateur mis à jour avec succès !', 'success');
        } else {
        $user = \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'password' => bcrypt('defaultpassword'), // You might want to handle password differently
        ]);

        Formateur::create([
            'user_id' => $user->id,
            'specialite' => $this->specialite,
        ]);

        Flux::toast('Formateur ajouté avec succès !', 'success');

        // Reset form fields
        $this->reset(['name', 'email', 'telephone', 'specialite']);

        }
        // Optionally, you can emit an event or flash a message here
    }
    public function render()
    {
          $query = Formateur::query();
        if ($this->search) {
           $query->whereRelation('user', 'name','like','%'.$this->search.'%')
           ->orwhereRelation('user', 'email','like','%'.$this->search.'%')
           ->orwhereRelation('user', 'telephone','like','%'.$this->search.'%')
           ->orWhere('specialite','like','%'.$this->search.'%');



        }
        $formateurs= $query->paginate(10);

        return view('livewire.formateur.formateur-show', compact('formateurs'));
    }
}
