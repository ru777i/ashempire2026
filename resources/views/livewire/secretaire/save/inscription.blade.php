<div>

    <!-- En-tête -->
    <div class="mb-6">
        <flux:heading size="xl">Inscriptions</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="/" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">insciptions</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="">
        <div class=" flex justify-between gap-4 mb-3">
            <flux:input kbd="⌘K" icon="magnifying-glass" placeholder="Search..." wire:model.live='search' />
            <flux:button href="{{ route('ajouterInscription') }}" variant="primary" color="green" icon="plus">
                Inscrire</flux:button>
        </div>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Matricule</flux:table.column>
                <flux:table.column>Nom & Prenom</flux:table.column>
                <flux:table.column>Sexe</flux:table.column>
                {{-- <flux:table.column>Date de naissance</flux:table.column> --}}
                <flux:table.column>Formation</flux:table.column>
                <flux:table.column>Session</flux:table.column>
                <flux:table.column>Statut</flux:table.column>

                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($inscriptions as $inscription)
                    <flux:table.row>
                        <flux:table.cell>{{ $inscription->aprenant->matricule ?? '' }}</flux:table.cell>
                        <flux:table.cell> {{ $inscription->aprenant->user->name ?? '' }}
                            {{ $inscription->aprenant->prenom }}</flux:table.cell>
                        <flux:table.cell> {{ $inscription->aprenant->sexe == 'M' ? 'Masculin' : 'Feminin' ?? '' }}
                        </flux:table.cell>
                        {{-- <flux:table.cell>{{ $inscription->aprenant->dateNaissance ?? '' }}</flux:table.cell> --}}
                        <flux:table.cell>{{ $inscription->sessionn->formation->nom }}</flux:table.cell>
                        <flux:table.cell> {{ $inscription->sessionn->dateDebut->format('Y/m/d') ?? '' }} Au
                            {{ $inscription->sessionn->dateFin->format('Y/m/d') ?? '' }}</flux:table.cell>
                        <flux:table.cell> <span @class([
                            'font-extrabold rounded-md px-2 py-1',

                            'bg-yellow-100 text-yellow-700' => $inscription->statut === 'En attente',

                            'bg-green-100 text-green-700' => $inscription->statut === 'Validée',

                            'bg-red-100 text-red-700' => $inscription->statut === 'Refusée',

                            'bg-blue-100 text-blue-700' => $inscription->statut === 'En formation',

                            'bg-purple-100 text-purple-700' => $inscription->statut === 'Terminée',

                            'bg-gray-200 text-gray-700' => $inscription->statut === 'Abandonnée',
                        ])>
                                {{ $inscription->statut }}
                            </span></span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:button href="{{ route('formateurs', $inscription->id) }}" icon="eye"
                                variant="primary"></flux:button>

                            <flux:button href="{{ route('ajouterInscription', $inscription->id) }}" icon="pencil">

                            </flux:button>

                            <flux:button wire:click="delete({{ $inscription->id }})" icon="trash" variant="danger">
                            </flux:button>
                        </flux:table.cell>



                    </flux:table.row>
                @empty
                @endforelse
            </flux:table.rows>
        </flux:table>
        {{ $inscriptions->links() }}

    </div>
    {{-- He who is contented is rich. - Laozi --}}
</div>
