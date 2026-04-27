<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Réinitialiser le mot de passe</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            primary: "#61BE2E",
                            secondary: "#045373",
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
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>

    <body class="bg-background text-gray-900 min-h-screen flex flex-col">
        <main class="flex-1 flex items-center justify-center px-6 py-16">
            <div class="w-full max-w-[500px] bg-white rounded-3xl shadow-xl p-10 border border-gray-100">

                {{-- Icône --}}
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-4xl">lock_reset</span>
                    </div>
                </div>

                {{-- Titre --}}
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-extrabold mb-2">Nouveau mot de passe</h1>
                    <p class="text-sm text-gray-500">Choisissez un mot de passe fort pour sécuriser votre compte.</p>
                </div>

                {{-- Formulaire --}}
                <form class="space-y-6" method="POST" action="{{ route('password.store') }}">
                    @csrf

                    {{-- Champs cachés indispensables --}}
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                    {{-- Nouveau mot de passe --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Nouveau mot de passe</label>
                        <div class="relative">
                            <input id="password" type="password" name="password"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="w-full rounded-xl border p-3 pr-11 focus:ring-2 focus:ring-primary/30 outline-none transition
                                          {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-gray-200 focus:border-primary' }}">
                            <button type="button" onclick="togglePassword('password', 'eye-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <span id="eye-password" class="material-symbols-outlined" style="font-size:20px">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="flex items-center gap-1.5 text-xs text-red-500 font-medium mt-1">
                                <span class="material-symbols-outlined" style="font-size:14px">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Confirmation mot de passe --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Confirmer le mot de passe</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                   required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="w-full rounded-xl border p-3 pr-11 focus:ring-2 focus:ring-primary/30 outline-none transition
                                          {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-gray-200 focus:border-primary' }}">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-confirm')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <span id="eye-confirm" class="material-symbols-outlined" style="font-size:20px">visibility</span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="flex items-center gap-1.5 text-xs text-red-500 font-medium mt-1">
                                <span class="material-symbols-outlined" style="font-size:14px">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Exigences --}}
                    <ul class="text-xs text-gray-400 space-y-1 px-1">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary" style="font-size:14px">check_circle</span>
                            Au moins 8 caractères
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary" style="font-size:14px">check_circle</span>
                            Une lettre majuscule
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary" style="font-size:14px">check_circle</span>
                            Un chiffre ou un symbole
                        </li>
                    </ul>

                    <button type="submit"
                            class="w-full bg-primary text-white font-bold py-3 rounded-xl
                                   hover:shadow-lg hover:opacity-90 transition">
                        Réinitialiser le mot de passe
                    </button>
                </form>

                {{-- Retour connexion --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary transition-colors font-medium">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                        Retour à la connexion
                    </a>
                </div>

            </div>
        </main>

        <script>
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon  = document.getElementById(iconId);
                const show  = input.type === 'password';
                input.type       = show ? 'text' : 'password';
                icon.textContent = show ? 'visibility_off' : 'visibility';
            }
        </script>
    </body>
</html>
