<div class="relative" x-data="{ ouvert: false }">
    <button @click="ouvert = !ouvert" @click.outside="ouvert = false"
        class="relative flex items-center justify-center w-9 h-9 rounded-full hover:bg-emerald-50 transition-colors">
        <flux:icon.bell class="w-5 h-5 text-zinc-500" />
        @if ($nombreNonLues > 0)
            <span
                class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold">
                {{ $nombreNonLues > 9 ? '9+' : $nombreNonLues }}
            </span>
        @endif
    </button>

    <div x-show="ouvert" x-cloak
        class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl ring-1 ring-emerald-950/10 z-50 overflow-hidden">
        <div class="flex items-center justify-between p-3 border-b border-zinc-100">
            <span class="font-semibold text-zinc-700 text-sm">Notifications</span>
            @if ($nombreNonLues > 0)
                <button wire:click="toutMarquerCommeLu" class="text-xs text-emerald-600 hover:underline">
                    Tout marquer comme lu
                </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-zinc-100">
            @forelse ($notifications as $notification)
                <div wire:click="marquerCommeLue('{{ $notification->id }}')"
                    @class([
                        'p-3 cursor-pointer hover:bg-emerald-50/60 transition-colors',
                        'bg-emerald-50/40' => is_null($notification->read_at),
                    ])>
                    <p class="text-sm font-medium text-zinc-800">{{ $notification->data['titre'] ?? 'Notification' }}
                    </p>
                    <p class="text-xs text-zinc-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    <p class="text-[11px] text-zinc-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <div class="p-6 text-center text-zinc-400 text-sm">Aucune notification.</div>
            @endforelse
        </div>
    </div>
</div>
