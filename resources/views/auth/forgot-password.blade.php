<!DOCTYPE html>
<html  lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Demande de mot de passe oublié</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <script id="tailwind-config">
                    tailwind.config = {
                        darkMode: "class",
                        theme: {
                            extend: {
                                colors: {
                                    "primary": "#13ec49",
                                    "background-light": "#f6f8f6",
                                    "background-dark": "#102215",
                                },
                                fontFamily: {
                                    "display": ["Inter"]
                                },
                                borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                            },
                        },
                    }
        </script>
    </head>
    <body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">
        <!--<header class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-primary/10 bg-background-light dark:bg-background-dark/50 backdrop-blur-md sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary rounded-lg text-background-dark">
                    <span class="material-symbols-outlined block">directions_car</span>
                </div>
                <a href="/">
                    <h2 class="text-xl font-bold tracking-tight">Covoiturage Benin</h2>
                </a>
            </div>
        </header>-->
        <main class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <div class="bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-primary/10 rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-48 w-full bg-gradient-to-br from-primary/20 to-background-dark relative flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 opacity-20" data-alt="Abstract geometric patterns representing security and connectivity" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBfVy_SpUStZVWaAsY3zd9r4JNHgQgBAsNJbmGRIDOr4w41hZ4d-f1VP1E5ZvDeczzuxMg-Bkv3Gv_D5Rl72M5jzTnOY4ob2otxeFcnI9FI8davoB_X9NbehKUONbF_trMaGgr1yHkqS5eaCslGbdW6gNESxZQUrmiQQavcm_FoiWkQaQDm-qBTYXg-Nl73sCP_6wCkJ576yIgj01BHD_DlCRQku_lWKf1fm3Z-VnBD_v9JmPffEtpW9vKdLOXELvRn90g9fX3Cu-c4");'></div>
                        <div class="relative z-10 p-4 bg-white dark:bg-background-dark rounded-full shadow-xl">
                            <span class="material-symbols-outlined text-primary text-5xl">lock_reset</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="text-center mb-8">
                            <h1 class="text-2xl font-bold mb-2">Mot de passe oublié</h1>
                            <p class="text-slate-600 dark:text-slate-400">
                                Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                            </p>
                        </div>

                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form class="space-y-6" onsubmit="return false;" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1" for="email">
                                    Adresse électronique
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-xl">mail</span>
                                    </div>
                                    <input class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-primary/20 rounded-lg text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all" id="email" name="email" placeholder="name@example.com" required="autofocus" type="email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                            <button class="w-full py-4 bg-primary text-background-dark font-bold text-lg rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90 active:scale-[0.98] transition-all flex items-center justify-center gap-2" type="submit">
                                <span>Send Reset Link</span>
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </form>
                        <div class="mt-8 text-center">
                            <a class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors" href="login">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                                Retour à la connexion
                            </a>
                        </div>
                    </div>
                </div>
                <!--<footer class="mt-8 text-center text-xs text-slate-500 dark:text-slate-500 uppercase tracking-widest">
                    © 2026 Équipe de sécurité Covoiturage Bénin
                </footer>-->
            </div>
        </main>
    </body>
</html>
