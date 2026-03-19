<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compte suspendu — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'background-dark': '#102215',
                        'card-dark': '#152b1a',
                        'primary': '#13ec49',
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0;
            line-height: 1; display: inline-block;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-background-dark min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-200 dark:border-white/10
                    shadow-xl p-8 text-center space-y-6">

            {{-- Icône --}}
            <div class="w-20 h-20 rounded-2xl bg-red-50 dark:bg-red-500/10
                        flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-red-500" style="font-size:40px">block</span>
            </div>

            {{-- Titre --}}
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                    Compte suspendu
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                    Votre compte a été suspendu par un administrateur.<br>
                    Vous n'avez plus accès aux fonctionnalités de la plateforme.
                </p>
            </div>

            {{-- Info contact --}}
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-white/5
                        border border-slate-200 dark:border-white/10 text-left space-y-2">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    Que faire ?
                </p>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    Si vous pensez qu'il s'agit d'une erreur, contactez notre support à
                    <a href="mailto:support@covoiturage.bj"
                       class="font-semibold text-primary hover:underline">
                        support@covoiturage.bj
                    </a>
                </p>
            </div>

            {{-- Infos utilisateur --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 dark:bg-white/5">
                <div class="w-9 h-9 rounded-full bg-slate-300 dark:bg-white/10
                            flex items-center justify-center flex-shrink-0 font-bold text-slate-600 dark:text-white text-sm">
                    {{ strtoupper(substr(Auth::user()->first_name ?? '?', 0, 1)) }}
                </div>
                <div class="text-left min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>

            {{-- Bouton déconnexion --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-slate-900 hover:bg-slate-800 dark:bg-white/10 dark:hover:bg-white/20
                               text-white font-bold py-3 rounded-xl transition-all text-sm">
                    <span class="material-symbols-outlined" style="font-size:18px">logout</span>
                    Se déconnecter
                </button>
            </form>

        </div>

        {{-- Logo bas --}}
        <p class="text-center text-xs text-slate-400 mt-6">
            Covoiturage Bénin — {{ now()->year }}
        </p>
    </div>

</body>
</html>
