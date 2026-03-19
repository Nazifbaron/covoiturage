<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Connexion</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#401268",        // couleur principale (navbar, titres)
                        secondary: "#c33c72",      // hover, éléments actifs
                        accent: "#fe7644",
                        background: "#f8faf8"
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"]
                    }
                }
            }
            }
        </script>
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>

    <body class="bg-background text-gray-900 min-h-screen flex flex-col">
        <!-- MAIN -->
        <main class="flex-1 flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-[500px] bg-white rounded-3xl shadow-xl p-10 border border-gray-100">
                <!-- TITLE -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-extrabold mb-2">Se connectez</h1>
                </div>
                <!-- FORM -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- EMAIL -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" name="email" required placeholder="email@exemple.com" class="w-full rounded-xl border border-gray-200 p-3 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition">
                    </div>
                    <!-- PASSWORD -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-sm font-medium">Mot de passe</label>
                            <a class="text-sm font-bold text-primary hover:underline" href="{{ route('password.request') }}">Mot de passe oublié?</a>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full rounded-xl border border-gray-200 p-3 focus:border-primary focus:ring-2 focus:ring-primary/30 outline-none transition">
                    </div>
                    <!-- BUTTON -->
                    <button type="submit"
                            class="w-full bg-primary text-black font-bold py-3 rounded-xl
                                hover:shadow-lg hover:opacity-90 transition">
                        Se Connectez
                    </button>

                </form>
                <!-- LOGIN LINK -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    Vous n'avez pas encore de compte ?
                    <a class="font-bold text-primary hover:underline" href="register">Inscrivez-vous gratuitement</a>
                </div>

            </div>
        </main>

    </body>
</html>
