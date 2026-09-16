<?php

namespace App\Livewire\Formateur;

use App\Models\Formateur;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FormateurCreate extends Component
{

    public $nomFormateur;
    public $email;
    public $telephone;
    public $role;
    public $specialite;
    public $id;
    public function delete($idd)
    {
        $formateur = Formateur::find($idd);
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
                $this->nomFormateur = $formateur->user->name;
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
            'nomFormateur' => 'required|string|max:255',
            'email' =>  $this->id ? ['required', 'email', Rule::unique('users')->ignore(Formateur::find($this->id)->user_id)] : ['required', 'email', Rule::unique('users')],
            'telephone' => 'required|string|max:20',
            'specialite' => 'required|string|max:255',
        ]);
        if ($this->id) {
            $formateur = Formateur::find($this->id);
            if ($formateur) {
                $formateur->user->update([
                    'name' => $this->nomFormateur,
                    'email' => $this->email,
                    'telephone' => $this->telephone,
                ]);
                $formateur->update([
                    'specialite' => $this->specialite,
                ]);
            }
            Flux::toast(variant: 'success',text:'Formateur mis à jour avec succès !');
        } else {
            $user = \App\Models\User::create([
                'name' => $this->nomFormateur,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'password' => bcrypt('defaultpassword'), // You might want to handle password differently
            ]);

            Formateur::create([
                'user_id' => $user->id,
                'specialite' => $this->specialite,
            ]);

            Flux::toast(variant:'success',text:'Formateur ajouté avec succès !');

            // Reset form fields
            $this->reset(['name', 'email', 'telephone', 'specialite']);
        }
        // Optionally, you can emit an event or flash a message here
    }
    public function render()
    {
        return view('livewire.formateur.formateur-create');
    }
}
