<?php

namespace App\Livewire\Apprenant;

use App\Models\Certificat;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class MesCertificats extends Component
{
    public function telecharger(int $certificatId)
    {
        $certificat = Certificat::with('inscription.aprenant.user')->findOrFail($certificatId);

        // Sécurité : un apprenant ne peut télécharger que ses propres documents
        if ($certificat->inscription->aprenant->user_id !== Auth::id()) {
            abort(403);
        }

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

    public function render()
    {
        $apprenant = Auth::user()->apprenant;

        $certificats = Certificat::with(['inscription.sessionn.formation'])
            ->whereHas('inscription', function ($q) use ($apprenant) {
                $q->where('apprenant_id', $apprenant?->id);
            })
            ->latest('dateDelivrance')
            ->get();

        return view('livewire.apprenant.mes-certificats', [
            'certificats' => $certificats,
        ]);
    }
}
