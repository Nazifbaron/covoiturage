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
                                    primary: "#61BE2E",        // couleur principale (navbar, titres)
                                    secondary: "#045373",      // hover, éléments actifs

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
    <body class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center px-6 py-10">

    <div class="w-full max-w-md">


        <!-- Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 p-8 space-y-6">

            <div class="text-center space-y-2">
                <h2 class="text-2xl font-black">Créer un compte</h2>
                <p class="text-sm text-slate-500">
                    Rejoignez la communauté de covoiturage
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <x-input type="text" name="first_name" label="Prénom" placeholder="Toni" :required="true" />
                    <x-input type="text" name="last_name" label="Nom" placeholder="Dossou" :required="true" />
                </div>

                <x-input type="email" name="email" label="Email" placeholder="toni@example.com" :required="true" />

                <x-input type="tel" name="phone" label="Téléphone" placeholder="+229 01 00 00 00" :required="true" />

                <x-input type="password" name="password" label="Mot de passe" placeholder="••••••••" :required="true" />

                <x-input type="password" name="password_confirmation" label="Confirmer mot de passe" placeholder="••••••••" :required="true" />

                <button
                    type="submit"
                    class="w-full bg-primary hover:bg-secondary text-white py-3 rounded-xl font-bold transition">
                    Créer un compte

                </button>

            </form>

            <p class="text-center text-sm text-slate-500">
                Déjà inscrit ?
                <a href="/login" class="text-primary font-semibold hover:underline">
                    Connectez-vous
                </a>
            </p>

        </div>

    </div>

</body>
</html>
