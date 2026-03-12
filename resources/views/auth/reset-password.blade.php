<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Créer un nouveau mot de passe </title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                                "display": ["Inter", "sans-serif"]
                            },
                            borderRadius: {
                                "DEFAULT": "0.25rem",
                                "lg": "0.5rem",
                                "xl": "0.75rem",
                                "full": "9999px"
                            },
                        },
                    },
                }
        </script>
        <style>
                body {
                    font-family: 'Inter', sans-serif;
                }
                .material-symbols-outlined {
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                }
        </style>
    </head>
    <body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display min-h-screen">
        <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
            <div class="layout-container flex h-full grow flex-col">
                <!--
                <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-primary/20 px-6 md:px-20 py-4 bg-background-light dark:bg-background-dark">
                    <div class="flex items-center gap-4 text-slate-900 dark:text-white">
                        <div class="size-8 text-primary">
                            <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M39.5563 34.1455V13.8546C39.5563 15.708 36.8773 17.3437 32.7927 18.3189C30.2914 18.916 27.263 19.2655 24 19.2655C20.737 19.2655 17.7086 18.916 15.2073 18.3189C11.1227 17.3437 8.44365 15.708 8.44365 13.8546V34.1455C8.44365 35.9988 11.1227 37.6346 15.2073 38.6098C17.7086 39.2069 20.737 39.5564 24 39.5564C27.263 39.5564 30.2914 39.2069 32.7927 38.6098C36.8773 37.6346 39.5563 35.9988 39.5563 34.1455Z" fill="currentColor"></path>
                                <path clip-rule="evenodd" d="M10.4485 13.8519C10.4749 13.9271 10.6203 14.246 11.379 14.7361C12.298 15.3298 13.7492 15.9145 15.6717 16.3735C18.0007 16.9296 20.8712 17.2655 24 17.2655C27.1288 17.2655 29.9993 16.9296 32.3283 16.3735C34.2508 15.9145 35.702 15.3298 36.621 14.7361C37.3796 14.246 37.5251 13.9271 37.5515 13.8519C37.5287 13.7876 37.4333 13.5973 37.0635 13.2931C36.5266 12.8516 35.6288 12.3647 34.343 11.9175C31.79 11.0295 28.1333 10.4437 24 10.4437C19.8667 10.4437 16.2099 11.0295 13.657 11.9175C12.3712 12.3647 11.4734 12.8516 10.9365 13.2931C10.5667 13.5973 10.4713 13.7876 10.4485 13.8519ZM37.5563 18.7877C36.3176 19.3925 34.8502 19.8839 33.2571 20.2642C30.5836 20.9025 27.3973 21.2655 24 21.2655C20.6027 21.2655 17.4164 20.9025 14.7429 20.2642C13.1498 19.8839 11.6824 19.3925 10.4436 18.7877V34.1275C10.4515 34.1545 10.5427 34.4867 11.379 35.027C12.298 35.6207 13.7492 36.2054 15.6717 36.6644C18.0007 37.2205 20.8712 37.5564 24 37.5564C27.1288 37.5564 29.9993 37.2205 32.3283 36.6644C34.2508 36.2054 35.702 35.6207 36.621 35.027C37.4573 34.4867 37.5485 34.1546 37.5563 34.1275V18.7877ZM41.5563 13.8546V34.1455C41.5563 36.1078 40.158 37.5042 38.7915 38.3869C37.3498 39.3182 35.4192 40.0389 33.2571 40.5551C30.5836 41.1934 27.3973 41.5564 24 41.5564C20.6027 41.5564 17.4164 41.1934 14.7429 40.5551C12.5808 40.0389 10.6502 39.3182 9.20848 38.3869C7.84205 37.5042 6.44365 36.1078 6.44365 34.1455L6.44365 13.8546C6.44365 12.2684 7.37223 11.0454 8.39581 10.2036C9.43325 9.3505 10.8137 8.67141 12.343 8.13948C15.4203 7.06909 19.5418 6.44366 24 6.44366C28.4582 6.44366 32.5797 7.06909 35.657 8.13948C37.1863 8.67141 38.5667 9.3505 39.6042 10.2036C40.6278 11.0454 41.5563 12.2684 41.5563 13.8546Z" fill="currentColor" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">Covoiturage Bénin</h2>
                    </div>
                    <div class="flex flex-1 justify-end gap-8 items-center">
                        <button class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-primary text-background-dark text-sm font-bold tracking-[0.015em] hover:opacity-90 transition-opacity">
                            Se connecter
                        </button>
                    </div>
                </header>-->
                <!-- Main Content Container -->
                <main class="flex-1 flex flex-col items-center justify-center px-4 py-12 md:py-20">
                    <div class="w-full max-w-[480px] bg-white dark:bg-primary/5 p-8 rounded-xl shadow-2xl border border-slate-200 dark:border-primary/10">
                        <!-- Title Section -->
                        <div class="flex flex-col gap-3 mb-8">
                            <h1 class="text-slate-900 dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]">Réinitialiser le mot de passe</h1>
                            <p class="text-slate-500 dark:text-primary/70 text-base font-normal leading-relaxed">Sécurisez votre compte en créant un nouveau mot de passe fort.</p>
                        </div>
                        <!-- Form Section -->
                        <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-6" onsubmit="return false;">
                            @csrf
                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <!-- New Password Field -->
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-slate-200 text-sm font-semibold tracking-wide">Nouveau mot de passe</label>
                                <div class="relative flex w-full items-stretch group">
                                    <input id="password" class="form-input flex w-full min-w-0 flex-1 rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-primary/20 bg-slate-50 dark:bg-background-dark/50 h-14 placeholder:text-slate-400 dark:placeholder:text-primary/40 px-4 text-base font-normal leading-normal transition-all" name="password" placeholder="Enter your new password" type="password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-primary/50 hover:text-primary transition-colors" data-icon="visibility" type="button">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Confirm Password Field -->
                            <div class="flex flex-col gap-2">
                                <label class="text-slate-700 dark:text-slate-200 text-sm font-semibold tracking-wide">Confirmer le nouveau mot de passe</label>
                                <div class="relative flex w-full items-stretch group">
                                    <input id="password_confirmation" class="form-input flex w-full min-w-0 flex-1 rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-primary/20 bg-slate-50 dark:bg-background-dark/50 h-14 placeholder:text-slate-400 dark:placeholder:text-primary/40 px-4 text-base font-normal leading-normal transition-all" placeholder="Re-enter your password" type="password" name="password_confirmation" required autocomplete="new-password"/>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-primary/50 hover:text-primary transition-colors" data-icon="visibility" type="button">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>
                            </div>
                            <!-- Requirements Hint -->
                            <ul class="text-xs text-slate-500 dark:text-primary/50 space-y-1 px-1">
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[12px] text-primary">check_circle</span> Au moins 8 caractères
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[12px] text-primary">check_circle</span> Une lettre majuscule
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[12px] text-primary">check_circle</span> Un chiffre ou un symbole
                                </li>
                            </ul>
                            <!-- Action Button -->
                            <button class="mt-4 flex w-full cursor-pointer items-center justify-center rounded-xl h-14 px-6 bg-primary text-background-dark text-lg font-black tracking-wide shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-100 transition-all">
                                Mettre à jour le mot de passe
                            </button>
                        </form>
                        <!-- Bottom Nav / Redirection -->
                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-primary/10 text-center">
                            <p class="text-slate-500 dark:text-primary/50 text-sm font-medium">
                                Vous vous souvenez de votre mot de passe ?
                                <a class="text-primary hover:underline ml-1" href="login">Retourner à la connexion</a>
                            </p>
                        </div>
                    </div>

                    <!-- <div class="mt-8 flex items-center gap-2 text-slate-400 dark:text-primary/30 text-xs">
                        <span class="material-symbols-outlined text-sm">lock</span>
                        <span>Connexion cryptée de bout en bout</span>
                    </div>-->
                </main>
            </div>
        </div>
    </body>
</html>
