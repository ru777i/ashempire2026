<?php

namespace App\Livewire\Presence;

use App\Models\EmploiTemp;
use App\Models\Inscription;
use App\Models\ModuleProgression;
use App\Models\Mprogression;
use App\Models\Presences;
use App\Models\Progression;
use App\Models\ProgressionApprenant;
use App\Models\ProgressionModule;
use App\Models\Seance;
use App\Models\SessionModule;
use App\Models\User;
use App\Notifications\AbsenceSignaleeNotification;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

use function Laravel\Prompts\text;

class Create extends Component
{
    public $selectedEmploiTempId = '';
    public $seances = [];
    public $emploi_temp_id;
    public $dateSeance;
    public $heureDebut;
    public $heureFin;
    public $statut = '';
    public $apprenants = [];
    public $presences = [];
    public $seanceId = '';
    public $statutsSeance = ['REALISEE', 'ANNULEE', 'REPORTEE'];
    public $statutsPresence = ['absent', 'present', 'retard', 'justifie'];


    public function enregistrerSeance()
    {
        $this->emploi_temp_id = $this->selectedEmploiTempId;
        $validated = $this->validate([
            // 'emploi_temp_id' => ['required'],
            'dateSeance' => ['date', 'required'],
            // 'heureDebut' => ['required', 'before:heureFin'],
            // 'heureFin' => ['required', 'after:heureDebut'],
            'statut' => ['required', 'in:REALISEE,ANNULEE,REPORTEE']
        ]);

        try {
            $edt = EmploiTemp::findOrFail($this->selectedEmploiTempId);
            $validated['heureDebut'] = $edt->heureDebut;
            $validated['heureFin'] = $edt->heureFin;
            $validated['emploi_temp_id'] = $this->selectedEmploiTempId;
            $seance = Seance::create($validated);
            Flux::toast(variant: 'success', text: 'Séance créée avec succès');
            $this->seanceId = $seance->id;
            $this->seances = Seance::where('emploi_temp_id', $this->selectedEmploiTempId)->get();
            $this->reset('dateSeance', 'heureDebut', 'heureFin', 'statut', 'emploi_temp_id');
            $idM = $edt->module_id;
            $idS = $edt->sessionn_id;

            $sessionM = SessionModule::where('module_id', $idM)->where('sessionn_id', $idS)->first();
            if ($sessionM) {
                $progressionEtu = Mprogression::where('sessionn_module_id', $sessionM->id)
                    ->where('sessionn_id', $idS)->first();
                $pro = progression::where('module_id', $idM)
                    ->where('sessionn_id', $idS)->first();
                $debut = Carbon::parse($validated['heureDebut']);
                $fin = Carbon::parse($validated['heureFin']);
                if ($progressionEtu) {
                    $duree = $debut->diffInMinutes($fin);
                    $progressionEtu->progre += $duree / 60;

                    $sessionM->progress += $duree / 60;
                    $sessionM->save();
                    $progressionEtu->save();
                }
                if ($pro) {
                    $duree = $debut->diffInMinutes($fin);
                    $pro->pourcentage += $duree / 60;
                    $pro->save();
                }
            }
        } catch (\Throwable $th) {
            Flux::toast(variant: 'error', text: 'Erreur: ' . $th->getMessage());
        }
    }
    #[On('update-apprenants')]
    public function updateApprenants($emploiTempId)
    {
        if ($emploiTempId) {
            $this->selectedEmploiTempId = $emploiTempId;
            $this->seances = Seance::where('emploi_temp_id', $emploiTempId)->get();
            $emploiTemp = EmploiTemp::findOrFail($emploiTempId);

            // Récupérer les inscriptions via la session
            $inscriptions = $emploiTemp->sess->inscriptions ?? collect();

            $this->apprenants = $inscriptions->map(function ($inscription) {
                return [
                    'id' => $inscription->id,
                    'matricule' => $inscription->aprenant->matricule ?? 'N/A',
                    'nom' => $inscription->aprenant->user->name ?? '',
                    'prenom' => $inscription->aprenant->prenom ?? ''
                ];
            })->toArray();

            // Initialiser le tableau presences
            $this->presences = array_map(function ($i) {
                return ['statut' => 'present', 'observations' => ''];
            }, range(0, count($this->apprenants) - 1));
        }
    }

