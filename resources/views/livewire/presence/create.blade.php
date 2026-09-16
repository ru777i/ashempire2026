<div class="p-6">
    <!-- En-tête -->
    <div class="mb-6">
        <flux:heading size="xl">Gestion des Présences - Enregistrement Multiple</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="/" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Gestion</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Présences</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <p class="text-sm text-gray-600 mt-2">
            Vous pouvez enregistrer les absences plusieurs fois pour la même séance. Chaque enregistrement reçoit un
            numéro.
        </p>
    </div>

    <!-- Message de succès -->


    <!-- Sélection de la séance -->
    <div class="mb-6">
        <flux:card class=" flex justify-between gap-7">
            {{-- <flux:heading size="lg" class="mb-4">Sélectionner une séance</flux:heading> --}}
            <flux:select wire:model.live="selectedEmploiTempId" placeholder="Sélectionner une séance..."
                :filter="true" class="mb-4">
                {{-- <flux:select.option >-- Sélectionner une séance --</flux:select.option> --}}
                @forelse ($emploiTemps as $emploiTempp)
                    <flux:select.option value="{{$emploiTempp->id }}">
                        {{ $emploiTempp->module->nom ?? 'N/A' }} |
                        {{ $emploiTempp->heureDebut }}-{{ $emploiTempp->heureFin }} |
                        {{ $emploiTempp->jour }}
                    </flux:select.option>

                @empty
                    <flux:select.option disabled value="">
                        Aucune séance disponible
                    </flux:select.option>
                @endforelse
            </flux:select>
              {{-- {{ $selectedEmploiTempId }} --}}
            <flux:select placeholder="Selectionner la date de la seance" wire:model.live="seanceId">
                <flux:select.option>-- Sélectionner une séance --</flux:select.option>
                @foreach ($seances as $seance)
                    <flux:select.option value="{{ $seance->id }}">
                        {{ $seance->date }} {{ $seance->heureDebut }}-{{ $seance->heureFin }} ({{ $seance->statut }})
                    </flux:select.option>
                @endforeach
            </flux:select>
            @if ($selectedEmploiTempId)
                <flux:modal.trigger name="edit-profile">
                    <flux:button icon="plus">Ajouter une seance</flux:button>
                </flux:modal.trigger>
            @endif

            <flux:modal name="edit-profile" class="md:w-96">
                <form class="space-y-6" wire:submit="enregistrerSeance()">
                    <div>
                        <flux:heading size="lg">Ajouter une seance</flux:heading>
                        <flux:text class="mt-2">Remplissez les details de la seances</flux:text>
                    </div>

                    <flux:input wire:model="emploi_temp_id" class="hidden" />
                    <flux:input label="Date du jour" label="date" type="date" wire:model="dateSeance" />
                    {{-- <flux:input label="Heure debut" type="time" wire:model="heureDebut" />
                    <flux:input label="Heure fin" type="time" wire:model="heureFin" /> --}}
                    <flux:select label="Statut" wire:model="statut" placeholder="Selectionner le statut de la seance">
                        @foreach ($statutsSeance as $value)
                            <flux:select.option value="{{ $value }}">
                                {{ $value }}
                            </flux:select.option>
                        @endforeach

                    </flux:select>
                    <div class="flex">
                        <flux:spacer />

                        <flux:button type="submit" variant="primary" icon="arrow-down-tray">Enregistrer la seance
                        </flux:button>
                    </div>
                </form>
            </flux:modal>
        </flux:card>
    </div>

    <!-- Historique des enregistrements -->
    {{-- @if ($selectedEmploiTempId && count($derniersEnregistrements) > 0)
    <div class="mb-6">
        <flux:card>
            <flux:heading size="lg" class="mb-4">Historique des enregistrements</flux:heading>
            <p class="text-sm text-gray-600 mb-3">Cliquez sur un enregistrement pour le charger</p>

            <div class="space-y-2">
                @foreach ($derniersEnregistrements as $enregistrement)
                <button type="button"
                    wire:click="afficherAncienEnregistrement({{ $enregistrement['numero_enregistrement'] }})"
                    class="w-full text-left p-3 border border-gray-300 rounded hover:bg-blue-50 transition">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">
                            Enregistrement #{{ $enregistrement['numero_enregistrement'] }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($enregistrement['created_at'])->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </button>
                @endforeach
            </div>
        </flux:card>
    </div>
    @endif --}}

    <!-- Tableau des présences -->
    @if ($selectedEmploiTempId && count($apprenants) > 0)
        <div class="mb-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">Enregistrement des présences</flux:heading>

                <form wire:submit.prevent="savePresences">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Matricule</flux:table.column>
                            <flux:table.column>Nom & Prénom</flux:table.column>
                            <flux:table.column>Statut</flux:table.column>
                            <flux:table.column>Observations</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @forelse($apprenants as $index => $inscription)
                                <flux:table.row>
                                    <flux:table.cell>
                                        {{ $inscription->aprenant->matricule }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-sm">
                                            {{  $inscription->aprenant->nom ?? '' }}
                                            {{  $inscription->aprenant->prenom ?? '' }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:select wire:model="presences.{{ $index }}.statut" size="sm"
                                            class="min-w-0">
                                            @foreach ($statuts as $label)
                                                <option value="{{ $label }}">{{ $label }}</option>
                                            @endforeach
                                        </flux:select>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <input type="text" wire:model="presences.{{ $index }}.observations"
                                            placeholder="Ex: Justificatif reçu"
                                            class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-500" />
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="text-center text-gray-500">
                                        Aucun apprenant inscrit pour cette séance
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>

                    <!-- Boutons -->
                    <div class="mt-6 flex gap-3">
                        <flux:button type="submit" variant="primary">
                            <flux:icon variant="solid" name="plus-circle" class="inline mr-2" />
                            Enregistrer
                        </flux:button>
                        <flux:button type="button" wire:click="$set('selectedEmploiTempId', '')" variant="ghost">
                            Annuler
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    @elseif ($selectedEmploiTempId && count($apprenants) == 0)
        <flux:card class="text-center text-gray-500 py-8">
            <flux:icon variant="solid" name="exclamation-triangle" class="inline mr-2" />
            Aucun apprenant inscrit pour cette séance
        </flux:card>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('hideSuccess', () => {
            setTimeout(() => {
                @this.set('showSuccess', false);
            }, 3000);
        });
    });
</script>

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('hideSuccess', () => {
            setTimeout(() => {
                @this.set('showSuccess', false);
            }, 3000);
        });
    });
</script>
