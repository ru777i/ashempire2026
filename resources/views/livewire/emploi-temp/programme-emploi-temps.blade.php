<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Programme de l'emploi du temps</h3>
                <p class="text-sm text-slate-500">Consultation des séances par jour</p>
            </div>
            <div class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                {{ $this->seances->count() }} séance(s)
            </div>
        </div>
    </div>

    <div class="grid gap-4 p-4 xl:grid-cols-5">
        @foreach ($days as $day)
            @php
                $daySeances = $this->seances->filter(fn($item) => $item->jour === $day)->sortBy('heureDebut');
            @endphp

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                <div class="mb-3 border-b border-slate-200 pb-2">
                    <h4 class="font-semibold text-slate-800">{{ $day }}</h4>
                    <p class="text-xs text-slate-500">{{ $daySeances->count() }} séance(s)</p>
                </div>

                <div class="space-y-2">
                    @forelse ($daySeances as $seance)
                        <flux:modal.trigger name="{{  Auth::user()->role=='secretaire' ? 'Seance': ''}}" class="m-4">
                            <button type="button" wire:click="{{ Auth::user()->role=='secretaire' ? 'editSeance( '.$seance->id.' )':''}}"
                                class="w-full rounded-lg border border-blue-200 bg-white p-3 text-left text-xs text-slate-700 shadow-sm transition hover:border-blue-400 hover:shadow-md">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="font-semibold text-slate-800">{{ $seance->module->nom ?? 'Module' }}</span>
                                    <span
                                        class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                        {{ $seance->type }}
                                    </span>
                                </div>
                                <div class="mt-1 text-slate-600">{{ $seance->heureDebut }} - {{ $seance->heureFin }}
                                </div>
                                <div class="text-slate-600">Salle: {{ $seance->salle->nom ?? '—' }}</div>
                                <div class="text-slate-600">Session: {{ $seance->sess->code  }}</div>
                                <div class="text-slate-600">Formation: {{ $seance->sess->formation->nom ?? '—' }}
                                </div>
                                <div class="text-slate-600">Formateur: {{ $seance->formateur->user->name ?? '—' }}
                                </div>
                            </button>
                        </flux:modal.trigger>
                    @empty
                        <div
                            class="rounded-lg border border-dashed border-slate-300 bg-slate-100 px-3 py-4 text-center text-sm text-slate-500">
                            Aucune séance
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
