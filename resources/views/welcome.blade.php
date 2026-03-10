<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Covoiturage -  Partagez votre parcours</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&amp;display=swap" />
        <link href="/src/style.css" rel="stylesheet">
        <script id="tailwind-config">
                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            colors: {
                                "primary": "#10b981", // Emerald Green
                                "background-light": "#f6f8f6",
                                "background-dark": "#0a0f0b", // Darker charcoal
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
    <body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 dark">
        <header class="sticky top-0 z-[100] w-full border-b border-slate-200 dark:border-white/10 bg-white/70 dark:bg-background-dark/70 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between relative">
                <div class="flex items-center gap-2 shrink-0">
                    <div class="bg-primary p-1.5 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-background-dark font-bold">directions_car</span>
                    </div>
                    <h2 class="text-deep-blue dark:text-white text-2xl font-extrabold tracking-tight">Covoiturage</h2>
                </div>
                <nav class="hidden lg:flex items-center gap-8 absolute left-1/2 -translate-x-1/2">
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="/">Accueil</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Trouver un trajet </a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Comment ça marche</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Proposer un trajet</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Contact</a>
                </nav>
                <div class="flex items-center gap-2 md:gap-4">
                    <button aria-label="Toggle theme" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-all text-slate-500 dark:text-slate-400" onclick="document.body.classList.toggle('dark')">
                        <span class="material-symbols-outlined light-icon">light_mode</span>
                        <span class="material-symbols-outlined dark-icon">dark_mode</span>
                    </button>
                    <div class="hidden sm:flex items-center gap-3">
                        <a class="bg-primary text-background-dark text-sm font-bold px-5 py-2.5 rounded-lg hover:shadow-lg hover:shadow-primary/20 transition-all" href="/login">Se connecter</a>
                    </div>
                    <input class="hidden" id="mobile-menu-toggle" type="checkbox"/>
                    <label class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400" for="mobile-menu-toggle">
                        <span class="material-symbols-outlined menu-open">menu</span>
                        <span class="material-symbols-outlined menu-close hidden">close</span>
                    </label>
                    <div class="hidden absolute top-20 left-0 w-full bg-white dark:bg-charcoal border-b border-slate-200 dark:border-white/10 flex-col p-6 gap-6 lg:hidden shadow-xl" id="mobile-menu">
                        <nav class="flex flex-col gap-4">
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="/">Accueil</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Trouver un trajet</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#"></a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Proposer un trajet</a>
                            <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Contact</a>
                        </nav>
                        <hr class="border-slate-100 dark:border-white/5"/>
                        <div class="flex flex-col gap-3">
                            <a class="w-full text-center py-4 font-bold bg-primary text-background-dark rounded-xl" href="/login">Se connecter</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <section class="relative min-h-[700px] flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 z-0 overflow-hidden">
                    <div class="carousel-track h-full">
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Diverse friends in car" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDixtjMIdazC3WKIJXJIPUt7fdceA64LRT9InOMQ8ig8uSM0BO5bDxu2J0H5SxLLXB90pSm7a9hSNlDxRCqeuRcJiCfmw5jGJ1fDloGAVbxVKbuFAaCivT7dooBh9UtaA4JhdvW3RPWEU1O75uEDUFvGChV6gqutFX2ZacqvRHm77fgvH-p3ygBWLWQU7MJ8S5-buETrlHwwVbncJ5ZYkVkbvk5TFMDuAWApvEY_r3IoOiHaxklLWf86o_arsbW4P_FPH6A5Yrdb9qG"/>
                        </div>
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Scenic highway sunset" class="w-full h-full object-cover" src="{{ asset('images/carousel/amazon.jpg') }}"/>
                        </div>
                        <div class="w-1/3 h-full relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70 z-10"></div>
                            <img alt="Friendly handshake" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAY24EbjPtNOzbDxbW3Hk4B-uXepWGnEYjt9lQTCVX6zmZUzImBIAUslt27wZuvdIaldZ6zk6-iryxo7v36gD_BOdH_Si-nRNuvK6b-j1dZC22EDZeVcVx7ZQN-geZS1djYHtZdJLhv60wa9G4omg24kUDwArzhnqSgZQ0T7cKSwvYXQXkHo6jRVtNgbL9FdWlTvsVKPGfTNGhOrH3HqQRIUBgsFXK_TTiM1NgcryKT4BmEGa4RDlF728w7UdCxfsKYEWXtfEe8aZTl"/>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 z-20 px-6 flex justify-between pointer-events-none">
                    <button class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white transition-all pointer-events-auto border border-white/10">
                    <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white transition-all pointer-events-auto border border-white/10">
                    <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
                    <div class="pagination-dot dot-1"></div>
                    <div class="pagination-dot dot-2"></div>
                    <div class="pagination-dot dot-3"></div>
                </div>
                <div class="relative z-10 max-w-5xl w-full px-6 py-20">
                    <div class="text-center mb-12">
                        <h1 class="text-white text-5xl md:text-7xl font-extrabold mb-6 leading-tight drop-shadow-lg">Votre prochain voyage, <span class="text-primary">partagé.</span></h1>
                        <p class="text-slate-100 text-lg md:text-xl max-w-2xl mx-auto font-medium drop-shadow-md">Entrez en contact avec des chauffeurs fiables et économisez sur vos frais de déplacement tout en réduisant votre empreinte carbone.</p>
                    </div>
                    <div class="bg-white dark:bg-charcoal p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row items-stretch gap-2">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-2">
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[16px]">location_on</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Ville de départ" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">near_me</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Ville de destination" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">calendar_month</span>
                                <input class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium" placeholder="Aujourd'hui" type="text"/>
                            </div>
                            <div class="relative flex items-center group">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400 group-focus-within:text-primary text-[20px]">person</span>
                                <select class="w-full pl-12 pr-4 py-5 bg-slate-50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-primary/50 text-slate-900 dark:text-white font-medium appearance-none">
                                    <option>1 passager</option>
                                    <option>2 passagers</option>
                                    <option>3 passagers</option>
                                    <option>4+ passagers</option>
                                </select>
                            </div>
                        </div>
                        <button class="bg-primary text-background-dark font-bold px-10 py-5 rounded-xl hover:bg-primary/90 transition-all text-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span> Rechercher
                        </button>
                    </div>
                </div>
            </section>
            <section class="py-24 bg-white dark:bg-background-dark">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-deep-blue dark:text-white mb-4">Comment ça marche</h2>
                        <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">Se rendre d'un point A à un point B n'a jamais été aussi simple et convivial.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-all duration-300">
                                <span class="material-symbols-outlined text-3xl text-primary group-hover:text-background-dark">search</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Rechercher</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Entrez votre lieu de départ, votre destination et la date pour trouver le trajet idéal pour vous.</p>
                        </div>
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-all duration-300">
                                <span class="material-symbols-outlined text-3xl text-primary group-hover:text-background-dark">event_available</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Reserver</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Choisissez un chauffeur, consultez son profil et réservez votre place en ligne en toute sécurité.</p>
                        </div>
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:bg-primary transition-all duration-300">
                                <span class="material-symbols-outlined text-3xl text-primary group-hover:text-background-dark">auto_awesome</span>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Voyage</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">Retrouvez votre chauffeur à l'endroit convenu, partagez les frais et profitez du trajet !</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-24 bg-background-light dark:bg-charcoal/50">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex items-end justify-between mb-12">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-extrabold text-deep-blue dark:text-white mb-4">Itinéraires populaires</h2>
                            <p class="text-slate-500 dark:text-slate-400">Découvrez nos destinations les plus prisées à des prix imbattables.</p>
                        </div>
                        <button class="hidden md:flex items-center gap-2 text-primary font-bold hover:underline">
                            Voir tous les itinéraires <span class="material-symbols-outlined">arrow_forward</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-white dark:bg-charcoal rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-100 dark:border-white/5">
                            <div class="h-48 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCjUuMpCAW7ILQQ7pLjwz7sUWcC6T1OQko-rJ1iifX-O0bPacpow7DDD8VlKY-qKIpXSch6ZepJ4W1hwpLTSCxFytm3loC22vmNwGBNnRkK5f1V7LRZvFxvUOBOJZdIbmxZ9gxFsxCRKsF1_sLagS7BN7FVjAkvuITSPfeQxl1Hj4pZdKhEWSmspgftJ2Qff_KZRsu9Jy04_BEsyN83IXCpRawnCVqsIvfkAPF3uUH1fL46i3HhHiXayrA9Cd2wyuYPsdirF4lJ3i1Z')"></div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-bold text-deep-blue dark:text-white">Cotonou → Calavi</h3>
                                        <span class="text-primary font-bold text-lg">A partir de  500F CFA</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">schedule</span> 4h 30m</span>
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">groups</span> Plus de 12 trajets quotidiens</span>
                                    </div>
                                    <button class="w-full py-3 border-2 border-primary text-background-dark dark:text-primary font-bold rounded-xl hover:bg-primary dark:hover:text-background-dark transition-all">Réservez ce voyage</button>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-charcoal rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-100 dark:border-white/5">
                                <div class="h-48 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBQIewMafR5A059NoOAcWEr2CGPY_MH5x3uByDPY8rQWAzmjBy7wqR8VO0KrMgHkkCDwLXM1Vn3NlNyXLKXpafqPuHaCRIiRAqPB7eVzuq99i1oEDn2wzzJrxXdwsJmKAug7BWT1i9ZSdiMFarjRZ8OzCnOfKBcm5lkq_xsX4lko8xGTadMsV1tpMmJOQmJs2whRDNcVn2KDBnz7ruCK9648kZIoUU8oMc-YNQBHHRK-30K1FHtV42ts9TJD2LN1nmQMyiwlthGoVl9')"></div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-bold text-deep-blue dark:text-white">Cocotome → Pahou</h3>
                                        <span class="text-primary font-bold text-lg">A partir de 500F CFA</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">schedule</span> 3h 45m</span>
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">groups</span> Plus de 8 trajets quotidiens</span>
                                    </div>
                                    <button class="w-full py-3 border-2 border-primary text-background-dark dark:text-primary font-bold rounded-xl hover:bg-primary dark:hover:text-background-dark transition-all">Réservez ce voyage</button>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-charcoal rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-slate-100 dark:border-white/5">
                                <div class="h-48 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBGtf4t7N_Z4AD0bwJO5_hRZxSqzjm-kTY6E3wKf4abbioMP3zs28GaHJh4Xi-GM4MlRTIK24aUJyQW2OTxe8fJvn1OP421M6nZoobzTD9xWy_O2KpFhKpGzCjyKKZ8DYFzODzPqPQ_pc4JjZm_hQfM56VTCkB9awZ6LhYFxu_HAM2oLzYTLt90Nbvl4cRN_I_456tBQ5WbKd_S85-5h2JKQj5nHvXl565bbACGvFkGb0mI1jdbdc2YbVC8HisZhuFGnrKx2PZdlWHT')"></div>
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xl font-bold text-deep-blue dark:text-white">Cotonou → Porto-Novo</h3>
                                        <span class="text-primary font-bold text-lg">A partir de 1000F CFA</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-slate-500 mb-6">
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">schedule</span> 6h 15m</span>
                                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">groups</span> Plus de 15 trajets quotidiens</span>
                                    </div>
                                    <button class="w-full py-3 border-2 border-primary text-background-dark dark:text-primary font-bold rounded-xl hover:bg-primary dark:hover:text-background-dark transition-all">Réservez ce voyage</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <section class="py-20 bg-white dark:bg-background-dark">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="bg-charcoal dark:bg-charcoal/50 rounded-3xl p-10 md:p-20 relative overflow-hidden border border-white/5">
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10">
                            <div class="max-w-xl">
                                <h2 class="text-white text-3xl md:text-5xl font-extrabold mb-6 leading-tight">Prêt à offrir une place pour votre prochain voyage ?</h2>
                                <p class="text-slate-300 text-lg mb-8">Rejoignez des milliers de conducteurs qui gagnent de l'argent tout en voyageant. C'est gratuit, sûr et facile à configurer.</p>
                                <button class="bg-primary text-background-dark font-extrabold px-10 py-4 rounded-xl hover:shadow-lg hover:shadow-primary/30 transition-all text-lg">Commencez à proposer des trajets</button>
                            </div>
                            <div class="w-full md:w-auto flex justify-center">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-primary/30 blur-2xl rounded-full"></div>
                                    <img class="w-64 h-64 object-cover rounded-2xl relative z-10 rotate-3 shadow-2xl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAY24EbjPtNOzbDxbW3Hk4B-uXepWGnEYjt9lQTCVX6zmZUzImBIAUslt27wZuvdIaldZ6zk6-iryxo7v36gD_BOdH_Si-nRNuvK6b-j1dZC22EDZeVcVx7ZQN-geZS1djYHtZdJLhv60wa9G4omg24kUDwArzhnqSgZQ0T7cKSwvYXQXkHo6jRVtNgbL9FdWlTvsVKPGfTNGhOrH3HqQRIUBgsFXK_TTiM1NgcryKT4BmEGa4RDlF728w7UdCxfsKYEWXtfEe8aZTl"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="relative bg-white dark:bg-background-dark py-24 overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <!-- Image Side -->
                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-tr from-primary/20 to-blue-500/20 rounded-3xl blur-2xl opacity-50"></div>

                            <img src="{{ asset('images/about/img-car.png') }}" alt="Car illustration" class="relative rounded-3xl shadow-2xl object-cover w-full">
                        </div>
                        <!-- Content Side -->
                        <div>
                            <h2 class="text-4xl font-bold text-deep-blue dark:text-white mb-8">
                                À propos de nous
                            </h2>
                            <div class="space-y-8">
                                <!-- Item 1 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition">
                                        <img src="{{ asset('images/about/about-1.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Des millions de trajets
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Chaque jour, des milliers de voyageurs trouvent un trajet sécurisé et économique grâce à notre communauté active et engagée.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 2 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center group-hover:bg-blue-500 transition">
                                        <img src="{{ asset('images/about/about-2.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Leader du covoiturage intelligent
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Nous connectons conducteurs et passagers via une plateforme moderne, rapide et fiable adaptée aux besoins actuels.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 3 -->
                                <div class="flex gap-6 group">
                                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center group-hover:bg-green-500 transition">
                                        <img src="{{ asset('images/about/about-3.svg') }}" class="w-7 h-7" alt="">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                            Expérience simple & moderne
                                        </h4>
                                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                            Une interface intuitive, des paiements sécurisés et une gestion simplifiée pour un voyage sans stress.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- CTA -->
                            <div class="mt-10">
                                <a href="#" class="inline-block bg-primary text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                    Découvrir nos trajets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="relative bg-slate-50 dark:bg-background-dark py-24 overflow-hidden">

                <!-- Background accents -->
                <div class="absolute top-0 right-0 w-80 h-80 bg-green-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>

                <div class="max-w-7xl mx-auto px-6">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <!-- LEFT CONTENT -->
                        <div>

                            <h2 class="text-4xl font-bold text-deep-blue dark:text-white mb-6">
                                Nous sommes présents dans <span class="text-primary">12+ villes</span> au Bénin
                            </h2>

                            <p class="text-slate-600 dark:text-slate-400 mb-10 leading-relaxed">
                                Que vous voyagiez entre Cotonou, Parakou, Porto-Novo ou Natitingou,
                                Entreprise vous connecte à des conducteurs fiables et vérifiés.
                                Notre équipe est disponible 24h/24 pour vous assister.
                            </p>

                            <!-- CONTACT CARDS -->
                            <div class="grid sm:grid-cols-2 gap-6">

                                <!-- Booking -->
                                <div class="bg-white dark:bg-white/5 p-6 rounded-2xl shadow-md hover:shadow-xl transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-xl">
                                            📞
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                Réservation de trajets
                                            </h4>
                                            <a href="tel:+22958040379" class="text-primary font-medium block mt-1">
                                                +229 58 04 03 79
                                            </a>
                                            <p class="text-xs text-slate-500 mt-1">
                                                Disponible 24h/24 – 7j/7
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Support -->
                                <div class="bg-white dark:bg-white/5 p-6 rounded-2xl shadow-md hover:shadow-xl transition">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-green-500/10 text-green-600 rounded-xl flex items-center justify-center text-xl">
                                            💬
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                Assistance & Questions
                                            </h4>
                                            <a href="tel:+22943152920" class="text-green-600 font-medium block mt-1">
                                                +229 43 15 29 20
                                            </a>
                                            <p class="text-xs text-slate-500 mt-1">
                                                Support client dédié
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- RIGHT CONTENT -->
                        <div class="relative">

                            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                                <img
                                    src="{{ asset('images/location/img-location-map.png') }}"
                                    alt="Carte des zones couvertes"
                                    class="w-full object-cover"
                                >

                                <!-- Stats Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                                    <div class="grid grid-cols-3 text-white text-center gap-6">

                                        <div>
                                            <div class="text-2xl font-bold">1 470+</div>
                                            <div class="text-xs opacity-80">Conducteurs actifs</div>
                                        </div>

                                        <div>
                                            <div class="text-2xl font-bold">265+</div>
                                            <div class="text-xs opacity-80">Avis 5 étoiles</div>
                                        </div>

                                        <div>
                                            <div class="text-2xl font-bold">308+</div>
                                            <div class="text-xs opacity-80">Partenariats locaux</div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </section>
           <section class="bg-white dark:bg-background-dark py-24">
                <div class="max-w-7xl mx-auto px-6">
                    <!-- Header -->
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-deep-blue dark:text-white mb-4">
                            Ils voyagent avec nous
                        </h2>
                        <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                            Des milliers de passagers et conducteurs utilisent GreenPool chaque semaine à travers le Bénin.
                        </p>
                    </div>
                    <!-- Reviews Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Review 1 -->
                        <div class="bg-slate-50 dark:bg-white/5 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition duration-300">

                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ asset('images/reviews/user1.jpg') }}"
                                    class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        Arnaud T.
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Trajet Cotonou → Parakou
                                    </div>
                                </div>
                            </div>

                            <div class="text-yellow-400 mb-4 text-lg">
                                ★★★★★
                            </div>

                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                Conducteur ponctuel, voiture propre et trajet confortable.
                                Je recommande GreenPool pour voyager en toute sécurité.
                            </p>

                            <div class="mt-6 text-xs text-green-600 font-medium">
                                ✔ Trajet vérifié
                            </div>
                        </div>

                        <!-- Review 2 -->
                        <div class="bg-slate-50 dark:bg-white/5 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition duration-300">

                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ asset('images/reviews/user2.jpg') }}"
                                    class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        Mireille S.
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Trajet Porto-Novo → Cotonou
                                    </div>
                                </div>
                            </div>

                            <div class="text-yellow-400 mb-4 text-lg">
                                ★★★★★
                            </div>

                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                Réservation rapide via la plateforme. Très rassurant,
                                surtout pour les voyages interurbains.
                            </p>

                            <div class="mt-6 text-xs text-green-600 font-medium">
                                ✔ Paiement sécurisé
                            </div>
                        </div>

                        <!-- Review 3 -->
                        <div class="bg-slate-50 dark:bg-white/5 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition duration-300">

                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ asset('images/reviews/user3.jpg') }}"
                                    class="w-14 h-14 rounded-full object-cover">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        Jean-Paul K.
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Trajet Abomey → Bohicon
                                    </div>
                                </div>
                            </div>

                            <div class="text-yellow-400 mb-4 text-lg">
                                ★★★★★
                            </div>

                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                Application moderne et fiable. On se sent en confiance
                                grâce aux profils vérifiés.
                            </p>

                            <div class="mt-6 text-xs text-green-600 font-medium">
                                ✔ Profil vérifié
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Trust Stats -->
                    <div class="mt-20 bg-primary/5 rounded-3xl p-10 text-center">

                        <div class="grid md:grid-cols-3 gap-10">

                            <div>
                                <div class="text-3xl font-bold text-primary">+10 000</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Utilisateurs actifs
                                </div>
                            </div>

                            <div>
                                <div class="text-3xl font-bold text-primary">+2 500</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Trajets par mois
                                </div>
                            </div>

                            <div>
                                <div class="text-3xl font-bold text-primary">4.9/5</div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Note moyenne
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </main>
        <footer class="bg-slate-50 dark:bg-background-dark border-t border-slate-200 dark:border-white/5 py-10 ">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12 mb-16">
                    <div class="col-span-2 lg:col-span-2">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="bg-primary p-1 rounded flex items-center justify-center">
                                <span class="material-symbols-outlined text-background-dark text-sm font-bold">directions_car</span>
                            </div>
                            <h2 class="text-deep-blue dark:text-white text-lg font-bold tracking-tight">Covoiturage</h2>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 max-w-xs mb-6 text-sm">La plateforme leader pour des voyages abordables, durables et communautaires à travers l'Europe et au-delà.</p>
                        <div class="flex gap-4">
                            <a class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center hover:bg-primary transition-colors text-slate-600 dark:text-white" href="#"><span class="material-symbols-outlined text-[16px]">language</span></a>
                            <a class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center hover:bg-primary transition-colors text-slate-600 dark:text-white" href="#"><span class="material-symbols-outlined text-[16px]">chat</span></a>
                            <a class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/5 flex items-center justify-center hover:bg-primary transition-colors text-slate-600 dark:text-white" href="#"><span class="material-symbols-outlined text-[16px]">share</span></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-4">À propos de nous</h4>
                        <ul class="space-y-2 text-slate-500 dark:text-slate-400 text-xs">
                            <li><a class="hover:text-primary transition-colors" href="#">Informations sur l'entreprise</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Salle de presse</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Carrières</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Développement durable</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-4">Assistance</h4>
                        <ul class="space-y-2 text-slate-500 dark:text-slate-400 text-xs">
                            <li><a class="hover:text-primary transition-colors" href="#">Centre d'aide</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Trust &amp; Sécurité</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Directives</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Contactez-nous</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm mb-4">Mentions légales</h4>
                        <ul class="space-y-2 text-slate-500 dark:text-slate-400 text-xs">
                            <li><a class="hover:text-primary transition-colors" href="#">Conditions d'utilisation</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Politique de confidentialité</a></li>
                            <li><a class="hover:text-primary transition-colors" href="#">Politique en matière de cookies</a></li>
                        </ul>
                    </div>

                </div>
                <div class="border-t border-slate-200 dark:border-white/5 pt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                    © 2026 Roulez ensemble, protégez pour toujours.
                </div>

            </div>
        </footer>
    </body>
</html>