    // public function updateStatut($index, $statut)
    // {
    //     if (isset($this->presences[$index])) {
    //         $this->presences[$index]['statut'] = $statut;
    //     }
    // }

    public function selectEmploiTemp()
    {


        // if ($pro) {
        //     # code...
        //             Flux::toast(variant:'error',text:'reussi');
        // }
        if ($this->selectedEmploiTempId) {
            $this->updateApprenants($this->selectedEmploiTempId);
        } else {
            $this->apprenants = [];
            $this->presences = [];
            $this->seances = [];
        }
    }
    public function selectSeance($id)
    {
        $this->chargerPresences($id);
    }
    public function updatedSeanceId()
    {
        $this->chargerPresences($this->seanceId);
    }
    public bool $modeEdition = false;

    public function chargerPresences($seanceId)
    {
        $this->seanceId = $seanceId;

        $presences = Presences::where('seance_id', $seanceId)
            ->get()
            ->keyBy('inscription_id');

        if ($presences->count()) {

            $this->modeEdition = true;

            foreach ($this->apprenants as $index => $apprenant) {

                if (isset($presences[$apprenant['id']])) {

                    $presence = $presences[$apprenant['id']];

                    $this->presences[$index] = [

                        'statut' => $presence->statut,

                        'observations' => $presence->observations

                    ];
                }
            }
        } else {

            $this->modeEdition = false;

            foreach ($this->apprenants as $index => $apprenant) {

                $this->presences[$index] = [
                    'statut' => 'present',
                    'observations' => ''
                ];
            }
        }
    }

    public function savePresences()
    {
        if (!$this->selectedEmploiTempId || empty($this->apprenants) || empty($this->seanceId)) {
            Flux::toast(variant: 'error', text: 'Veuillez sélectionner une séance d\'abord');
            return;
        }

        try {
            $formateur = User::findOrFail(Auth::user()->id)->formateur;
            $sessionId = Str::uuid();

            foreach ($this->apprenants as $index => $apprenant) {
                $presence = Presences::updateOrCreate(

                    [

                        'seance_id' => $this->seanceId,

                        'inscription_id' => $apprenant['id']

                    ],

                    [

                        'emploi_temp_id' => $this->selectedEmploiTempId,

                        'statut' => $this->presences[$index]['statut'],

                        'observations' => $this->presences[$index]['observations'],

                        'formateur_id' => $formateur->id,

                        'session_enregistrement_id' => $sessionId,

                        'numero_enregistrement' => 1

                    ]

                );

                if ($this->presences[$index]['statut'] === 'absent') {
                    $inscription = Inscription::with('aprenant.user')->find($apprenant['id']);

                    if ($inscription?->aprenant?->user) {
                        $inscription->aprenant->user->notify(new AbsenceSignaleeNotification($presence));
                    }
                }
                // ProgressionApprenant::updateOrCreate([
                //     'inscription_id',
                //     'module_id',
                //     'pourcentage'=,
                //     'statut' => $this->presences[$index]['statut']=='present'? 'effectue':'non effectue',
                // ]);
            }

            Flux::toast(variant: 'success', text: 'Présences enregistrées avec succès');
            $this->presences = [];
            $this->selectedEmploiTempId = '';
        } catch (\Throwable $th) {
            Flux::toast(variant: 'error', text: 'Erreur: ' . $th->getMessage());
        }
    }

    public function render()
    {
        if ($this->seanceId) {
            $this->chargerPresences($this->seanceId);
        }
        $formateur = User::findOrFail(Auth::user()->id)->formateur->id;
        $emploiTemps = EmploiTemp::where('formateur_id', $formateur)->with('module')->get();
        if ($this->selectedEmploiTempId) {
            $emploi = EmploiTemp::findOrFail($this->selectedEmploiTempId);
            $this->apprenants = $edt = Inscription::where('sessionn_id', $emploi->sessionn_id)->get();
            $this->seances = Seance::where('emploi_temp_id', $this->selectedEmploiTempId)->get();
            $this->emploi_temp_id = $this->selectedEmploiTempId;
        }
        return view('livewire.presence.create', [
            'emploiTemps' => $emploiTemps,
            'seances' => $this->seances,
            'apprenants' => $this->apprenants,
            'presences' => $this->presences,
            'statuts' => $this->statutsPresence,
            'statutsSeance' => $this->statutsSeance
        ]);
    }
}
