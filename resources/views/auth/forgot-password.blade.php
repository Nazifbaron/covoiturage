<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Mot de passe oublié</title>
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
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-extrabold mb-2">Mot de passe oublié ?</h1>
                    <p class="text-sm text-gray-500">
                        Renseignez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>
                </div>

                {{-- Message de succès --}}
                @if (session('status'))
                    <div class="flex items-center gap-2 mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
                        <span class="material-symbols-outlined text-green-600 flex-shrink-0">check_circle</span>
                        <p class="text-sm font-semibold text-green-700">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- Formulaire --}}
                <form class="space-y-5" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Adresse e-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               required autofocus placeholder="email@exemple.com"
                               class="w-full rounded-xl border p-3 focus:ring-2 focus:ring-primary/30 outline-none transition
                                      {{ $errors->has('email') ? 'border-red-400 bg-red-50 focus:border-red-400' : 'border-gray-200 focus:border-primary' }}">
                        @error('email')
                            <p class="flex items-center gap-1.5 text-xs text-red-500 font-medium mt-1">
                                <span class="material-symbols-outlined" style="font-size:14px">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2
                                   bg-primary text-white font-bold py-3 rounded-xl
                                   hover:shadow-lg hover:opacity-90 transition">
                        <span class="material-symbols-outlined text-xl">send</span>
                        Envoyer le lien 
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
    </body>
</html>
