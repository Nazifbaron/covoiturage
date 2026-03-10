<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>@yield('title', 'Covoiturage Benin')</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" />
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

            /* Material Symbols Outlined - font setup */
            @supports (font-variation-settings: normal) {
                .material-symbols-outlined {
                    font-family: 'Material Symbols Outlined';
                    font-weight: normal;
                    font-style: normal;
                    font-size: 24px;
                    line-height: 1;
                    letter-spacing: normal;
                    text-transform: none;
                    display: inline-block;
                    white-space: nowrap;
                    word-wrap: normal;
                    direction: ltr;
                    font-feature-settings: 'liga';
                    -webkit-font-smoothing: antialiased;
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0;
                }

                /* Responsive icon sizes */
                .material-symbols-outlined.text-xs { font-size: 12px; }
                .material-symbols-outlined.text-sm { font-size: 16px; }
                .material-symbols-outlined.text-base { font-size: 20px; }
                .material-symbols-outlined.text-lg { font-size: 24px; }
                .material-symbols-outlined.text-xl { font-size: 28px; }
                .material-symbols-outlined.text-2xl { font-size: 32px; }
                .material-symbols-outlined.text-3xl { font-size: 40px; }
                .material-symbols-outlined.text-4xl { font-size: 48px; }
            }

            /* Animated background blobs (dark only) */
            .blob {
                position: absolute;
                border-radius: 9999px;
                filter: blur(80px);
                opacity: 0.07;
                animation: blobFloat 8s ease-in-out infinite;
            }
            .blob-1 { width: 400px; height: 400px; background: #13ec49; top: -100px; right: -100px; animation-delay: 0s; }
            .blob-2 { width: 300px; height: 300px; background: #13ec49; bottom: -80px; left: -80px; animation-delay: 3s; }

            @keyframes blobFloat {
                0%, 100% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
            }
        </style>

        {{-- Slot pour les styles spécifiques à chaque page --}}
        @yield('styles')
    </head>
    <body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-300">

        {{-- Blobs décoratifs (visibles uniquement en dark mode) --}}
        <div class="fixed inset-0 pointer-events-none overflow-hidden dark:block hidden">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
        </div>

        {{-- ── HEADER ── --}}
        <header class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-primary/10 bg-background-light dark:bg-background-dark/80 backdrop-blur-md sticky top-0 z-50">
            <div class="flex items-center gap-2">
                <div class="text-primary">
                    <svg class="size-8" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.8261 30.5736C16.7203 29.8826 20.2244 29.4783 24 29.4783C27.7756 29.4783 31.2797 29.8826 34.1739 30.5736C36.9144 31.2278 39.9967 32.7669 41.3563 33.8352L24.8486 7.36089C24.4571 6.73303 23.5429 6.73303 23.1514 7.36089L6.64374 33.8352C8.00331 32.7669 11.0856 31.2278 13.8261 30.5736Z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-extrabold tracking-tight">Covoiturage Benin</h2>
            </div>
            <div class="flex items-center gap-4">
                {{-- Bouton dark/light mode --}}
                <button id="themeToggle" class="p-2 rounded-lg bg-slate-200 dark:bg-primary/10 hover:bg-slate-300 dark:hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined block">light_mode</span>
                </button>

                {{-- Slot optionnel dans le header (ex: indicateur d'étapes) --}}
                @hasSection('header-extra')
                    @yield('header-extra')
                @else
                    <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-primary/20 border border-slate-300 dark:border-primary/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-500 dark:text-primary/70">person</span>
                    </div>
                @endif
            </div>
        </header>

        {{-- ── MAIN CONTENT ── --}}
        <main class="flex-1 flex overflow-hidden relative z-10">
            @yield('content')
        </main>

        {{-- ── FOOTER ── --}}
        <footer class="px-6 py-4 bg-background-light dark:bg-background-dark border-t border-slate-200 dark:border-primary/10 flex justify-between items-center text-xs text-slate-500">
            <div>© 2026 Tous droits réservés.</div>
            <div class="flex gap-4">
                <a class="hover:text-primary transition-colors" href="#">Politique de confidentialité</a>
                <a class="hover:text-primary transition-colors" href="#">Conditions d'utilisation</a>
            </div>
        </footer>

        {{-- ── SCRIPTS COMMUNS ── --}}
        <script>
            const toggleBtn = document.getElementById("themeToggle");
            toggleBtn.addEventListener("click", () => {
                document.documentElement.classList.toggle("dark");
                localStorage.theme = document.documentElement.classList.contains("dark") ? "dark" : "light";
            });
            if (localStorage.theme === "dark") {
                document.documentElement.classList.add("dark");
            }
        </script>

        {{-- Slot pour les scripts spécifiques à chaque page --}}
        @yield('scripts')
    </body>
</html>
