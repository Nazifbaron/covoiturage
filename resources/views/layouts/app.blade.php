<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Covoiturage') }}</title>

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
                        // ── Palette logo ──────────────────────────────
                        // Violet principal (logo icon)
                        "primary":       "#6C2BD9",
                        "primary-light": "#8B5CF6",
                        "primary-dark":  "#4C1D95",

                        // Orange-rouge (route + texte logo)
                        "accent":        "#E8470A",
                        "accent-light":  "#F97316",

                        // Fonds
                        "background-light": "#F8F7FF",   // blanc teinté violet très léger
                        "background-dark":  "#0F0A1E",   // quasi-noir violet foncé
                        "sidebar-dark":     "#150D2E",   // violet très foncé
                        "card-dark":        "#1E1040",   // violet foncé carte
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                },
            },
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Material Symbols */
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
            .material-symbols-outlined.text-xs  { font-size: 12px; }
            .material-symbols-outlined.text-sm  { font-size: 16px; }
            .material-symbols-outlined.text-base{ font-size: 20px; }
            .material-symbols-outlined.text-lg  { font-size: 24px; }
            .material-symbols-outlined.text-xl  { font-size: 28px; }
            .material-symbols-outlined.text-2xl { font-size: 32px; }
            .material-symbols-outlined.text-3xl { font-size: 40px; }
            .material-symbols-outlined.text-4xl { font-size: 48px; }
        }

        /* Sidebar links */
        .sidebar-link {
            position: relative;
            transition: all 0.2s ease;
        }
        .sidebar-link.active {
            background: rgba(108, 43, 217, 0.12);
            color: #6C2BD9;
        }
        .dark .sidebar-link.active {
            background: rgba(139, 92, 246, 0.15);
            color: #A78BFA;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: linear-gradient(to bottom, #6C2BD9, #E8470A);
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link:hover:not(.active) {
            background: rgba(108, 43, 217, 0.06);
        }
        .dark .sidebar-link:hover:not(.active) {
            background: rgba(255,255,255,0.05);
        }

        /* Overlay mobile */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15, 10, 30, 0.6);
            z-index: 40;
        }
        #sidebar-overlay.show { display: block; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: rgba(108, 43, 217, 0.25);
            border-radius: 4px;
        }

        /* Gradient logo text */
        .logo-gradient {
            background: linear-gradient(135deg, #6C2BD9 0%, #E8470A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    {{ $styles ?? '' }}
    @hasSection('styles') @yield('styles') @endif
    @stack('styles')
</head>

@include('components.toast')

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
                  border-r border-violet-100 dark:border-violet-900/30
                  flex flex-col
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-4 border-b border-violet-100 dark:border-violet-900/30">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Logo Covoiturage"
                 class="w-9 h-9 rounded-xl object-contain flex-shrink-0
                        ring-2 ring-violet-200 dark:ring-violet-800">
            <span class="font-black text-base tracking-tight logo-gradient">
                Covoiturage Bénin
            </span>
        </div>

        {{-- User info --}}
        <div class="px-4 py-3 border-b border-violet-100 dark:border-violet-900/30">
            <div class="flex items-center gap-3 p-2.5 rounded-xl
                        bg-violet-50 dark:bg-violet-900/20
                        border border-violet-100 dark:border-violet-800/30">
                <div class="w-9 h-9 rounded-full
                            bg-gradient-to-br from-primary to-accent
                            flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="text-white font-black text-sm">
                        {{ strtoupper(substr(Auth::user()->first_name ?? '?', 0, 1)) }}
                    </span>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate text-slate-900 dark:text-white">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                    @if(Auth::user()->role === 'driver')
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                 bg-violet-100 text-violet-700 dark:bg-violet-800/40 dark:text-violet-300">
                        <span class="material-symbols-outlined" style="font-size:11px">directions_car</span>
                        Conducteur
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                 bg-orange-100 text-orange-700 dark:bg-orange-800/30 dark:text-orange-300">
                        <span class="material-symbols-outlined" style="font-size:11px">airline_seat_recline_normal</span>
                        Passager
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}
                      flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                      text-slate-700 dark:text-slate-300">
                <span class="material-symbols-outlined text-xl {{ request()->routeIs('dashboard') ? 'filled' : '' }}">grid_view</span>
                Tableau de bord
            </a>

            {{-- ── Passager ── --}}
            @if(Auth::user()->role === 'passenger')
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Trajets</p>
                </div>

                <a href="{{ route('passenger.trips') }}"
                   class="sidebar-link {{ request()->routeIs('passenger.trips') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">search</span>
                    Rechercher un trajet
                </a>

                <a href="{{ route('passenger.showtrips') }}"
                   class="sidebar-link {{ request()->routeIs('passenger.showtrips') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Proposer un trajet
                </a>

                <a href="{{ route('passenger.my-requests') }}"
                   class="sidebar-link {{ request()->routeIs('passenger.my-requests') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">bookmark</span>
                    Mes réservations
                </a>

                <a href="{{ route('messages.index') }}"
                   class="sidebar-link {{ request()->routeIs('messages.index') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">message</span>
                    Messages
                </a>
                
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Compte</p>
                </div>

                <a href="#"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                    Mon solde
                </a>
            @endif

            {{-- ── Conducteur ── --}}
            @if(Auth::user()->role === 'driver')
                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Mes trajets</p>
                </div>

                <a href="{{ route('driver.trips.create') }}"
                   class="sidebar-link {{ request()->routeIs('driver.trips.create') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Publier un trajet
                </a>

                <a href="{{ route('driver.my-trips') }}"
                   class="sidebar-link {{ request()->routeIs('driver.my-trips') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">directions_car</span>
                    Trajets publiés
                </a>

                <a href="{{ route('driver.requests') }}"
                   class="sidebar-link {{ request()->routeIs('driver.requests') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                    Demandes
                </a>

                <a href="{{ route('messages.index') }}"
                   class="sidebar-link {{ request()->routeIs('messages.index') ? 'active' : '' }}
                          flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">message</span>
                    Messages
                </a>

                <div class="pt-3 pb-1 px-3">
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Finances</p>
                </div>

                <a href="#"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">payments</span>
                    Mes gains
                </a>
            @endif

            <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Paramètres</p>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}
                      flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                      text-slate-700 dark:text-slate-300">
                <span class="material-symbols-outlined text-xl">manage_accounts</span>
                Mon profil
            </a>
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-violet-100 dark:border-violet-900/30">
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
                       border-b border-violet-100 dark:border-violet-900/30 z-30">

            <button onclick="openSidebar()"
                    class="lg:hidden p-2 rounded-lg hover:bg-violet-50 dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">menu</span>
            </button>

            @isset($header)
                <div class="hidden lg:flex items-center">{{ $header }}</div>
            @endisset

            <div class="flex items-center gap-2 lg:gap-3 ml-auto">
                {{-- Theme toggle --}}
                <button id="themeToggle"
                        class="p-2 rounded-lg bg-slate-100 dark:bg-white/10
                               hover:bg-violet-100 dark:hover:bg-violet-900/30 transition-colors">
                    <span class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300">light_mode</span>
                </button>

                {{-- Notifications --}}
                <button class="relative p-2 rounded-lg bg-slate-100 dark:bg-white/10
                               hover:bg-violet-100 dark:hover:bg-violet-900/30 transition-colors">
                    <span class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300">notifications</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full
                                 bg-gradient-to-br from-primary to-accent"></span>
                </button>

                {{-- Avatar --}}
                <a href="{{ route('profile.edit') }}"
                   class="w-9 h-9 rounded-full
                          bg-gradient-to-br from-primary to-accent
                          flex items-center justify-center
                          shadow-md shadow-violet-200 dark:shadow-violet-900/30
                          ring-2 ring-violet-200 dark:ring-violet-800/50
                          hover:ring-4 transition-all">
                    <span class="text-white font-black text-sm">
                        {{ strtoupper(substr(Auth::user()?->first_name ?? '?', 0, 1)) }}
                    </span>
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-background-dark p-4 lg:p-6">
            {{ $slot ?? '' }}
            @hasSection('content')
                @yield('content')
            @endif
        </main>
    </div>
</div>

<script>
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        themeToggle.querySelector('.material-symbols-outlined').textContent =
            document.documentElement.classList.contains('dark') ? 'dark_mode' : 'light_mode';
    });
    if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        themeToggle.querySelector('.material-symbols-outlined').textContent = 'dark_mode';
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

{{ $scripts ?? '' }}
@hasSection('scripts') @yield('scripts') @endif
@stack('scripts')

</body>
</html>
