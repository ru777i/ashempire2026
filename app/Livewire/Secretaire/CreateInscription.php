<?php

namespace App\Livewire\Secretaire;

use App\Actions\Teams\CreateTeam;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\Apprenant as ApprenantModel;
use App\Models\Diplome;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Inscription as InscriptionModel;
use App\Models\Mprogression;
use App\Models\Progression;
use App\Models\ProgressionApprenantModule;
use App\Models\Sessionn;
use App\Models\User as UserModel;
use Flux\Flux;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateInscription extends Component
{
    use PasswordValidationRules, ProfileValidationRules;

    use \Livewire\WithFileUploads;
    public $nom = '';

    public $modules;
    public $prenom = '';
    public $dateNaissance = '';
    public $sexe = '';
    public $diplomes = [];
    public $statut;
    public $sessionn_id = '';
    public $formation_id = '';
    public $email = '';
    public $phone = '';
    public $selectedModules = [];
    public $id = '';

    public function mount($id = null)
    {
        if ($id != null) {
            $inscription = InscriptionModel::findOrfail($id);
            $this->id = $inscription->id;
            $this->statut = $inscription->statut;
            $this->nom = $inscription->aprenant->user->name;
            $this->prenom = $inscription->aprenant->prenom;
            $this->selectedModules = Progression::where('inscription_id', $inscription->id)->pluck('module_id');
            $this->dateNaissance = $inscription->aprenant->dateNaissance;
            $this->sexe = $inscription->aprenant->sexe;
            $this->email = $inscription->aprenant->user->email;
            $this->phone = $inscription->aprenant->user->telephone;
            $this->formation_id = $inscription->sessionn->formation->id;
            $this->sessionn_id = $inscription->sessionn->id;
        }
    }
    public function modifier($id)
    {
        return redirect(route('ajouterInscription', $id));
    }



    public function delete($id)
    {
        Inscription::destroy($id);
    }

    public function voir($id)
    {
        return redirect(route('createNote'));
    }

    public function inscrire()
    {
        $validated = $this->validate([
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'sexe' => ['required', 'string', 'in:M,F'],
            'statut' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => $this->id ? ['required', 'email', Rule::unique('users')->ignore(InscriptionModel::findOrFail($this->id)->aprenant->user_id)] : ['required', 'email', Rule::unique('users')],
            'dateNaissance' => ['required', 'date'],
            'diplomes' => ['array'],
            'formation_id' => ['required', 'exists:formations,id', ' integer'],
            'sessionn_id' => ['required', 'exists:sessionns,id'],
            'selectedModules' => ['array'],
        ]);

        DB::beginTransaction();
        if (empty($this->id)) {
            # code...

            try {
                $user = UserModel::create([
                    'email' => $validated['email'],
                    'name' => $validated['nom'],
                    'telephone' => $validated['phone'],
                    'role' => 'apprenant',
                    'adresse' => '',
                    'password' => Str::random(12),
                ]);

               (new CreateTeam)->handle($user, $user->name . "'s Team", isPersonal: true);



                $apprenant = ApprenantModel::create([
                    'prenom' => $validated['prenom'],
                    'sexe' => $validated['sexe'],
                    'dateNaissance' => $validated['dateNaissance'],
                    'user_id' => $user->id,
                ]);

                $sessionns = Sessionn::findOrFail($validated['sessionn_id']);

                $inscription = InscriptionModel::create([
                    'apprenant_id' => $apprenant->id,
                    'sessionn_id' => $sessionns->id,
                    'dateInscription' => now()->toDateString(),
                    'statut' => $validated['statut'],
                    'montantTotal' => 0,
                ]);
                $apprenant->matricule = 'AP' . $apprenant->id . '-' .  Str::upper(Str::substr($sessionns->formation->nom, 0, 4));
                $apprenant->save();
                $apprenant->user->update(['password' => ($apprenant->matricule)]);


                if ($validated['selectedModules']) {


                    foreach ($validated['selectedModules'] as $moduleId) {
                        // $sessionns->modules()->sync($validated['selectedModules']);
                        Progression::updateOrCreate(
                            [
                                'inscription_id' => $inscription->id,
                                'module_id' => $moduleId
                            ],
                            [
                                'sessionn_id' => $validated['sessionn_id'],
                                // 'statut' => $validated['statut'],
                            ]
                        );
                    }
                    Progression::where('inscription_id', $inscription->id)->whereNotIn('module_id', $validated['selectedModules'])->delete();
                }
                $Path = [];
                if ($this->diplomes) {
                    foreach ($this->diplomes as $diplome) {
                        $path = $diplome->store('diplomes', 'public');
                        try {
                            Diplome::create(['path' => $path, 'apprenant_id' => $apprenant->id]);
                            Flux::toast('success', 'Diplome ajouté avec succès.');
                        } catch (\Throwable $th) {
                            Flux::toast(variant: 'error', text: 'Impossible d’enregistrer le diplome : ' . $th->getMessage());
                        }
                    }
                }



                DB::commit();
                $this->reset(['nom', 'prenom', 'dateNaissance', 'sexe', 'diplomes', 'statut', 'sessionn_id', 'email', 'phone', 'selectedModules']);
                Flux::toast(variant: 'success', text: 'Apprenant créé avec succès.');
            } catch (\Throwable $th) {
                DB::rollBack();
                Flux::toast(variant: 'error', text: 'Impossible d’enregistrer l’inscription : ' . $th->getMessage());
            }
        } else {
            try {
                InscriptionModel::findOrFail($this->id)->aprenant->user()->update([
                    'email' => $validated['email'],
                    'name' => $validated['nom'],
                    'telephone' => $validated['phone'],
                    'role' => 'apprenant',
                    'adresse' => '',
                ]);

                InscriptionModel::findOrFail($this->id)->aprenant()->update([
                    'prenom' => $validated['prenom'],
                    'sexe' => $validated['sexe'],
                    'dateNaissance' => $validated['dateNaissance'],
                    'user_id' => InscriptionModel::findOrFail($this->id)->aprenant->user->id,
                ]);

                $session = Sessionn::findOrFail($validated['sessionn_id']);

                InscriptionModel::findOrFail($this->id)->update([
                    'apprenant_id' => InscriptionModel::findOrFail($this->id)->aprenant->id,
                    'sessionn_id' => $session->id,
                    'dateInscription' => now()->toDateString(),
                    'statut' => $validated['statut'],
                    'montantTotal' => 0,

                ]);
                $dtel = InscriptionModel::findOrFail($this->id);
                $dtel->aprenant->user()->update([
                    'password' => ($dtel->aprenant->matricule),
                ]);
                //    $dtel->save();
                // $apprenant =InscriptionModel::findOrFail($this->id)->aprenant;
                // $apprenant->matricule = 'AP' . $apprenant->id . '-' .  Str::upper(Str::substr($session->formation->nom, 0, 4));
                // $apprenant->save();

                // if ($validated['selectedModules']) {
                //     foreach ($validated['selectedModules'] as $moduleId) {
                //          InscriptionModel::findOrFail($this->id)->progressions()->update([
                //             'inscription_id' => $this->id,
                //             'module_id' => $moduleId,
                //             'statut' => 'NON_COMMENCE',
                //         ]);
                //     }
                // }
                if ($validated['selectedModules']) {
                    foreach ($validated['selectedModules'] as $moduleId) {
                        //  $session->modules()->sync($validated['selectedModules']);
                        Progression::updateOrCreate(
                            [
                                'inscription_id' => $this->id,
                                'module_id' => $moduleId
                            ],

                            [
                                'sessionn_id' => $validated['sessionn_id'],
                                'statut' => 'EN_COURS',
                            ]
                        );
                    }
                    Progression::where('inscription_id', $this->id)->whereNotIn('module_id', $validated['selectedModules'])->delete();
                }
                $Path = [];
                if ($this->diplomes) {
                    foreach ($this->diplomes as $diplome) {
                        $path = $diplome->store('diplomes', 'public');
                        try {
                            Diplome::create(['path' => $path, 'apprenant_id' => InscriptionModel::findOrFail($this->id)->aprenant->id]);
                            Flux::toast('success', 'Diplome ajouté avec succès.');
                        } catch (\Throwable $th) {
                            Flux::toast(variant: 'error', text: 'Impossible d’enregistrer le diplome : ' . $th->getMessage());
                        }
                    }
                }



                DB::commit();
                $this->reset(['nom', 'prenom', 'dateNaissance', 'sexe', 'diplomes', 'statut', 'sessionn_id', 'email', 'phone', 'selectedModules']);
                Flux::toast(variant: 'success', text: 'Apprenant mise ajour avec succès.');
            } catch (\Throwable $th) {
                DB::rollBack();
                Flux::toast(variant: 'error', text: 'Impossible de mettre a jour l’inscription : ' . $th->getMessage());
            }
        }
    }

    public function render()
    {
        $inscriptions = InscriptionModel::with('sessionn')->get();
        $sessions = [];
        if (!empty($this->sessionn_id)) {
            $this->modules = Sessionn::findOrFail($this->sessionn_id)->formation->modules;
        } else {
            $this->modules = collect();
        }

        $statuts = [
            'EN ATTENTE',
            'EN COURS',
            'TERMINEE',
            'VALIDEE',
            'ANNULEE',
            'SUSPENDUE'
        ];
        if ($this->formation_id) {
            $sessions  = Sessionn::where('formation_id', $this->formation_id)->get();
        }
        $formations = Formation::all();

        return view('livewire.secretaire.create-inscription', compact('inscriptions', 'sessions', 'statuts', 'formations'));
    }
}
