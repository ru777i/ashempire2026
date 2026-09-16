<div class="space-y-6">
 <div class="div">  <livewire:notifications-bell /></div>
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
                <flux:progress
                    value="{{ $progression }}"
                    color="green"
                />
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

                                @if(!empty($module['formateur']))
                                    <p class="text-xs text-zinc-500">
                                        {{ $module['formateur'] }}
                                    </p>
                                @endif

                            </div>

                            <span class="font-semibold">
                                {{ $module['progression'] }}%
                            </span>

                        </div>

                        <flux:progress
                            value="{{ $module['progression'] }}"
                            color="green"
                        />

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

                        @if(isset($evaluation['heure']))
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
