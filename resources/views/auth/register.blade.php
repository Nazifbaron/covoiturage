<!DOCTYPE html>

    <html  lang="en">
        <head>
            <meta charset="utf-8"/>
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <title>Se Connectez</title>
            <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
            <script id="tailwind-config">
                    tailwind.config = {
                        darkMode: "class",
                        theme: {
                            extend: {
                                colors: {
                                    "primary": "#13ec49",
                                    "background-light": "#fbfcfb",
                                    "background-dark": "#102215",
                                },
                                fontFamily: {
                                    "display": ["Plus Jakarta Sans", "sans-serif"]
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
                        font-family: 'Plus Jakarta Sans', sans-serif;
                    }
                    .material-symbols-outlined {
                        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                    }
                </style>
        </head>
        <body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-300">
            <!-- Top Navigation Bar -->
            <header class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-primary/10 bg-background-light dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
                <div class="flex items-center gap-2">
                    <div class="text-primary">
                        <svg class="size-8" fill="currentColor" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.8261 30.5736C16.7203 29.8826 20.2244 29.4783 24 29.4783C27.7756 29.4783 31.2797 29.8826 34.1739 30.5736C36.9144 31.2278 39.9967 32.7669 41.3563 33.8352L24.8486 7.36089C24.4571 6.73303 23.5429 6.73303 23.1514 7.36089L6.64374 33.8352C8.00331 32.7669 11.0856 31.2278 13.8261 30.5736Z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-extrabold tracking-tight">Covoiturage Benin</h2>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 rounded-lg bg-slate-200 dark:bg-primary/10 hover:bg-slate-300 dark:hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined block">light_mode</span>
                    </button>
                    <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-primary/20 border border-slate-300 dark:border-primary/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-500 dark:text-primary/70">person</span>
                    </div>
                </div>
            </header>
            <!-- Main Content Split Layout -->
            <main class="flex-1 flex overflow-hidden">
                <!-- Left Side: Image Content -->
                <div class="hidden lg:flex flex-1 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-background-dark/80 z-10"></div>
                        <img alt="Friends laughing during a road trip" class="absolute inset-0 w-full h-full object-cover" data-alt="Group of friends traveling together in a car laughing" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqczn5CrwTfEEywQ63_5B65FvqTlPYrHokjnhU1DGPvdLDMun-jPzPjA-bhuI3NdJYMkM0_FAPdiZcqMV1jrl5MtWmkIvPDWdgiVz1azlqXXqWpTXCe4Gz1UGxNULlO-s0n_DltVvD7LM1pE6x4zcKx20bb8CjO6v73z2z1llvGMC_Hr0RTPbMJCnCAgmqEPyCf2dmZa1MozcR_DZbf8XsZRSi_GwarqRrdd8ltUyS_NYvZLRVcgsSr_gCI_eDAbzUfvCGBZu04t7Q"/>
                        <div class="relative z-20 self-end p-16 max-w-2xl">
                            <h1 class="text-5xl font-black text-white leading-tight mb-6">
                                Voyager ensemble, <br/>
                                <span class="text-primary">Économisez mieux.</span>
                            </h1>
                            <p class="text-slate-200 text-xl font-medium leading-relaxed">
                                Rejoignez des milliers de navetteurs qui partagent leurs trajets quotidiens. Réduisez votre empreinte carbone et faites-vous de nouveaux amis sur la route.
                            </p>
                        </div>
                    </div>
                        <!-- Right Side: Login Form -->
                    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-24 bg-background-light dark:bg-background-dark">
                        <div class="w-full max-w-[440px] space-y-8">
                            <div class="space-y-2">
                                <h2 class="text-3xl font-black tracking-tight">Créer un compte</h2>
                                <p class="text-slate-600 dark:text-slate-400">Rejoignez des milliers de navetteurs qui partagent leurs trajets chaque jour.</p>
                            </div>
                            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                                    @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-2">
                                        <x-input type="text" name="first_name" label="Prénom" placeholder="Toni" :required="true" />
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <x-input type="text" name="last_name" label="Nom" placeholder="Dossou" :required="true" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <x-input type="email" name="email" label="Email Address" placeholder="toni.dossou@example.com" :required="true" />
                                </div>
                                <div class="flex flex-col gap-2">
                                    <x-input type="tel" name="phone" label="Téléphone" placeholder="+229 01 00 00 00" :required="true" />
                                </div>
                                <div class="flex flex-col gap-2">
                                    <x-input type="password" name="password" label="Password" placeholder="••••••••" :required="true" />
                                </div>
                                <div class="flex flex-col gap-2">
                                    <x-input type="password" name="password_confirmation" label="Confirmer Password" placeholder="••••••••" :required="true" />
                                </div>
                                <!-- Terms & Conditions -->
                                <div class="flex items-start gap-3 py-2">
                                    <div class="flex items-center h-5">
                                        <input class="h-5 w-5 rounded border-white/10 bg-white/5 text-primary focus:ring-primary focus:ring-offset-background-dark" id="terms" type="checkbox"/>
                                    </div>
                                    <label class="text-sm text-slate-400 leading-tight" for="terms">
                                        En m'inscrivant, j'accepte les <a class="text-primary hover:underline" href="#">Conditions d'utilisation</a> et <a class="text-primary hover:underline" href="#">Politique de confidentialité</a>.
                                    </label>
                                </div>
                                <!-- Submit Button -->
                                <button class="w-full bg-primary hover:bg-primary/90 text-background-dark py-3 rounded-xl font-bold text-lg transition-all shadow-lg shadow-primary/10 flex items-center justify-center gap-2" type="submit">
                                    Créer un compte
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </button>
                            </form>

            <!-- Divider -->
            <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200 dark:border-primary/10"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-background-light dark:bg-background-dark px-4 text-slate-500 font-bold tracking-widest">Ou continuer avec</span>
            </div>
            </div>
            <!-- Social Logins -->
            <div class="grid grid-cols-2 gap-4">
            <button class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-slate-200 dark:border-primary/20 hover:bg-slate-50 dark:hover:bg-primary/10 transition-colors font-semibold">
            <svg class="w-5 h-5" viewbox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
            </svg>
                                    Google
                                </button>
            <button class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-slate-200 dark:border-primary/20 hover:bg-slate-50 dark:hover:bg-primary/10 transition-colors font-semibold">
            <svg class="w-5 h-5 fill-current" viewbox="0 0 24 24">
            <path d="M17.05 20.28c-.96.95-2.03 2.02-3.41 2.02-1.34 0-1.78-.82-3.33-.82-1.55 0-2.04.82-3.32.82-1.36 0-2.48-1.12-3.41-2.02C1.56 18.25 0 14.93 0 11.75c0-4.63 2.99-7.07 5.9-7.07 1.54 0 2.92.93 3.86.93.94 0 2.44-.93 4.19-.93 1.48 0 3.35.63 4.54 2.16-3.08 1.51-2.58 5.86.56 7.15-1.01 2.51-2.18 5.46-3.15 7.15zM12.03 3.51C11.39 1.43 13.04 0 13.04 0s1.77.12 2.68 1.19c.92 1.07.72 2.32.72 2.32s-1.8.21-3.41-.01z"></path>
            </svg>
                                    Apple
                                </button>
            </div>
            <!-- Footer Text -->
            <p class="text-center text-sm text-slate-600 dark:text-slate-400">
                                Déjà inscrit ?

        <a href="login" class="text-primary font-semibold hover:underline">
            Connectez-vous
        </a>
            </p>
            </div>
            </div>
    </main>
    <!-- Simple Footer -->
    <footer class="px-6 py-4 bg-background-light dark:bg-background-dark border-t border-slate-200 dark:border-primary/10 flex justify-between items-center text-xs text-slate-500">
    <div>© 2026 Tous droits réservés.</div>
    <div class="flex gap-4">
    <a class="hover:text-primary transition-colors" href="#">Politique de confidentialité</a>
    <a class="hover:text-primary transition-colors" href="#">Conditions d'utilisation</a>
    </div>
    </footer>
    <script>
        const toggleBtn = document.querySelector("header button");

        toggleBtn.addEventListener("click", () => {
            document.documentElement.classList.toggle("dark");

            if (document.documentElement.classList.contains("dark")) {
                localStorage.theme = "dark";
            } else {
                localStorage.theme = "light";
            }
        });

        if (localStorage.theme === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
    </body>
</html>
