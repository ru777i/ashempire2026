<div class="p-6">
    <!-- En-tête -->
    <div class="mb-6">
        <flux:heading size="xl">Historique des Présences - Tous les Enregistrements</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="/" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Presences</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Historique des Présences</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <p class="text-sm text-gray-600 mt-2">
            Consultez tous les enregistrements de présences effectués pour vos séances.
        </p>
    </div>
    <div class="grid grid-cols-5 gap-5 m-3 rounded-md">
        <div class=" flex flex-col shadow-2xl bg-gray-100 p-4 items-center justify-center rounded-md border-2">
            <span>Presences</span>
            <div class=""><span class=" text-3xl text-green-500 font-bold">{{ $presents}}</span></div>
        </div>

        <div class=" flex flex-col shadow-2xl bg-gray-100 p-4  items-center justify-center rounded-md border-2">
            <span>Absences</span>
            <div class=""><span class=" text-3xl text-red-500 font-bold ">{{ $absences }}</span></div>
        </div>

        <div class=" flex flex-col shadow-2xl bg-gray-100 p-4 items-center justify-center rounded-md border-2">
            <span>Retards</span>
            <div class=""><span class=" text-3xl text-red-200 font-bold">{{ $retards }}</span></div>
        </div>
              <div class=" flex flex-col shadow-2xl bg-gray-100 p-4 items-center justify-center rounded-md border-2">
            <span>Justifie</span>
            <div class=""><span class=" text-3xl text-red-200 font-bold">{{ $justifiers }}</span></div>
        </div>
        <div class=" flex flex-col shadow-2xl bg-gray-100 p-4  items-center justify-center rounded-md border-2">
            <span>Total</span>
            <div class=""><span class=" text-3xl font-bold"> Total : {{ count($presences) }}</span></div>
        </div>
        


    </div>

    <!-- Filtres -->
    <div class="mb-6">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Filtres de recherche</flux:heading>
                <div>
                    <flux:label>Statut</flux:label>
                    <flux:select wire:model.live="statut" placeholder="Tous les statuts" :filter="true">
                        <flux:select.option value="">-- Tous les statuts --</flux:select.option>
                        @foreach ($statuts as $value )
                            <flux:select.option value="{{ $value }}">{{ $value }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
        </flux:card>
    </div>
    <!-- Tableau des présences -->
    @if (count($presences) > 0)
        <div class="mb-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">
                    Résultats ({{ count($presences) }} enregistrement{{ count($presences) > 1 ? 's' : '' }})
                </flux:heading>

                <div class="overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Enregistrement</flux:table.column>
                            <flux:table.column>Module</flux:table.column>
                            <flux:table.column>Formateur</flux:table.column>
                            <flux:table.column>Statut</flux:table.column>
                            <flux:table.column>Observations</flux:table.column>
                            <flux:table.column>Date</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @forelse($presences as $presence)
                                <flux:table.row>
                                    <flux:table.cell>
                                        <span
                                            class="inline-block px-2 py-1 bg-gray-200 text-gray-800 text-xs font-semibold rounded">
                                            #{{ $presence['id'] }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        {{ $presence->emploiTemps->module->nom }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-sm">
                                            {{  $presence->emploiTemps->formateur->user->name ?? '' }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if ($presence['statut'] === 'present') bg-green-100 text-green-800
                                            @elseif ($presence['statut'] === 'absent')
                                                bg-red-100 text-red-800
                                            @elseif ($presence['statut'] === 'retard')
                                                bg-yellow-100 text-yellow-800
                                            @elseif ($presence['statut'] === 'justifie')
                                                bg-blue-100 text-blue-800 @endif
                                        ">
                                            {{ $statuts[$presence['statut']] ?? $presence['statut'] }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-xs text-gray-600">
                                            {{ $presence['observations'] ?? '-' }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($presence['created_at'])->format('d/m/Y H:i') }}
                                        </span>
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="6" class="text-center text-gray-500 py-8">
                                        <flux:icon variant="solid" name="inbox" class="inline mr-2" />
                                        Aucun enregistrement trouvé avec les critères spécifiés
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>
        </div>
    @elseif ($presences)
        <flux:card class="text-center text-gray-500 py-8">
            <flux:icon variant="solid" name="inbox" class="inline mr-2" />
            Aucune présence enregistrée pour cette séance
        </flux:card>
    @endif
</div>
