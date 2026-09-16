<div>
    <div class="">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
            <flux:breadcrumbs.item href="#">Modules</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
    <div class="">
          <div class="">
            <flux:input icon="magnifying-glass" placeholder="rechercher un module" wire:model.live="search"
                class="" kbd="⌘K" />
        </div>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nom</flux:table.column>
                <flux:table.column>Volume Horaire</flux:table.column>
                <flux:table.column>Formation</flux:table.column>
                <flux:table.column>Progression</flux:table.column>
                <flux:table.column>Statut</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>


            <flux:table.rows>
                @forelse ($sessionModules as $sessionModule)
                    <flux:table.row>
                        <flux:table.cell>{{ $sessionModule->module->nom }}</flux:table.cell>
                        <flux:table.cell>{{ $sessionModule->module->volumeHoraire }}</flux:table.cell>
                        <flux:table.cell>{{ $sessionModule->sessionn->formation->nom }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:field>
                                <flux:label>

                                    <x-slot name="trailing">
                                        <span
                                            class="tabular-nums">{{ round(($sessionModule->progress / $sessionModule->module->volumeHoraire) * 100, 2) }}%</span>
                                    </x-slot>
                                </flux:label>
                                @php
                                    $pourcentage = round(
                                        ($sessionModule->progress / $sessionModule->module->volumeHoraire) * 100,
                                        2,
                                    );

                                    $couleur = match (true) {
                                        $pourcentage < 25 => 'red',
                                        $pourcentage < 50 => 'orange',
                                        $pourcentage < 75 => 'yellow',
                                        $pourcentage < 100 => 'blue',
                                        default => 'green',
                                    };
                                @endphp

                                <flux:progress color="{{ $couleur }}" value="{{ $pourcentage }}" />
                                {{-- <flux:progress  color="green" value="{{ round(($sessionModule->progress/$sessionModule->module->volumeHoraire)*100,2)}}" /> --}}
                            </flux:field>
                        </flux:table.cell>
                        <flux:table.cell> <span
                                class=" p-2 rounded-md bg-gray-200  text-green-500 font-extrabold">{{ $sessionModule->statut }}</span>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:button variant="primary" icon="eye"></flux:button>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:text>Aucun module trouover</flux:text>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

</div>
