<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>{{$title ?? 'Covoiturage -  Partagez votre parcours'}}</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="/src/style.css" rel="stylesheet">
        <script id="tailwind-config">
                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            colors: {
                                primary: "#401268",        // couleur principale (navbar, titres)
                                secondary: "#c33c72",      // hover, éléments actifs
                                accent: "#fe7644",         // boutons importants (CTA)

                                "background-light": "#f8f6fb",
                                "background-dark": "#0f0b1a",

                                "text-dark": "#1a1a1a",
                                "text-light": "#f9fafb",

                                "border-light": "#e5e7eb",
                                "border-dark": "#2a2438",
                                "deep-blue": "#0f172a",
                                "charcoal": "#121212",
                            },
                            fontFamily: {
                                "display": ["Plus Jakarta Sans", "sans-serif"]
                            },
                            borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                        },
                    },
                }
        </script>
        <style type="text/tailwindcss">
                body {
                    font-family: "Plus Jakarta Sans", sans-serif;
                }
                .dark-icon { display: none; }
                .light-icon { display: block; }
                .dark .dark-icon { display: block; }
                .dark .light-icon { display: none; }
                #mobile-menu-toggle:checked ~ #mobile-menu {
                    display: flex;
                }
                #mobile-menu-toggle:checked ~ label[for="mobile-menu-toggle"] .menu-open {
                    display: none;
                }
                #mobile-menu-toggle:checked ~ label[for="mobile-menu-toggle"] .menu-close {
                    display: block;
                }.carousel-track {
                    display: flex;
                    width: 300%;
                    animation: slide 15s infinite;
                }
                @keyframes slide {
                    0%, 28% { transform: translateX(0); }
                    33%, 61% { transform: translateX(-33.333%); }
                    66%, 94% { transform: translateX(-66.666%); }
                    100% { transform: translateX(0); }
                }
                .pagination-dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.3);
                    transition: all 0.3s;
                }@keyframes dot1 { 0%, 28%, 100% { background: #10b981; transform: scale(1.2); } 33%, 94% { background: rgba(255, 255, 255, 0.3); transform: scale(1); } }
                @keyframes dot2 { 0%, 28%, 66%, 100% { background: rgba(255, 255, 255, 0.3); transform: scale(1); } 33%, 61% { background: #10b981; transform: scale(1.2); } }
                @keyframes dot3 { 0%, 61%, 100% { background: rgba(255, 255, 255, 0.3); transform: scale(1); } 66%, 94% { background: #10b981; transform: scale(1.2); } }
                .dot-1 { animation: dot1 15s infinite; }
                .dot-2 { animation: dot2 15s infinite; }
                .dot-3 { animation: dot3 15s infinite; }
        </style>
    </head>
    <body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 ">
        <x-header></x-header>
        @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif
        <main>
            {{ $slot }}
        </main>
        <x-footer></x-footer>
    </body>
</html>
