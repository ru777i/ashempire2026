@if (Auth::user()->role =='secretaire')


    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="div"> <livewire:notifications-bell /></div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <div class="rounded-xl bg-white ring-1 ring-emerald-950/5 shadow-sm p-5">
                <span class="text-zinc-400 text-xs uppercase tracking-wide flex items-center gap-2">
                    <flux:icon.banknotes class="w-4 h-4 text-emerald-500" /> Revenus encaissés
                </span>
                <p class="text-2xl font-bold text-emerald-700 mt-2">{{ number_format($revenusTotal, 0) }}</p>
            </div>
            <div class="rounded-xl bg-white ring-1 ring-emerald-950/5 shadow-sm p-5">
                <span class="text-zinc-400 text-xs uppercase tracking-wide flex items-center gap-2">
                    <flux:icon.clock class="w-4 h-4 text-amber-500" /> Revenus en attente
                </span>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ number_format($revenusEnAttente, 0) }}</p>
            </div>
            <div class="rounded-xl bg-white ring-1 ring-emerald-950/5 shadow-sm p-5">
                <span class="text-zinc-400 text-xs uppercase tracking-wide flex items-center gap-2">
                    <flux:icon.check-circle class="w-4 h-4 text-emerald-500" /> Taux de présence
                </span>
                <p class="text-2xl font-bold text-emerald-700 mt-2">{{ $tauxPresence }}%</p>
            </div>
            <div class="rounded-xl bg-white ring-1 ring-emerald-950/5 shadow-sm p-5">
                <span class="text-zinc-400 text-xs uppercase tracking-wide flex items-center gap-2">
                    <flux:icon.academic-cap class="w-4 h-4 text-blue-500" /> Taux de réussite
                </span>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ $tauxReussite }}%</p>
            </div>
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative flex items-center justify-center aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="relative w-full h-full flex items-center justify-center">
                    <canvas id="formationChart"></canvas>
                </div>

                @script
                    <script>
                        const ctx = document.getElementById('formationChart');

                        new Chart(ctx, {
                            type: 'pie',

                            data: {
                                labels: @json($labels),

                                datasets: [{
                                    label: 'Nombre d\'inscriptions',
                                    data: @json($data),
                                    borderWidth: 1
                                }]
                            },

                            options: {
                                responsive: true,

                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }

                        });
                    </script>
                @endscript
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="relative aspect-video rounded-xl border p-6">
                    <div class="flex flex-col justify-center h-full">

                        <span class="text-gray-500 text-sm flex items-center  justify-center">
                            <flux:icon.user-group variant="solid" class=" size-10 text-green-500 dark:text-amber-300" />
                            <span> Apprenants en formation </span>
                        </span>

                        <span class="text-5xl font-bold text-green-600 flex justify-center">
                            {{ $nbA }}
                        </span>
                    </div>
                </div>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="relative aspect-video rounded-xl border p-6">
                    <div class="flex flex-col justify-center h-full">
                        <span class="text-gray-500 text-sm flex items-center justify-center">
                            <flux:icon.calendar variant="solid" class=" size-10 text-blue-500 dark:text-amber-300" />
                            Session en cours
                        </span>

                        <span class="text-5xl font-bold text-blue-600 flex justify-center items-center">
                            {{ $nbS }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <flux:heading size="xl" class="p-3 uppercase!">Dernieres inscriptions </flux:heading>
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


                            </flux:table.cell>



                        </flux:table.row>
                    @empty
                    @endforelse
                </flux:table.rows>
            </flux:table>
            {{ $inscriptions->links() }}

        </div>
    </div>
@endif

@if (Auth::user()->role =='formateur')

    <div>
        <div class="div"> <livewire:notifications-bell /></div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

            {{-- Modules --}}
            <flux:card class="h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Modules enseignés</p>
                        <h2 class="mt-2 text-3xl font-bold">
                            {{ $nombreModules }}
                        </h2>
                    </div>

                    <flux:icon.book-open class="size-10 text-blue-600" />
                </div>
            </flux:card>

            {{-- Cours --}}
            <flux:card class="h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Cours aujourd'hui</p>
                        <h2 class="mt-2 text-3xl font-bold">
                            {{ $nombreCoursAujourdhui }}
                        </h2>
                    </div>

                    <flux:icon.calendar class="size-10 text-green-600" />
                </div>
            </flux:card>

            {{-- Apprenants --}}
            <flux:card class="h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Apprenants</p>
                        <h2 class="mt-2 text-3xl font-bold">
                            {{ $nombreApprenants }}
                        </h2>
                    </div>

                    <flux:icon.users class="size-10 text-violet-600" />
                </div>
            </flux:card>

            {{-- Evaluations --}}
            <flux:card class="h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Copies à corriger</p>
                        <h2 class="mt-2 text-3xl font-bold">
                            {{ $evaluationsACorriger }}
                        </h2>
                    </div>

                    <flux:icon.clipboard-document-check class="size-10 text-red-600" />
                </div>
            </flux:card>

        </div>
        <div class="mt-6 grid grid-cols-12 gap-6">

            <flux:card class="col-span-12 xl:col-span-8">

                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">
                        Emploi du temps du jour
                    </h2>

                    <flux:badge color="blue">
                        {{ count($emploiTemps) }}
                    </flux:badge>
                </div>

                <div class="space-y-4">

                    @forelse($emploiTemps as $cours)
                        <div class="rounded-lg border p-4">

                            <div class="flex justify-between">

                                <div>

                                    <h3 class="font-semibold">
                                        {{ $cours['module'] }}
                                    </h3>

                                    <p class="text-sm text-zinc-500">
                                        Salle {{ $cours['salle'] }}
                                    </p>

                                </div>

                                <div class="text-right">

                                    <p class="font-medium">
                                        {{ $cours['debut'] }} - {{ $cours['fin'] }}
                                    </p>

                                    <p class="text-xs text-zinc-500">
                                        {{ $cours['type'] }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="py-10 text-center text-zinc-500">
                            Aucun cours aujourd'hui.
                        </div>
                    @endforelse

                </div>

            </flux:card>

            <flux:card class="col-span-12 xl:col-span-4">

                <div class="mb-5 flex items-center justify-between">

                    <h2 class="text-lg font-semibold">
                        Prochaines évaluations
                    </h2>

                    <flux:badge color="amber">
                        {{ count($prochainesEvaluations) }}
                    </flux:badge>

                </div>

                <div class="space-y-4">

                    @forelse($prochainesEvaluations as $evaluation)
                        <div class="rounded-lg border p-3">

                            <h3 class="font-semibold">
                                {{ $evaluation['module'] }}
                            </h3>

                            <p class="text-sm text-zinc-500">
                                {{ $evaluation['date'] }}
                            </p>

                            <p class="text-xs text-zinc-400">
                                {{ $evaluation['heure'] }}
                            </p>

                        </div>

                    @empty

                        <div class="text-center text-zinc-500 py-8">
                            Aucune évaluation.
                        </div>
                    @endforelse

                </div>

            </flux:card>

        </div>
        <div class="mt-6 grid grid-cols-12 gap-6">

            <flux:card class="col-span-12 xl:col-span-8">

                <div class="mb-5">
                    <h2 class="text-lg font-semibold">
                        Mes modules
                    </h2>
                </div>

                <div class="space-y-5">

                    @foreach ($modules as $module)
                        <div>

                            <div class="mb-2 flex justify-between">

                                <span class="font-medium">
                                    {{ $module['nom'] }}
                                </span>

                                <span>
                                    {{ $module['progression'] }}%
                                </span>

                            </div>

                            <flux:progress value="{{ $module['progression'] }}" color="green" />

                        </div>
                    @endforeach

                </div>

            </flux:card>

            <flux:card class="col-span-12 xl:col-span-4">

                <h2 class="mb-5 text-lg font-semibold">
                    Actions rapides
                </h2>

                <div class="space-y-3">

                    <flux:button class="w-full" variant="primary">
                        Faire l'appel
                    </flux:button>

                    <flux:button class="w-full">
                        Ajouter une évaluation
                    </flux:button>

                    <flux:button class="w-full">
                        Saisir les notes
                    </flux:button>

                    <flux:button class="w-full">
                        Voir les recours
                    </flux:button>

                </div>

            </flux:card>

        </div>

        {{-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh --}}
    </div>

@endif

@if (Auth::user()->role == 'apprenant')
    <div class="space-y-6">
        <div class="div"> <livewire:notifications-bell /></div>
        {{-- ===================== Statistiques ===================== --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

            <flux:card class="h-full">
                <div class="flex h-full items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">Formation</p>
                        <h2 class="mt-1 text-xl font-bold">
                            {{ $formation }}
                        </h2>
                    </div>

                    <flux:icon.academic-cap class="size-10 text-blue-600" />
                </div>
            </flux:card>

            <flux:card class="h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">
                            Progression
                        </p>

                        <h2 class="mt-1 text-3xl font-bold">
                            {{ $progression }}%
                        </h2>
                    </div>

                    <flux:icon.chart-bar class="size-10 text-green-600" />
                </div>

                <div class="mt-4">
                    <flux:progress value="{{ $progression }}" color="green" />
                </div>
            </flux:card>

            <flux:card class="h-full">
                <div class="flex h-full items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">
                            Moyenne Générale
                        </p>

                        <h2 class="mt-1 text-3xl font-bold">
                            {{ $moyenne }}/20
                        </h2>
                    </div>

                    <flux:icon.star class="size-10 text-yellow-500" />
                </div>
            </flux:card>

            <flux:card class="h-full">
                <div class="flex h-full items-center justify-between">
                    <div>
                        <p class="text-sm text-zinc-500">
                            Présence
                        </p>

                        <h2 class="mt-1 text-3xl font-bold">
                            {{ $tauxPresence }}%
                        </h2>
                    </div>

                    <flux:icon.check-circle class="size-10 text-emerald-600" />
                </div>
            </flux:card>

        </div>

        {{-- ===================== Modules + Evaluations ===================== --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

            {{-- Modules --}}
            <flux:card class="xl:col-span-8">

                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">
                        Modules en cours
                    </h2>

                    <flux:badge color="blue">
                        {{ count($modules) }}
                    </flux:badge>
                </div>

                <div class="space-y-5">

                    @forelse($modules as $module)
                        <div class="border-b pb-4 last:border-none">

                            <div class="mb-2 flex items-center justify-between">

                                <div>

                                    <h3 class="font-medium">
                                        {{ $module['nom'] }}
                                    </h3>

                                    @if (!empty($module['formateur']))
                                        <p class="text-xs text-zinc-500">
                                            {{ $module['formateur'] }}
                                        </p>
                                    @endif

                                </div>

                                <span class="font-semibold">
                                    {{ $module['progression'] }}%
                                </span>

                            </div>

                            <flux:progress value="{{ $module['progression'] }}" color="green" />

                        </div>

                    @empty

                        <p class="text-center text-zinc-500">
                            Aucun module.
                        </p>
                    @endforelse

                </div>

            </flux:card>

            {{-- Evaluations --}}
            <flux:card class="xl:col-span-4">

                <div class="mb-6 flex items-center justify-between">

                    <h2 class="text-lg font-semibold">

                        Prochaines évaluations

                    </h2>

                    <flux:badge color="amber">

                        {{ count($prochainesEvaluations) }}

                    </flux:badge>

                </div>

                <div class="space-y-4">

                    @forelse($prochainesEvaluations as $evaluation)
                        <div class="rounded-lg border p-3">

                            <h3 class="font-semibold">

                                {{ $evaluation['module'] }}

                            </h3>

                            <p class="mt-1 text-sm text-zinc-500">

                                {{ $evaluation['date'] }}

                            </p>

                            @if (isset($evaluation['heure']))
                                <p class="text-xs text-zinc-400">

                                    {{ $evaluation['heure'] }}

                                </p>
                            @endif

                        </div>

                    @empty

                        <div class="text-center text-zinc-500">

                            Aucune évaluation programmée.

                        </div>
                    @endforelse

                </div>

            </flux:card>

        </div>

        {{-- ===================== Dernières notes ===================== --}}
        <flux:card>

            <div class="mb-6 flex items-center justify-between">

                <h2 class="text-lg font-semibold">

                    Dernières notes

                </h2>

                <flux:badge color="green">

                    {{ count($dernieresNotes) }}

                </flux:badge>

            </div>

            <flux:table>

                <flux:table.columns>

                    <flux:table.column>
                        Module
                    </flux:table.column>

                    <flux:table.column>
                        Note
                    </flux:table.column>

                    <flux:table.column>
                        Date
                    </flux:table.column>

                </flux:table.columns>

                <flux:table.rows>

                    @forelse($dernieresNotes as $note)
                        <flux:table.row>

                            <flux:table.cell>

                                {{ $note['module'] }}

                            </flux:table.cell>

                            <flux:table.cell>

                                <span class="font-semibold">

                                    {{ $note['note'] }}/20

                                </span>

                            </flux:table.cell>

                            <flux:table.cell>

                                {{ $note['date'] }}

                            </flux:table.cell>

                        </flux:table.row>

                    @empty

                        <flux:table.row>

                            <flux:table.cell colspan="3">

                                <div class="py-6 text-center text-zinc-500">

                                    Aucune note disponible.

                                </div>

                            </flux:table.cell>

                        </flux:table.row>
                    @endforelse

                </flux:table.rows>

            </flux:table>

        </flux:card>

    </div>

@endif
