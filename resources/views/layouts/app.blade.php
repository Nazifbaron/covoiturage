<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec49",
                        "background-light": "#fbfcfb",
                        "background-dark": "#102215",
                        "sidebar-dark": "#0b1a0f",
                        "card-dark": "#152b1a",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Material Symbols Outlined - font setup */
        @supports (font-variation-settings: normal) {
            .material-symbols-outlined {
                font-family: 'Material Symbols Outlined';
                font-weight: normal;
                font-style: normal;
                font-size: 24px;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                display: inline-block;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0;
            }

            .material-symbols-outlined.filled {
                font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0;
            }

            /* Responsive icon sizes */
            .material-symbols-outlined.text-xs { font-size: 12px; }
            .material-symbols-outlined.text-sm { font-size: 16px; }
            .material-symbols-outlined.text-base { font-size: 20px; }
            .material-symbols-outlined.text-lg { font-size: 24px; }
            .material-symbols-outlined.text-xl { font-size: 28px; }
            .material-symbols-outlined.text-2xl { font-size: 32px; }
            .material-symbols-outlined.text-3xl { font-size: 40px; }
            .material-symbols-outlined.text-4xl { font-size: 48px; }
        }

        .sidebar-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .sidebar-link.active {
            background: rgba(19,236,73,0.12);
            color: #13ec49;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: #13ec49;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link:hover:not(.active) {
            background: rgba(255,255,255,0.05);
        }
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
        }
        #sidebar-overlay.show { display: block; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(19,236,73,0.2); border-radius: 4px; }
    </style>

    {{-- Styles additionnels via slot (x-app-layout) ou @yield (extends) --}}
    {{ $styles ?? '' }}
    @hasSection('styles') @yield('styles') @endif
    {{-- Support for @push/@stack on styles --}}
    @stack('styles')
</head>
@include('components.toast')  {{-- ou le chemin où tu mets le fichier --}}
</body>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 h-screen overflow-hidden font-sans antialiased">
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed lg:relative z-50 lg:z-auto
                  w-64 h-full flex-shrink-0
                  bg-white dark:bg-sidebar-dark
                  border-r border-slate-100 dark:border-primary/10
                  flex flex-col
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-5 py-5 border-b border-slate-100 dark:border-primary/10">
            <div class="text-primary flex-shrink-0">
                <svg class="size-7" fill="currentColor" viewBox="0 0 48 48">
                    <path d="M13.8261 30.5736C16.7203 29.8826 20.2244 29.4783 24 29.4783C27.7756 29.4783 31.2797 29.8826 34.1739 30.5736C36.9144 31.2278 39.9967 32.7669 41.3563 33.8352L24.8486 7.36089C24.4571 6.73303 23.5429 6.73303 23.1514 7.36089L6.64374 33.8352C8.00331 32.7669 11.0856 31.2278 13.8261 30.5736Z"/>
                </svg>
            </div>
            <span class="font-black text-base tracking-tight">Covoiturage Benin</span>
        </div>

        {{-- User info --}}
        <div class="px-4 py-4 border-b border-slate-100 dark:border-primary/10">
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-white/5">
                <div class="w-9 h-9 rounded-full bg-primary/20 border border-primary/30 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary text-lg">person</span>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ Auth::user()->role === 'driver'
                            ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300'
                            : 'bg-primary/15 text-green-700 dark:text-primary' }}">
                        <span class="material-symbols-outlined text-xs">
                            {{ Auth::user()->role === 'driver' ? 'directions_car' : 'airline_seat_recline_normal' }}
                        </span>
                        {{ Auth::user()->role === 'driver' ? 'Conducteur' : 'Passager' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                <span class="material-symbols-outlined text-xl {{ request()->routeIs('dashboard') ? 'filled' : '' }}">grid_view</span>
                Tableau de bord
            </a>

            @if(Auth::user()->role === 'passenger')
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Trajets</p>
                </div>
                <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">search</span>
                    Rechercher un trajet
                </a>
                <a href="{{ route('passenger.showtrips') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Proposer un trajet
                </a>
                <a href="{{ route('passenger.my-requests') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">bookmark</span>
                    Mes réservations
                </a>
                <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">history</span>
                    Historique
                </a>
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Compte</p>
                </div>
                <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                    Mon solde
                </a>
            @endif

            @if(Auth::user()->role === 'driver')
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Mes trajets</p>
                </div>
                <a href="{{ route('driver.create-tips') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Publier un trajet
                </a>
                <a href="{{ route('driver.my-trips') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                    <span class="material-symbols-outlined text-xl">directions_car</span>
                    Trajets publiés
                </a>
                <a href="{{ route('driver.requests') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                    Demandes
                    {{-- <span class="ml-auto bg-primary text-background-dark text-xs font-black px-2 py-0.5 rounded-full">3</span> --}}
                </a>
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Finances</p>
                </div>
                <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                    <span class="material-symbols-outlined text-xl">payments</span>
                    Mes gains
                </a>
            @endif

            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Paramètres</p>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-green-50 hover:text-green-600">
                <span class="material-symbols-outlined text-xl">manage_accounts</span>
                Mon profil
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-slate-100 dark:border-primary/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                           text-slate-500 dark:text-slate-400
                           hover:bg-red-50 hover:text-red-600
                           dark:hover:bg-red-500/10 dark:hover:text-red-400
                           transition-colors">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════
         MAIN AREA
    ══════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Navbar --}}
        <header class="flex-shrink-0 flex items-center justify-between
                       px-4 lg:px-6 py-3
                       bg-white dark:bg-sidebar-dark
                       border-b border-slate-100 dark:border-primary/10 z-30">

            <button onclick="openSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>

            {{-- Header slot (compatible <x-app-layout> avec <x-slot name="header">) --}}
            @isset($header)
                <div class="hidden lg:flex items-center">{{ $header }}</div>
            @endisset

            <div class="flex items-center gap-2 lg:gap-3 ml-auto">
                <button id="themeToggle" class="p-2 rounded-lg bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined text-xl">light_mode</span>
                </button>
                <button class="relative p-2 rounded-lg bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined text-xl">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-primary"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-primary/20 border-2 border-primary/30 flex items-center justify-center">
                    <a href="{{ route('profile.edit') }}">
                        <span class="material-symbols-outlined text-primary text-lg">person</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Page Content
             Supporte les deux syntaxes :
             - <x-app-layout> ... </x-app-layout>  → via $slot
             - @extends('layouts.app') + @section('content') → via @yield
        --}}
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-background-dark p-4 lg:p-6">
            {{ $slot ?? '' }}
            @hasSection('content')
                @yield('content')
            @endif
        </main>
    </div>
</div>


<script>
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    });
    if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('show');
    }
</script>

{{-- Scripts additionnels --}}
{{ $scripts ?? '' }}
@hasSection('scripts') @yield('scripts') @endif
{{-- Support for @push/@stack on scripts --}}
@stack('scripts')

</body>
</html>
