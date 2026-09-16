<?php

namespace App\Livewire\Secretaire;

use App\Models\Certificat;
use App\Models\Inscription;
use App\Notifications\ResultatPublieNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class CertificatManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filtreStatut = 'eligibles'; // eligibles | generes | tous

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltreStatut()
    {
        $this->resetPage();
    }

    /**
     * Genere l'attestation ou le certificat pour une inscription terminee et admise.
     */
    public function generer(int $inscriptionId, string $type = 'attestation')
    {
        $inscription = Inscription::with([
            'aprenant.user',
            'sessionn.formation',
            'resultatFormation',
        ])->findOrFail($inscriptionId);

        if (!$inscription->resultatFormation) {
            Flux::toast(
                variant: 'error',
                text: "Aucun résultat final n'a été saisi pour cet apprenant."
            );

            return;
        }

        if ($inscription->certificat) {
            Flux::toast(
                variant: 'error',
                text: 'Un document existe déjà pour cette inscription.'
            );

            return;
        }

        $resultat = $inscription->resultatFormation;

        $numero = Certificat::genererNumero($type);

        $pdf = Pdf::loadView('pdf.certificat', [
            'inscription' => $inscription,
            'resultat' => $resultat,
            'numero' => $numero,
            'type' => $type,
            'dateDelivrance' => now(),
        ])->setPaper('a4', 'landscape');

        $nomFichier = "certificats/{$numero}.pdf";
        Storage::disk('local')->put($nomFichier, $pdf->output());

        $certificat = Certificat::create([
            'inscription_id' => $inscription->id,
            'numero' => $numero,
            'type' => $type,
            'mention' => $resultat->mention,
            'moyenneGenerale' => $resultat->moyenneGenerale,
            'dateDelivrance' => now(),
            'cheminFichier' => $nomFichier,
            'genere_par' => Auth::id(),
        ]);

        if ($inscription->aprenant?->user) {
            $inscription->aprenant->user->notify(new ResultatPublieNotification($certificat));
        }

        Flux::toast(
            variant: 'success',
            text: "Document {$numero} généré avec succès."
        );
    }

    public function telecharger(int $certificatId)
    {
        $certificat = Certificat::findOrFail($certificatId);

        if (!$certificat->cheminFichier || !Storage::disk('local')->exists($certificat->cheminFichier)) {
            Flux::toast(
                variant: 'error',
                text: 'Le fichier est introuvable.'
            );

            return;
        }

        return response()->streamDownload(
            fn () => print (Storage::disk('local')->get($certificat->cheminFichier)),
            "{$certificat->numero}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    public function supprimer(int $certificatId)
    {
        $certificat = Certificat::findOrFail($certificatId);

        if ($certificat->cheminFichier) {
            Storage::disk('local')->delete($certificat->cheminFichier);
        }

        $certificat->delete();

        Flux::toast(
            variant: 'success',
            text: 'Document supprimé.'
        );
    }

    public function render()
    {
        $query = Inscription::query()
            ->with(['aprenant.user', 'sessionn.formation', 'resultatFormation', 'certificat']);

        if ($this->search !== '') {
            $query->whereHas('aprenant.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })->orWhereHas('aprenant', function ($q) {
                $q->where('matricule', 'like', "%{$this->search}%");
            });
        }

        if ($this->filtreStatut === 'eligibles') {
            $query->whereHas('resultatFormation')->whereDoesntHave('certificat');
        } elseif ($this->filtreStatut === 'generes') {
            $query->whereHas('certificat');
        }

        $inscriptions = $query->latest()->paginate(10);

        return view('livewire.secretaire.certificat-manager', [
            'inscriptions' => $inscriptions,
        ]);
    }
}
