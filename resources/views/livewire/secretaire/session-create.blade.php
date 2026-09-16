<div>
    <div class="mb-6">
        <flux:heading size="xl" class="!text-emerald-800 font-bold">Ajouter une session</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class="my-3" />
            <flux:breadcrumbs.item href="{{ route('sessions') }}">Sessions</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Ajouter</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="bg-white shadow-lg shadow-emerald-900/5 ring-1 ring-emerald-950/5 rounded-2xl">

        <form wire:submit.prevent="enregistrer()" @click.outside="open = false" class="p-4 sm:p-6 space-y-8">

            <div>
                <h3 class="text-sm font-semibold text-emerald-700 uppercase tracking-wide mb-4">Détails de la session
                </h3>
                <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">
                    <flux:select wire:model.live="formation_id" label="Choisir une formation"
                        placeholder="Choisir une formation">
                        @foreach ($formations as $formation)
                            <flux:select.option value="{{ $formation->id }}">{{ $formation->nom }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="type" label="Type de session"
                        placeholder="Choisir le type de session">
                        <flux:select.option value="">Choisir le type de session</flux:select.option>
                        <flux:select.option value="journee">JOURNEE</flux:select.option>
                        <flux:select.option value="soiree">SOIREE</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="dateDebut" type="date" label="Date debut session" />

                    <flux:input wire:model="dateFin" type="date" label="Date fin session" />

                    <flux:input wire:model="capacite" icon="server" label="Capacite" type="number"
                        placeholder="Entrer la capacite de la session" />
                </div>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-emerald-50/50 rounded-xl p-4 ring-1 ring-emerald-100">
                    <flux:radio.group wire:model="statut" label="Statut"
                        description="Selectionner le statut de la session">
                        <flux:radio value="en attente" label="EN ATTENTE" />
                        <flux:radio value="en cours" label="EN COURS" />
                        <flux:radio value="terminee" label="TERMINEE" />
                        <flux:radio value="annulee" label="ANNULEE" />
                    </flux:radio.group>
                </div>

                <div class="bg-emerald-50/50 rounded-xl p-4 ring-1 ring-emerald-100">
                    <flux:checkbox.group wire:model="moduless"
                        label="Selectionner les modules de la session de formation">
                        @forelse ($modules as $module)
                            <flux:checkbox label="{{ $module->nom }}" value="{{ $module->id }}" />
                        @empty
                            <flux:text class="text-zinc-400">Aucun module disponible</flux:text>
                        @endforelse
                    </flux:checkbox.group>
                </div>

                <div class="bg-emerald-50/50 rounded-xl p-4 ring-1 ring-emerald-100 lg:col-span-2">
                    <flux:radio.group wire:model="anne_academique_id" label="Annee Academique"
                        description="Selectionner l'annee academique de la session">
                        @foreach ($anneAcademiques as $annee)
                            <flux:radio value="{{ $annee->id }}"
                                label="{{ $annee->dateDebut }}  A  {{ $annee->dateFin }}" />
                        @endforeach
                    </flux:radio.group>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <flux:button type="submit" variant="primary" color="green" icon="arrow-down-tray"
                    class="shadow-md shadow-emerald-600/20">Enregistrer
                </flux:button>
            </div>

        </form>
    </div>
</div>
