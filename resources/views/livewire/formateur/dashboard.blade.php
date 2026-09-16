<div>
 {{-- <div class="div">  <livewire:notifications-bell /></div> --}}

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

            <flux:icon.book-open class="size-10 text-blue-600"/>
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

            <flux:icon.calendar class="size-10 text-green-600"/>
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

            <flux:icon.users class="size-10 text-violet-600"/>
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

            <flux:icon.clipboard-document-check class="size-10 text-red-600"/>
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

            @foreach($modules as $module)

                <div>

                    <div class="mb-2 flex justify-between">

                        <span class="font-medium">
                            {{ $module['nom'] }}
                        </span>

                        <span>
                            {{ $module['progression'] }}%
                        </span>

                    </div>

                    <flux:progress
                        value="{{ $module['progression'] }}"
                        color="green"
                    />

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
