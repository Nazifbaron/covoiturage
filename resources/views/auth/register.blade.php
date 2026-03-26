<!DOCTYPE html>
<html  lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Inscrivez-vous</title>
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
                                    primary: "#fe7644",        // couleur principale (navbar, titres)
                                    secondary: "#c33c72",      // hover, éléments actifs
                                    accent: "#fe7644",
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
            <!-- Main Content Split Layout -->
        <main class="min-h-screen flex items-center justify-center bg-background-light dark:bg-background-dark px-6 py-12">
            <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden">
                <!-- Image -->
                <div class="relative h-64 sm:h-80 lg:h-auto">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBqczn5CrwTfEEywQ63_5B65FvqTlPYrHokjnhU1DGPvdLDMun-jPzPjA-bhuI3NdJYMkM0_FAPdiZcqMV1jrl5MtWmkIvPDWdgiVz1azlqXXqWpTXCe4Gz1UGxNULlO-s0n_DltVvD7LM1pE6x4zcKx20bb8CjO6v73z2z1llvGMC_Hr0RTPbMJCnCAgmqEPyCf2dmZa1MozcR_DZbf8XsZRSi_GwarqRrdd8ltUyS_NYvZLRVcgsSr_gCI_eDAbzUfvCGBZu04t7Q"
                        class="absolute inset-0 w-full h-full object-cover" alt="">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-black/70"></div>
                    <div class="relative z-10 h-full flex flex-col justify-end p-8 lg:p-12 text-white">
                        <h1 class="text-3xl lg:text-4xl font-black leading-tight mb-4">
                            Voyager ensemble,
                            <span class="text-primary">économisez mieux</span>
                        </h1>
                        <p class="text-sm lg:text-base text-slate-200 max-w-md">
                            Rejoignez des milliers de navetteurs qui partagent leurs trajets quotidiens et réduisent leur empreinte carbone.
                        </p>
                    </div>

                </div>
                <!-- Form -->
                <div class="flex items-center justify-center p-8 lg:p-14">
                    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-8 space-y-6">
                        <div class="text-center space-y-2">
                            <h2 class="text-2xl font-black">Créer un compte</h2>
                            <p class="text-sm text-slate-500">
                                Rejoignez la communauté de covoiturage.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-input type="text" name="first_name" label="Prénom" placeholder="Toni" :required="true" />
                                <x-input type="text" name="last_name" label="Nom" placeholder="Dossou" :required="true" />
                            </div>
                            <x-input type="email" name="email" label="Email" placeholder="toni@example.com" :required="true" />

                            <x-input type="tel" name="phone" label="Téléphone" placeholder="+229 01 00 00 00" :required="true" />

                            <x-input type="password" name="password" label="Mot de passe" placeholder="••••••••" :required="true" />

                            <x-input type="password" name="password_confirmation" label="Confirmer mot de passe" placeholder="••••••••" :required="true" />

                            <button type="submit"
                                class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                                Créer un compte
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </form>
                        <p class="text-center text-sm text-slate-500">
                            Déjà inscrit ?
                            <a href="login" class="text-primary font-semibold hover:underline">
                                Connectez-vous
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
