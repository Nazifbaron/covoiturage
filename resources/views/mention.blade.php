<!DOCTYPE html>

<html  lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                extend: {
                    "colors": {
                        "primary": "#10b981"
                    },
                    "borderRadius": {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                    },
                    "fontFamily": {
                            "headline": ["Public Sans"],
                            "body": ["Public Sans"],
                            "label": ["Public Sans"]
                    }
                },
                },
            }
        </script>
        <style>
                body { font-family: 'Public Sans', sans-serif; }
                .material-symbols-outlined {
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                }
        </style>
    </head>
    <body class="bg-[#f8f6fb] text-slate-700 antialiased selection:bg-blue-500/30">
        <!-- TopNavBar -->
        <x-header></x-header>
        <main class="min-h-screen">
            <!-- Hero Section -->
            <section class="relative py-20 overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-500/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
                </div>
                <div class="relative z-10 max-w-7xl mx-auto px-6">
                    <div class="max-w-3xl">
                        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-slate-900 mb-6">Mentions Légales</h1>
                        <p class="text-lg text-slate-700 leading-relaxed">Conformément aux dispositions de l'article 121 de la loi n° 2020-35 du 06 JANVIER 2021 pour la confiance dans l'économie numérique.</p>
                    </div>
                </div>
            </section>
            <!-- Content Grid (Bento Style) -->
            <section class="max-w-7xl mx-auto px-6 py-12">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Identity Card -->
                    <div class="md:col-span-8 bg-slate-900 border border-slate-800 p-8 rounded-xl backdrop-blur-sm">
                        <div class="flex items-start justify-between mb-8">
                            <div>
                                <span class="text-blue-500 text-xs font-bold uppercase tracking-widest mb-2 block">Éditeur du site</span>
                                <h2 class="text-2xl font-bold text-white">Acces Universel.</h2>
                            </div>
                            <div class="bg-blue-500/10 p-3 rounded-lg">
                                <span class="material-symbols-outlined text-blue-500">corporate_fare</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">Forme Juridique</h3>
                                    <p class="text-slate-200">Société par Actions Simplifiée (SAS)</p>
                                </div>
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">Capital Social</h3>
                                    <p class="text-slate-200">5 000 000 FCFA</p>
                                </div>
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">Siège Social</h3>
                                    <p class="text-slate-200">Ilot:4421, Quartier:Kpondéhou<br/>Maison, Cotonou</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">Immatriculation</h3>
                                    <p class="text-slate-200">RB/COT/23 B 34399</p>
                                </div>
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">TVA Intracommunautaire</h3>
                                    <p class="text-slate-200">BJ  ??????</p>
                                </div>
                                <div>
                                    <h3 class="text-slate-500 font-medium mb-1">Directeur de la publication</h3>
                                    <p class="text-slate-200">??????????</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Info Card -->
                    <div class="md:col-span-4 bg-blue-600 rounded-xl p-8 flex flex-col justify-between text-white overflow-hidden relative group">
                        <div class="absolute -right-4 -top-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-outlined text-9xl">alternate_email</span>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-2xl font-bold mb-6">Nous contacter</h2>
                            <ul class="space-y-6">
                                <li class="flex items-center space-x-4">
                                    <span class="material-symbols-outlined bg-white/20 p-2 rounded-lg">mail</span>
                                    <div>
                                        <p class="text-xs text-blue-100 uppercase">Email</p>
                                        <p class="font-medium">Acces@gmail.com</p>
                                    </div>
                                </li>
                                <li class="flex items-center space-x-4">
                                    <span class="material-symbols-outlined bg-white/20 p-2 rounded-lg">call</span>
                                    <div>
                                    <p class="text-xs text-blue-100 uppercase">Téléphone</p>
                                    <p class="font-medium">+229 12 34 56 78</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="relative z-10 mt-8 pt-8 border-t border-white/20">
                        <p class="text-sm text-blue-100">Disponible du lundi au vendredi, de 8h à 18h.</p>
                        </div>
                    </div>
                    <!-- Hosting Provider -->
                    <div class="md:col-span-6 bg-slate-900 border border-slate-800 p-8 rounded-xl flex items-center space-x-6">
                        <div class="flex-shrink-0 bg-slate-800 p-4 rounded-xl">
                            <span class="material-symbols-outlined text-3xl text-slate-400">cloud</span>
                        </div>
                        <div>
                            <span class="text-slate-300 text-xs font-bold uppercase tracking-widest mb-1 block">Hébergement</span>
                            <h2 class="text-xl font-bold text-white mb-1">Amazon Web Services (AWS)</h2>
                            <p class="text-sm text-slate-400">Amazon.com Inc., P.O. Box 81226, Seattle, WA 98108-1226, USA.<br/>https://aws.amazon.com</p>
                        </div>
                    </div>
                    <!-- Intellectual Property -->
                    <div class="md:col-span-6 bg-slate-900 border border-slate-800 p-8 rounded-xl flex items-center space-x-6">
                        <div class="flex-shrink-0 bg-slate-800 p-4 rounded-xl">
                            <span class="material-symbols-outlined text-3xl text-slate-400">copyright</span>
                        </div>
                        <div>
                            <span class="text-slate-300 text-xs font-bold uppercase tracking-widest mb-1 block">Propriété Intellectuelle</span>
                            <h2 class="text-xl font-bold text-white mb-1">Contenu &amp; Droits</h2>
                            <p class="text-sm text-slate-400">Toute reproduction, représentation, modification ou publication, quel que soit le support, est interdite sans autorisation écrite.</p>
                        </div>
                    </div>
                    <!-- Map/Location Visualization -->
                    <!--<div class="md:col-span-12 h-64 rounded-xl overflow-hidden relative border border-slate-800 grayscale opacity-60 hover:opacity-100 transition-opacity duration-700">
                        <img alt="Map of Paris" class="w-full h-full object-cover" data-alt="Modern dark-themed digital map visualization of Paris city center with glowing blue transit lines and minimal UI elements" data-location="Paris" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpvRqHm9CJq3LQ5pb5J2eEFkyv4DVx-pR-PZy1cxJYL_whcKY2FZAhP0AdZjesdyRf6w9INpUh-9iOTM-LJs5Pu1hH8qWhQnLMSUGmpbymQlNTsMT0efNgLKRznXZPBl-O7ZNc9don5_2W5Zf-c4Xe4t9wOnapQHVkptcpnOCNoeyP9OT7Kh--lgIc03F8YlQ9Gy0FvfsEC3PyCCPVsVFxU5ZD45-JE_QtAISsdAtuHOmYuFpEZhRoDcBhr0FyXA07FuA1Zxb8f0f1"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent"></div>
                        <div class="absolute bottom-6 left-6">
                            <p class="text-white font-bold flex items-center">
                                <span class="material-symbols-outlined mr-2 text-blue-500">location_on</span>
                                                    Cotonou, 8ème Arrondissement
                            </p>
                        </div>
                    </div>-->
                </div>
            </section>
            <!-- Small Print Section -->
            <section class="max-w-4xl mx-auto px-6 py-10">
                <div class="prose prose-invert prose-slate max-w-none">
                    <h3 class="text-slate-900 text-xl font-bold mb-4">Gestion des données personnelles</h3>
                    <p class="text-slate-700 text-sm leading-relaxed mb-8">
                                Le Client est informé des réglementations concernant la communication marketing, la loi du ???date pour la confiance dans l'Economie Numérique, la Loi Informatique et Liberté du "Date" ainsi que du Règlement Général sur la Protection des Données (RGPD : n° 2016-679). Acces Universel s’engage à préserver la confidentialité des informations fournies en ligne par l’internaute.
                    </p>
                    <h3 class="text-slate-900 text-xl font-bold mb-4">Cookies</h3>
                    <p class="text-slate-700 text-sm leading-relaxed">
                                Un « cookie » est un petit fichier d’information envoyé sur le navigateur de l’Utilisateur et enregistré au sein du terminal de l’Utilisateur. Ce fichier comprend des informations telles que le nom de domaine de l’Utilisateur, le fournisseur d’accès Internet de l’Utilisateur, le système d’exploitation de l’Utilisateur, ainsi que la date et l’heure d’accès. Les Cookies ne risquent en aucun cas d’endommager le terminal de l’Utilisateur.
                    </p>
                </div>
            </section>
        </main>
        <!-- Footer -->
        <x-footer></x-footer>
    </body>
</html>
