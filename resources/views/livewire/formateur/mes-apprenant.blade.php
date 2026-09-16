<div>
    <div class="my-2">
        <flux:heading size="xl">Mes Apprnants</flux:heading>
        <flux:subheading>Liste des apprenants de vos sessions</flux:subheading>
    </div>
    <div class="flex justify-between mt-2">
        <div class="my-2">
            <flux:select class="" placeholder="Tous les sessions" wire:model.live="sessionId" class="mr-10 ">
                @foreach ($sessionModules as $sessionModule)
                    <flux:select.option value="{{ $sessionModule->sessionn->id }}">
                        -{{ $sessionModule->sessionn->formation->nom ?? '' }} Plage:
                        {{ $sessionModule->sessionn->dateDebut->format('Y/m/d') ?? '' }}--{{ $sessionModule->sessionn->dateFin->format('Y/m/d') ?? '' }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <div class="">
            <flux:input icon="magnifying-glass" placeholder="rechercher un apprenants" wire:model.live="search"
                class="" kbd="⌘K" />
        </div>

    </div>

    <div class="">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Matricule</flux:table.column>
                <flux:table.column>Nom & Prenom</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse ($apprenants as $apprenant)
                    <flux:table.row>
                        <flux:table.cell>{{ $apprenant->matricule }}</flux:table.cell>
                        <flux:table.cell>{{ $apprenant->user->name }} {{ $apprenant->prenom }}</flux:table.cell>
                        <flux:table.cell>{{ $apprenant->user->email }}</flux:table.cell>
                        <flux:table.cell> <flux:button color="green" icon="eye"></flux:button> </flux:table.cell>
                    </flux:table.row>
                @empty

                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- You must be the change you wish to see in the world. - Mahatma Gandhi --}}
</div>
