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
                        primary: "#61BE2E",        // couleur principale (navbar, titres)
                        secondary: "#045373",      // hover, éléments actifs

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
                    <h1 class="text-3xl font-extrabold mb-2">Se connecter</h1>
                </div>
                <!-- FORM -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- EMAIL -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@exemple.com"
                               class="w-full rounded-xl border p-3 focus:ring-2 focus:ring-primary/30 outline-none transition
                                      {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-gray-200 focus:border-primary' }}">
                        @error('email')
                            <p class="flex items-center gap-1.5 text-xs text-red-500 font-medium mt-1">
                                <span class="material-symbols-outlined" style="font-size:14px">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- PASSWORD -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-sm font-medium">Mot de passe</label>
                            <a class="text-sm font-bold text-primary hover:underline" href="{{ route('password.request') }}">Mot de passe oublié?</a>
                        </div>
                        <div class="relative">
                            <input id="password-input" type="password" name="password" required placeholder="••••••••"
                                   class="w-full rounded-xl border p-3 pr-11 focus:ring-2 focus:ring-primary/30 outline-none transition
                                          {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-gray-200 focus:border-primary' }}">
                            <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <span id="eye-icon" class="material-symbols-outlined" style="font-size:20px">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="flex items-center gap-1.5 text-xs text-red-500 font-medium mt-1">
                                <span class="material-symbols-outlined" style="font-size:14px">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- BUTTON -->
                    <button type="submit"
                            class="w-full bg-primary text-black font-bold py-3 rounded-xl
                                hover:shadow-lg hover:opacity-90 transition">
                        Se connecter
                    </button>

                </form>
                <!-- LOGIN LINK -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    Vous n'avez pas encore de compte ?
                    <a class="font-bold text-primary hover:underline" href="register">Inscrivez-vous gratuitement</a>
                </div>

            </div>
        </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon  = document.getElementById('eye-icon');
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            icon.textContent = show ? 'visibility_off' : 'visibility';
        }
    </script>
    </body>
</html>
