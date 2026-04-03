<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>@yield('title', 'Covoiturage Benin')</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
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
       

        {{-- ── MAIN CONTENT ── --}}
        <main class="flex-1 flex overflow-hidden relative z-10">
            @yield('content')
        </main>




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
