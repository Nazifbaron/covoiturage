<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: '#13ec49',
                        'brand-dark': '#0db83a',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Material Symbols */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0;
            font-size: 20px;
            line-height: 1;
            display: inline-block;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Sidebar nav links — PAS de @apply, CSS pur */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            width: 100%;
        }
        .nav-link:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .nav-link.active {
            background: #111827;
            color: #ffffff;
        }
        .nav-link.active .material-symbols-outlined {
            color: #13ec49;
        }
        .nav-link-danger {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #ef4444;
            transition: background 0.15s;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }
        .nav-link-danger:hover { background: #fef2f2; }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Overlay mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
        }
        #sidebar-overlay.show { display: block; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Overlay mobile --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex min-h-screen">

    {{-- ══════════════════════════════
         SIDEBAR
    ══════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed lg:relative inset-y-0 left-0 z-50 lg:z-auto
                  w-64 flex-shrink-0
                  bg-white border-r border-gray-200
                  flex flex-col
                  -translate-x-full lg:translate-x-0
                  transition-transform duration-300">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-brand" style="font-size:18px;color:#13ec49">shield</span>
                </div>
                <div>
                    <p class="font-bold text-sm text-gray-900 leading-tight">Admin Panel</p>
                    <p class="text-xs text-gray-400">Covoiturage Bénin</p>
                </div>
            </div>
            {{-- Bouton fermer (mobile) --}}
            <button onclick="closeSidebar()" class="lg:hidden p-1 rounded-lg hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-500" style="font-size:20px">close</span>
            </button>
        </div>

        {{-- Info admin --}}
        <div class="px-4 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                @else
                <div class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->first_name ?? 'A', 0, 1)) }}
                    </span>
                </div>
                @endif
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                    <span class="badge bg-gray-100 text-gray-500">Administrateur</span>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-3">Navigation</p>

            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">grid_view</span>
                    <span>Tableau de bord</span>
                </a>

                @if(Auth::user()->is_super_admin || Auth::user()->can('manage_users'))
                <a href="{{ route('admin.users') }}"
                   class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">group</span>
                    <span>Utilisateurs</span>
                </a>
                @endif

                @if(Auth::user()->is_super_admin || Auth::user()->can('manage_trips'))
                <a href="{{ route('admin.trips') }}"
                   class="nav-link {{ request()->routeIs('admin.trips') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">directions_car</span>
                    <span>Trajets</span>
                </a>
                @endif

                @if(Auth::user()->is_super_admin || Auth::user()->can('manage_reservations'))
                <a href="{{ route('admin.reservations') }}"
                   class="nav-link {{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">bookmark</span>
                    <span>Réservations</span>
                </a>
                @endif

                @if(Auth::user()->is_super_admin || Auth::user()->can('manage_vehicles'))
                <a href="{{ route('admin.vehicles') }}"
                   class="nav-link {{ request()->routeIs('admin.vehicles') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">two_wheeler</span>
                    <span>Véhicules</span>
                </a>
                @endif

                @if(Auth::user()->is_super_admin)
                <div class="pt-2 pb-1">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Administration</p>
                </div>
                <a href="{{ route('admin.admins.list') }}"
                   class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined" style="font-size:20px">manage_accounts</span>
                    <span>Gérer les admins</span>
                </a>
                @endif

            </div>
        </nav>

        {{-- Déconnexion --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link-danger">
                    <span class="material-symbols-outlined" style="font-size:20px">logout</span>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════
         CONTENU PRINCIPAL
    ══════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                {{-- Burger menu (mobile) --}}
                <button onclick="openSidebar()"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-gray-600" style="font-size:22px">menu</span>
                </button>
                <div>
                    <h1 class="text-base lg:text-lg font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 hidden sm:block">@yield('page-subtitle', '')</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="material-symbols-outlined" style="font-size:14px">schedule</span>
                <span class="hidden sm:inline">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-4 lg:mx-6 mt-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
            <span class="material-symbols-outlined text-green-500" style="font-size:18px">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-4 lg:mx-6 mt-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            <span class="material-symbols-outlined text-red-500" style="font-size:18px">error</span>
            {{ session('error') }}
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.add('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.remove('show');
    }
</script>
@stack('scripts')
</body>
</html>
