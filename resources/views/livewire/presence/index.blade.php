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
    <div class="grid grid-cols-4 gap-5 m-3 rounded-md">
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
        <div class=" flex flex-col shadow-2xl bg-gray-100 p-4  items-center justify-center rounded-md border-2">
            <span>Total</span>
            <div class=""><span class=" text-3xl font-bold">{{ $totalAppel }}</span></div>
        </div>


    </div>

    <!-- Filtres -->
    <div class="mb-6">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Filtres de recherche</flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Sélection de la séance -->
                <div>
                    <flux:label>Séance</flux:label>
                    <flux:select wire:model.live="selectedEmploiTempId" placeholder="Sélectionner une séance..."
                        :filter="true">
                        <flux:select.option value="">-- Toutes les séances --</flux:select.option>
                        @forelse ($emploiTemps as $emploiTemp)
                            <flux:select.option value="{{ $emploiTemp->id }}">
                                {{ $emploiTemp->module->nom ?? 'N/A' }} |
                                {{ $emploiTemp->heureDebut }}-{{ $emploiTemp->heureFin }} |
                                {{ $emploiTemp->jour }}
                            </flux:select.option>
                        @empty
                            <flux:select.option disabled value="">
                                Aucune séance disponible
                            </flux:select.option>
                        @endforelse
                    </flux:select>
                </div>

                <!-- Sélection de l'enregistrement -->
                @if ($selectedEmploiTempId && count($enregistrements) > 0)
                    <div>
                        <flux:label>Enregistrement</flux:label>
                        <flux:select wire:model.live="selectedNumeroEnregistrement"
                            placeholder="Tous les enregistrements" :filter="true">
                            <flux:select.option value="">-- Tous les enregistrements --</flux:select.option>
                            @foreach ($enregistrements as $enreg)
                                <flux:select.option value="{{ $enreg['numero_enregistrement'] }}">
                                    #{{ $enreg['numero_enregistrement'] }} -
                                    {{ \Carbon\Carbon::parse($enreg['created_at'])->format('d/m/Y H:i') }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                @endif

                <!-- Filtre statut -->
                <div>
                    <flux:label>Statut</flux:label>
                    <flux:select wire:model.live="filterStatut" placeholder="Tous les statuts" :filter="true">
                        <flux:select.option value="">-- Tous les statuts --</flux:select.option>
                        @foreach ($statuts as $value => $label)
                            <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Résumé des enregistrements -->
    @if ($selectedEmploiTempId && count($enregistrements) > 0)
        <div class="mb-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">
                    Résumé: {{ count($enregistrements) }} enregistrement(s) pour cette séance
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($enregistrements as $enreg)
                        <div class="p-3 border-l-4 border-blue-500 bg-blue-50 rounded cursor-pointer hover:bg-blue-100 transition"
                            wire:click="$set('selectedNumeroEnregistrement', {{ $enreg['numero_enregistrement'] }})">
                            <div class="font-semibold text-blue-900">
                                Enregistrement #{{ $enreg['numero_enregistrement'] }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ \Carbon\Carbon::parse($enreg['created_at'])->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        </div>
    @endif

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
                            <flux:table.column>Matricule</flux:table.column>
                            <flux:table.column>Apprenant</flux:table.column>
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
                                            #{{ $presence['numero_enregistrement'] }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        {{ $presence['inscription']['aprenant']['matricule'] ?? 'N/A' }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-sm">
                                            {{ $presence['inscription']['aprenant']['user']['name'] ?? '' }}
                                            {{ $presence['inscription']['aprenant']['prenom'] ?? '' }}
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
    @elseif ($selectedEmploiTempId)
        <flux:card class="text-center text-gray-500 py-8">
            <flux:icon variant="solid" name="inbox" class="inline mr-2" />
            Aucune présence enregistrée pour cette séance
        </flux:card>
    @endif
</div>
