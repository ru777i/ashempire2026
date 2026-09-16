<div>
    <div class="">
        <flux:heading size="xl">Emploi de temps</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="#">Emploi de temps</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#"></flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">

        @if (Auth::user()->role == 'secretaire')
            <flux:modal.trigger name="Seance">
                <flux:button wire:click="prepareCreate">Ajouter une séance</flux:button>
            </flux:modal.trigger>
        @endif

        @if (session()->has('message'))
            <div class="rounded bg-green-100 px-3 py-2 text-sm text-green-700">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <flux:modal name="Seance" class="md:w-96">
        <form wire:submit.prevent="saveEdt()" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? 'Modifier la séance' : 'Créer une séance' }}</flux:heading>
                <flux:text class="mt-2">Remplissez les détails de la séance.</flux:text>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <flux:select wire:model="salle_id" label="Salle" placeholder="Sélectionner la salle">
                    @foreach ($salles as $salle)
                        <flux:select.option value="{{ $salle->id }}">
                            Salle-{{ $salle->nom }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="module_id" placeholder="Choisir un module" label="Module">
                    @foreach ($modules as $mod)
                        <flux:select.option value="{{ $mod->id }}">{{ $mod->nom }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.live="sessionn_id" label="Session" placeholder="Sélectionner la session"
                    :filter="true">
                    @foreach ($sessions as $sess)
                        <flux:select.option value="{{ $sess->id }}">
                            {{ $sess->code }}-{{ $sess->formation->nom }} {{ $sess->dateDebut->format('d/m/Y') }}
                            au {{ $sess->dateFin->format('d/m/Y') }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
{{-- 
                <flux:select wire:model="formateur_id" label="Formateur" placeholder="Sélectionner le formateur">
                    @foreach ($formateurs as $format)
                        <flux:select.option value="{{ $format->id }}">
                            {{ $format->user->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select> --}}

                <flux:select wire:model="type" label="Type" placeholder="Sélectionner le type">
                    <flux:select.option value="CM">CM</flux:select.option>
                    <flux:select.option value="TD">TD</flux:select.option>
                    <flux:select.option value="TP">TP</flux:select.option>
                </flux:select>

                <flux:input label="Date" type="date" wire:model="date" />

                <flux:select wire:model="jour" label="Jour" placeholder="Sélectionner un jour">
                    <flux:select.option value="Lundi">Lundi</flux:select.option>
                    <flux:select.option value="Mardi">Mardi</flux:select.option>
                    <flux:select.option value="Mercredi">Mercredi</flux:select.option>
                    <flux:select.option value="Jeudi">Jeudi</flux:select.option>
                    <flux:select.option value="Vendredi">Vendredi</flux:select.option>
                </flux:select>

                <flux:input label="Heure de début" type="time" wire:model="heureDebut" />
                <flux:input label="Heure de fin" type="time" wire:model="heureFin" />
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    @include('livewire.emploi-temp.programme-emploi-temps')
</div>
