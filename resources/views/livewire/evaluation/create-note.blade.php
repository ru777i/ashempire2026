<div class="p-6">
    <!-- En-tête -->
    <div class="mb-6">
        <flux:heading size="xl">Gestion des Notes - Enregistrement Multiple</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="/" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Note</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter</flux:breadcrumbs.item>
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
            <flux:select wire:model.live="selectedEvaluationId" placeholder="Sélectionner une séance..."
                :filter="true" class="mb-4">
                <flux:select.option value="">-- Sélectionner une séance --</flux:select.option>
                @forelse ($evaluations as $evaluation)
                    <flux:select.option value="{{ $evaluation->id }}">
                        {{ $evaluation->sessionModule->module->nom ?? 'N/A' }} |
                        {{ $evaluation->noteMax }} Date: {{ $evaluation->dateEvaluation }} |
                    </flux:select.option>
                @empty
                    <flux:select.option disabled value="">
                        ...
                    </flux:select.option>
                @endforelse
            </flux:select>
            <flux:modal.trigger name="AjouterEvalua">
                <flux:button icon="plus" variant="filled">Ajouter une evaluation</flux:button>
            </flux:modal.trigger>

            <flux:modal name="AjouterEvalua" class="md:w-96">
                <form wire:submit.prevent="saveEvaluation()" class="space-y-6">
                    <div>
                        <flux:heading size="lg">Creer une evaluations</flux:heading>
                        <flux:text class="mt-2">Selectionner le module</flux:text>
                    </div>
                    <div class=" grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <flux:input type="time" label="Heure Debut"  wire:model="heureDebut" />
                        <flux:input type="time" label="Heure Fin" wire:model="heureFin" />
                        <flux:input type="number" label="noteMax" wire:model="noteMax" />
                        <flux:input type="date" label="Date evaluation" wire:model="dateEvaluation" />
                        <flux:select wire:model.live="sessionn_module_id" placeholder="Selectionner le module"
                            placeholder="Selectionner le module">
                            @foreach ($sessionModules as $sessionModule)
                                <flux:select.option value="{{ $sessionModule->id }}">
                                    {{ $sessionModule->sessionn->formation->nom }}-{{ $sessionModule->module->nom }}
                                    {{-- {{ $sessionn->dateDebut->format('Y/m/d') }}
                                    {{ $sessionn->dateFin->format('Y/m/d') }} --}}
                                </flux:select.option>
                            @endforeach

                        </flux:select>
                    </div>
                    <div class="flex">
                        <flux:spacer />

                        <flux:button type="submit" icon="arrow-down-tray" variant="primary">Enregistrer</flux:button>
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
    @if ($selectedEvaluationId && count($apprenants) > 0)
        <div class="mb-6">
            <flux:card>
                <flux:heading size="lg" class="mb-4">Enregistrement des Notes</flux:heading>

                <form wire:submit.prevent="saveNotes">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Matricule</flux:table.column>
                            <flux:table.column>Nom & Prénom</flux:table.column>
                            <flux:table.column>Note</flux:table.column>

                        </flux:table.columns>
                        <flux:table.rows>
                            @forelse($apprenants as $index => $apprenant)
                                <flux:table.row>
                                    <flux:table.cell>
                                        {{ $apprenant->aprenant->matricule ?? 'N/A' }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-sm">
                                            {{ $apprenant['aprenant']['nom'] ?? '' }}
                                            {{ $apprenant['aprenant']['prenom'] ?? '' }}
                                        </span>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:input wire:model="notes.{{ $apprenant->id }}.note"
                                            placeholder="Entrer une note" />
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
    @elseif ($selectedEvaluationId && count($apprenants) == 0)
        <flux:card class="text-center text-gray-500 py-8">
            <flux:icon variant="solid" name="exclamation-triangle" class="inline mr-2" />
            Aucun apprenant inscrit pour cette session
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
