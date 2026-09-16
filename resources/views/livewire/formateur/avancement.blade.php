<div>
    <div class="">
        <flux:heading size="xl">Formateurs</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#" icon="home" class=" my-3" />
            <flux:breadcrumbs.item href="#">Avancements des module</flux:breadcrumbs.item>

        </flux:breadcrumbs>
    </div>
    @foreach ($sessionModules as $sessionModule)
        <flux:card class="mb-4 grid grid-cols-1 lg:grid-cols-2 items-center">

            <div class="flex justify-between items-center">
                <div class=" flex items-center">
                    <h3 class="font-bold">
                        {{ $sessionModule->module->nom }} Session : {{ $sessionModule->sessionn->code }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ round($sessionModule->progress,2) }}
                        /
                        {{ $sessionModule->module->volumeHoraire }} heures
                    </p>
                </div>

                <span class="font-bold">
                    {{ round(($sessionModule->progress / $sessionModule->module->volumeHoraire) * 100, 2) }}%
                </span>
            </div>

            @php
                $pourcentage = round(($sessionModule->progress / $sessionModule->module->volumeHoraire) * 100, 2);

                $couleur = match (true) {
                    $pourcentage < 25 => 'red',
                    $pourcentage < 50 => 'orange',
                    $pourcentage < 75 => 'yellow',
                    $pourcentage < 100 => 'blue',
                    default => 'green',
                };
            @endphp

            {{-- <flux:progress color="{{ $couleur }}" value="{{ $pourcentage }}" /> --}}
            <div class="w-full bg-gray-200 rounded-full h-3 mt-3">
                {{-- <flux:progress color="green" value=" {{ $sessionModule->progress/$sessionModule->module->volumeHoraire*100 }}" /> --}}
                <div class="bg-{{ $couleur }}-500 h-3 rounded-full" style="width:{{ $pourcentage }}%">
                </div>

            </div>


            <div class="mt-2">

                <span class="text-sm">
                    Statut :
                    {{ $sessionModule->statut }}
                </span>

            </div>


        </flux:card>
    @endforeach
</div>
