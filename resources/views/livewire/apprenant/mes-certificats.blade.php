<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Mes attestations & certificats</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Mes documents</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    @if ($certificats->isEmpty())
        <div
            class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-10 text-center text-zinc-400">
            Aucun document disponible pour le moment. Vos attestations apparaîtront ici une fois vos résultats
            validés par le centre.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($certificats as $certificat)
                <div
                    class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 p-5 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <flux:icon.document-text class="w-5 h-5 text-emerald-600" />
                        </div>
                        <span
                            class="font-semibold text-xs tracking-wide uppercase rounded-full px-3 py-1 ring-1 bg-emerald-50 text-emerald-700 ring-emerald-200">
                            {{ $certificat->type === 'certificat' ? 'Certificat' : 'Attestation' }}
                        </span>
                    </div>

                    <div>
                        <p class="font-semibold text-zinc-800">
                            {{ $certificat->inscription->sessionn->formation->nom ?? '' }}
                        </p>
                        <p class="text-sm text-zinc-500">Mention : {{ $certificat->mention }}</p>
                        <p class="text-xs text-zinc-400 font-mono mt-1">{{ $certificat->numero }}</p>
                    </div>

                    <div class="flex items-center justify-between text-xs text-zinc-400 mt-1">
                        <span>Délivré le {{ $certificat->dateDelivrance->format('d/m/Y') }}</span>
                    </div>

                    <flux:button wire:click="telecharger({{ $certificat->id }})" icon="arrow-down-tray"
                        variant="primary" color="green" class="mt-2">
                        Télécharger le PDF
                    </flux:button>
                </div>
            @endforeach
        </div>
    @endif
</div>
