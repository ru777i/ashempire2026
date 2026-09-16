<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>
            <livewire:team-switcher />
        <flux:sidebar.nav>

            <flux:sidebar.group :heading="__('Platform')" class="grid">
                @if (Auth::check() && Auth::user()->role == 'secretaire')
                    <flux:sidebar.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="user-circle" :href="route('formateurs')"
                        :current="request()->routeIs('formateurs')" wire:navigate>
                        {{ __('Formateurs') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar" :href="route('sessions')"
                        :current="request()->routeIs('sessions')" wire:navigate>
                        {{ __('Sessions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('inscriptions')"
                        :current="request()->routeIs('inscriptions')" wire:navigate>
                        {{ __('Inscriptions') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="academic-cap" :href="route('formations')"
                        :current="request()->routeIs('formations')" wire:navigate>
                        {{ __('Formations') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="hand-raised" :href="route('presencesIndex')"
                        :current="request()->routeIs('presencesIndex')" wire:navigate>
                        {{ __('Presences') }}
                    </flux:sidebar.item>
                @endif



                @if (Auth::check() && Auth::user()->role == 'formateur')
 <flux:sidebar.item icon="home" :href="route('dashboardFormateur')"
                        :current="request()->routeIs('dashboardFormateurd')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="hand-raised" :href="route('addPresence')"
                        :current="request()->routeIs('addPresence')" wire:navigate>
                        {{ __('Absences') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="squares-2x2" :href="route('createNote')"
                        :current="request()->routeIs('createNote')" wire:navigate>
                        {{ __('Evaluations') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="square-3-stack-3d" :href="route('formateurModule')"
                        :current="request()->routeIs('formateurModule')" wire:navigate>
                        {{ __('Mes modules') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="chart-bar" :href="route('avancement')"
                        :current="request()->routeIs('avancement')" wire:navigate>
                        {{ __('Avancements') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="user" :href="route('mesApprenants')"
                        :current="request()->routeIs('mesApprenants')" wire:navigate>
                        {{ __('Mes etudiants') }}
                    </flux:sidebar.item>
                @endif
                @if (Auth::check() && Auth::user()->role == 'apprenant')
 <flux:sidebar.item icon="home" :href="route('dashboardAprenant')"
                        :current="request()->routeIs('dashboardAprenant')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                 <flux:sidebar.item icon="newspaper" :href="route('mesNotes')"
                        :current="request()->routeIs('mesNotes')" wire:navigate>
                        {{ __('Mes Notes') }}
                    </flux:sidebar.item>
                      <flux:sidebar.item icon="hand-raised" :href="route('mesPresences')"
                        :current="request()->routeIs('mesPresences')" wire:navigate>
                        {{ __('Mes Presences') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="square-3-stack-3d" :href="route('formateurModule')"
                        :current="request()->routeIs('formateurModule')" wire:navigate>
                        {{ __('Mes modules') }}
                    </flux:sidebar.item>
                     <flux:sidebar.item icon="swatch" :href="route('mesRecours')"
                        :current="request()->routeIs('mesRecours')" wire:navigate>
                        {{ __('Recours') }}
                    </flux:sidebar.item>
                @endif
 <flux:sidebar.item icon="calendar" :href="route('edtCreate')"
                    :current="request()->routeIs('edtCreate')" wire:navigate>
                    {{ __('Emploi du temps') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="calendar" :href="route('sessions')" :current="request()->routeIs('sessions')"
                    wire:navigate>
                    {{ __('Sessions') }}
                </flux:sidebar.item>
                  <flux:sidebar.item icon="megaphone" :href="route('mesRecours')"
                        :current="request()->routeIs('mesRecours')" wire:navigate>
                        {{ __('Annonces') }}
                    </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>


        <flux:spacer />

        {{-- <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav> --}}

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    <livewire:create-team-modal />

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
