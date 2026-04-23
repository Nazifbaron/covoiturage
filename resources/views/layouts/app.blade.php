<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Covoiturage') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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
                        // ── Palette logo (vert + teal) ─────────────────
                        "primary":       "#16a34a",   // vert-600
                        "primary-light": "#22c55e",   // vert-500
                        "primary-dark":  "#15803d",   // vert-700

                        "accent":        "#0891b2",   // cyan-600 (teal du logo)
                        "accent-light":  "#06b6d4",   // cyan-500

                        // Fonds
                        "background-light": "#f0fdf4",   // vert très clair
                        "background-dark":  "#052e16",   // vert quasi-noir
                        "sidebar-dark":     "#071f0e",   // vert très foncé
                        "card-dark":        "#0a3018",   // vert foncé carte
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
            background: rgba(22, 163, 74, 0.12);
            color: #16a34a;
        }
        .dark .sidebar-link.active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: linear-gradient(to bottom, #16a34a, #0891b2);
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link:hover:not(.active) {
            background: rgba(22, 163, 74, 0.06);
        }
        .dark .sidebar-link:hover:not(.active) {
            background: rgba(255,255,255,0.05);
        }

        /* Overlay mobile */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(5, 46, 22, 0.65);
            z-index: 40;
        }
        #sidebar-overlay.show { display: block; }

        /* Sidebar gradient — same palette as logo section */
        #sidebar {
            background: linear-gradient(160deg, #f0fdf4 0%, #ecfeff 100%);
        }
        .dark #sidebar {
            background: linear-gradient(160deg, rgba(5,46,22,.95) 0%, rgba(8,51,68,.90) 100%);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb {
            background: rgba(22, 163, 74, 0.3);
            border-radius: 4px;
        }

        /* Gradient logo text */
        .logo-gradient {
            background: linear-gradient(135deg, #15803d 0%, #0891b2 100%);
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
                  border-r border-green-100 dark:border-green-900/30
                  flex flex-col
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-4 border-b border-green-100 dark:border-green-900/30
                    bg-gradient-to-r from-green-50 to-cyan-50 dark:from-green-950/60 dark:to-cyan-950/40">
            <div class="w-12 h-12 rounded-2xl bg-white shadow-md shadow-green-200/60 dark:shadow-green-900/40
                        ring-2 ring-green-300 dark:ring-green-700 flex-shrink-0 overflow-hidden p-0.5">
                <img src="{{ asset('images/logoC.jpeg') }}"
                     alt="Logo Covoiturage"
                     class="w-full h-full object-contain rounded-xl">
            </div>
            <div>
                <span class="font-black text-base tracking-tight logo-gradient block leading-none">
                    Covoiturage
                </span>
                <span class="text-[10px] font-semibold text-green-600/70 dark:text-green-400/60 tracking-wider uppercase">
                    Bénin
                </span>
            </div>
        </div>

        {{-- User info --}}
        {{-- <div class="px-4 py-3 border-b border-green-100 dark:border-green-900/30">
            <div class="flex items-center gap-3 p-2.5 rounded-xl
                        bg-green-50 dark:bg-green-900/20
                        border border-green-100 dark:border-green-800/30">
                @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0 shadow-md ring-2 ring-primary/20">
                @else
                <div class="w-9 h-9 rounded-full
                            bg-gradient-to-br from-primary to-accent
                            flex items-center justify-center flex-shrink-0 shadow-md">
                    <span class="text-white font-black text-sm">
                        {{ strtoupper(substr(Auth::user()->first_name ?? '?', 0, 1)) }}
                    </span>
                </div>
                @endif
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate text-slate-900 dark:text-white">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                    @if(Auth::user()->role === 'driver')
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                 bg-green-100 text-green-700 dark:bg-green-800/40 dark:text-green-300">
                        <span class="material-symbols-outlined" style="font-size:11px">directions_car</span>
                        Conducteur
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full
                                 bg-cyan-100 text-cyan-700 dark:bg-cyan-800/30 dark:text-cyan-300">
                        <span class="material-symbols-outlined" style="font-size:11px">airline_seat_recline_normal</span>
                        Passager
                    </span>
                    @endif
                </div>
            </div>
        </div> --}}

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
                    Demander un trajet
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

                <a href="{{ route('driver.earnings') }}"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                          text-slate-700 dark:text-slate-300">
                    <span class="material-symbols-outlined text-xl">payments</span>
                    Mes gains
                </a>
            @endif

            {{-- <div class="pt-3 pb-1 px-3">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Paramètres</p>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}
                      flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold
                      text-slate-700 dark:text-slate-300">
                <span class="material-symbols-outlined text-xl">manage_accounts</span>
                Mon profil
            </a> --}}
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-green-100 dark:border-green-900/30">
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
                       border-b border-green-100 dark:border-green-900/30 z-30">

            <button onclick="openSidebar()"
                    class="lg:hidden p-2 rounded-lg hover:bg-green-50 dark:hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">menu</span>
            </button>

            @isset($header)
                <div class="hidden lg:flex items-center">{{ $header }}</div>
            @endisset

            <div class="flex items-center gap-2 lg:gap-3 ml-auto">
                {{-- Theme toggle --}}
                <button id="themeToggle"
                        class="p-2 rounded-lg bg-slate-100 dark:bg-white/10
                               hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                    <span class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300">light_mode</span>
                </button>

                {{-- Notifications --}}
                <div class="relative" id="notifWrapper">
                    <button id="notifBtn"
                            class="relative p-2 rounded-lg bg-slate-100 dark:bg-white/10
                                   hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                        <span class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300">notifications</span>
                        <span id="notifBadge"
                              class="absolute top-1 right-1 min-w-[16px] h-4 px-0.5 rounded-full hidden
                                     bg-gradient-to-br from-primary to-accent
                                     text-white text-[10px] font-black flex items-center justify-center"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div id="notifDropdown"
                         class="hidden absolute right-0 mt-2 w-80 z-50
                                bg-white dark:bg-card-dark
                                border border-green-100 dark:border-green-900/30
                                rounded-2xl shadow-xl shadow-green-100/50 dark:shadow-black/30
                                overflow-hidden">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3
                                    border-b border-slate-100 dark:border-white/10">
                            <p class="font-black text-sm text-slate-900 dark:text-white">Notifications</p>
                            <div class="flex items-center gap-3">
                                <button id="notifMarkAll"
                                        class="text-xs font-semibold text-primary hover:underline">
                                    Tout lire
                                </button>
                                <button id="notifDeleteAll"
                                        class="text-xs font-semibold text-red-400 hover:text-red-500 hover:underline">
                                    Tout effacer
                                </button>
                            </div>
                        </div>

                        {{-- Liste --}}
                        <div id="notifList" class="max-h-80 overflow-y-auto divide-y divide-slate-50 dark:divide-white/5">
                            <p id="notifEmpty" class="text-center py-10 text-sm text-slate-400 hidden">
                                Aucune notification
                            </p>
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-white/10 text-center">
                            <a href="{{ route('notifications.index') }}"
                               class="text-xs font-semibold text-primary hover:underline">
                                Voir toutes les notifications
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Avatar --}}
                <a href="{{ route('profile.edit') }}"
                   class="block w-9 h-9 rounded-full overflow-hidden
                          shadow-md shadow-green-200 dark:shadow-green-900/30
                          ring-2 ring-green-300 dark:ring-green-700/50
                          hover:ring-4 transition-all flex-shrink-0">
                    @if(Auth::user()?->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                             alt="Avatar"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary to-accent
                                    flex items-center justify-center">
                            <span class="text-white font-black text-sm">
                                {{ strtoupper(substr(Auth::user()?->first_name ?? '?', 0, 1)) }}
                            </span>
                        </div>
                    @endif
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

    // ── Notifications ─────────────────────────────────────────────────────
    (function () {
        const btn      = document.getElementById('notifBtn');
        const dropdown = document.getElementById('notifDropdown');
        const badge    = document.getElementById('notifBadge');
        const list     = document.getElementById('notifList');
        const empty    = document.getElementById('notifEmpty');
        const markAll  = document.getElementById('notifMarkAll');

        let loaded = false;

        const iconColor = {
            check_circle      : 'text-emerald-500',
            hail              : 'text-green-500',
            verified          : 'text-emerald-500',
            cancel            : 'text-red-400',
            notifications     : 'text-slate-400',
        };

        const deleteUrl    = "{{ route('notifications.destroy', '__ID__') }}".replace('__ID__', '__ID__');
        const deleteAllUrl = "{{ route('notifications.destroy-all') }}";
        const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;

        window.deleteNotif = function(id, el) {
            fetch(deleteUrl.replace('__ID__', id), {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => {
                el.closest('.notif-item').remove();
                if (!list.querySelector('.notif-item')) empty.classList.remove('hidden');
                loaded = false;
                fetchNotifs();
            });
        }

        function renderNotif(n) {
            const d    = n.data;
            const icon = d.icon || 'notifications';
            const color= iconColor[icon] || 'text-slate-400';
            const url  = "{{ route('notifications.read', '__ID__') }}".replace('__ID__', n.id);
            return `
            <div class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors
                      ${n.read ? 'opacity-60' : ''}">
                <span class="material-symbols-outlined ${color} mt-0.5 flex-shrink-0" style="font-size:20px">${icon}</span>
                <a href="${url}" class="flex-1 min-w-0">
                    <p class="text-xs font-black text-slate-900 dark:text-white leading-snug">${d.title}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-snug line-clamp-2">${d.body}</p>
                    <p class="text-[10px] text-slate-400 mt-1">${n.created_at}</p>
                </a>
                <div class="flex items-center gap-1 flex-shrink-0 ml-1">
                    ${!n.read ? '<span class="w-2 h-2 rounded-full bg-primary"></span>' : ''}
                    <button onclick="deleteNotif('${n.id}', this)"
                            class="p-1 rounded-lg text-slate-300 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                            title="Supprimer">
                        <span class="material-symbols-outlined" style="font-size:15px">close</span>
                    </button>
                </div>
            </div>`;
        }

        function fetchNotifs() {
            fetch("{{ route('notifications.index') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                // Badge
                const count = data.unread_count;
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }

                // Liste
                const items = data.notifications;
                // Retirer les anciennes entrées
                list.querySelectorAll('.notif-item').forEach(el => el.remove());

                if (items.length === 0) {
                    empty.classList.remove('hidden');
                } else {
                    empty.classList.add('hidden');
                    items.forEach(n => {
                        list.insertAdjacentHTML('beforeend', renderNotif(n));
                    });
                }
                loaded = true;
            })
            .catch(() => {});
        }

        // Ouvrir/fermer
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden');
            if (isHidden && !loaded) fetchNotifs();
        });

        // Fermer en cliquant dehors
        document.addEventListener('click', (e) => {
            if (!document.getElementById('notifWrapper').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Marquer tout lu
        markAll.addEventListener('click', () => {
            fetch("{{ route('notifications.read-all') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => { loaded = false; fetchNotifs(); });
        });

        // Tout effacer
        document.getElementById('notifDeleteAll').addEventListener('click', () => {
            fetch(deleteAllUrl, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
            }).then(() => { loaded = false; fetchNotifs(); });
        });

        // Rafraîchir toutes les 30 secondes
        fetchNotifs();
        setInterval(fetchNotifs, 30000);
    })();

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
