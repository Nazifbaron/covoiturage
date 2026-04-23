<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                body { font-family: 'Public Sans', sans-serif;
                        background-color: #f8f6fb;
                    }
                .material-symbols-outlined {
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                }
        </style>

    </head>
    <body class="bg-[#f8f6fb] text-slate-700 antialiased">
        <!-- TopNavBar -->
        <x-header></x-header>
        <main class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
            <!-- Hero Header -->
            <header class="mb-16 max-w-3xl">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold mb-6">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">gpp_good</span>
                    <span>Conformité RGPD Active</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 leading-tight">Politique de Confidentialité</h1>
                <p class="text-lg text-slate-800 leading-relaxed">
                    Chez <strong>Acces Universel</strong>, la protection de vos données personnelles est au cœur de notre engagement. Nous détaillons ici comment nous collectons, utilisons et sécurisons vos informations pour garantir un service de covoiturage sûr et transparent.
                </p>
                <div class="mt-8 flex items-center text-sm text-slate-700">
                    <span>Dernière mise à jour : Aujourd'hui</span>
                    <span class="mx-3">•</span>
                    <span>Temps de lecture : qui vous convient</span>
                </div>
            </header>
            <!-- Bento Grid Layout for Data Types -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
                <!-- Location Data -->
                <div class="md:col-span-2 bg-blue-700 border border-slate-200/10 p-8 rounded-xl hover:border-blue-500/30 transition-colors group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="p-3 bg-blue-500/10 rounded-lg group-hover:bg-blue-500/20 transition-colors">
                            <span class="material-symbols-outlined text-blue-400 text-3xl">location_on</span>
                        </div>
                        <span class="text-[10px] uppercase tracking-widest text-slate-900 font-bold">Critique</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Données de Localisation</h3>
                    <p class="text-white text-sm leading-relaxed mb-4">
                        Nous collectons vos coordonnées GPS en temps réel pour faciliter la mise en relation entre passagers et conducteurs, calculer les itinéraires optimaux et assurer votre sécurité durant le trajet.
                    </p>
                    <ul class="space-y-2 text-xs text-slate-900">
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-blue-400 rounded-full"></span> Suivi du trajet en direct</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-blue-400 rounded-full"></span> Historique des points de départ et d'arrivée</li>
                    </ul>
                </div>
                <!-- Profile Data -->
                <div class="bg-slate-900/90 border border-slate-800/10 p-8 rounded-xl hover:border-blue-500/30 transition-colors group">
                    <div class="p-3 bg-purple-500/10 rounded-lg w-fit mb-6 group-hover:bg-purple-500/20 transition-colors">
                        <span class="material-symbols-outlined text-purple-400 text-3xl">person</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Profil Utilisateur</h3>
                    <p class="text-slate-200 text-sm leading-relaxed">
                                    Votre nom, photo, adresse email et numéro de téléphone sont nécessaires pour créer un environnement de confiance et permettre la communication entre les membres de la communauté.
                    </p>
                </div>
                <!-- Payment Data -->
                <div class="bg-green-900 border border-slate-200 p-8 rounded-xl hover:border-blue-500/30 transition-colors group">
                    <div class="p-3 bg-emerald-500/10 rounded-lg w-fit mb-6 group-hover:bg-emerald-500/20 transition-colors">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">account_balance_wallet</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Transactions &amp; Paiement</h3>
                    <p class="text-slate-200 text-sm leading-relaxed">
                                    Les informations de paiement sont traitées via des protocoles sécurisés PCI-DSS. Nous ne stockons jamais l'intégralité de vos numéros de carte bancaire sur nos serveurs.
                    </p>
                </div>
                <!-- Compliance/GDPR -->
                <div class="md:col-span-2 relative overflow-hidden bg-gradient-to-br from-slate-900 to-blue-900 border border-slate-800/20 p-8 rounded-xl">
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-white mb-4">Vos droits selon le RGPD</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-slate-200">
                                    <span class="material-symbols-outlined text-blue-400 text-lg">check_circle</span>
                                    <span>Droit d'accès</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-200">
                                    <span class="material-symbols-outlined text-blue-400 text-lg">check_circle</span>
                                    <span>Droit de rectification</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-sm text-slate-200">
                                    <span class="material-symbols-outlined text-blue-400 text-lg">check_circle</span>
                                    <span>Droit à l'effacement</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-200">
                                    <span class="material-symbols-outlined text-blue-400 text-lg">check_circle</span>
                                    <span>Portabilité des données</span>
                                </div>
                            </div>
                        </div>
                        <button class="mt-8 px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-semibold text-sm transition-colors">
                                        Exercer mes droits
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 opacity-10">
                        <span class="material-symbols-outlined text-[120px]" style="font-variation-settings: 'FILL' 1;">policy</span>
                    </div>
                </div>
            </section>
            <!-- Detailed Policy Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <aside class="hidden lg:block">
                    <div class="sticky top-24 space-y-4">
                        <h4 class="text-xm font-bold text-slate-900 uppercase tracking-widest mb-6">
                            Sommaire
                        </h4>
                        <nav class="space-y-4">
                            <a class="toc-link block text-blue-500 font-medium text-sm border-l-2 border-blue-500 pl-3" href="#collecte"> 1. Collecte des données </a>
                            <a class="toc-link block text-slate-600 hover:text-blue-500 text-sm transition-colors border-l-2 border-transparent pl-3" href="#utilisation"> 2. Utilisation des informations </a>
                            <a class="toc-link block text-slate-600 hover:text-blue-500 text-sm transition-colors border-l-2 border-transparent pl-3" href="#partage"> 3. Partage avec des tiers </a>
                        </nav>
                    </div>
                </aside>
                <div class="lg:col-span-3 space-y-16 text-slate-400 leading-relaxed">
                    <section id="collecte">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">1. Collecte des données</h2>
                        <p class="mb-4 text-slate-700">
                            Nous recueillons des informations lorsque vous créez un compte, utilisez notre application ou communiquez avec nous. Ces données incluent non seulement les informations que vous fournissez explicitement, mais aussi des données techniques collectées automatiquement par nos systèmes.
                        </p>
                        <div class="bg-slate-900 border-l-4 border-blue-500 p-6 rounded-r-xl">
                            <h4 class="text-white font-semibold mb-2">Informations obligatoires</h4>
                            <p class="text-sm">Pour fonctionner, notre service nécessite au minimum : un nom d'utilisateur, une adresse e-mail vérifiée et un numéro de téléphone mobile pour la coordination des trajets.</p>
                        </div>
                    </section>
                    <section id="utilisation">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">2. Utilisation des informations</h2>
                        <p class="text-slate-900">Vos données sont utilisées exclusivement pour les finalités suivantes :</p>
                        <ul class="list-disc ml-6 mt-4 space-y-2 text-slate-900">
                            <li>Faciliter la mise en relation entre membres pour le covoiturage.</li>
                            <li>Envoyer des notifications de service (confirmation de réservation, messages entre membres).</li>
                            <li>Améliorer l'ergonomie de notre application mobile.</li>
                            <li>Prévenir la fraude et assurer la sécurité de notre plateforme.</li>
                        </ul>
                    </section>
                    <section id="partage">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">3. Partage avec des tiers</h2>
                        <p class="text-slate-900">
                            Nous ne vendons jamais vos données personnelles. Le partage d'informations est limité à :
                        </p>
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-slate-900 border border-slate-800">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-symbols-outlined text-blue-400">partner_reports</span>
                                    <span class="text-white font-medium">Partenaires de paiement</span>
                                </div>
                                <p class="text-xs text-slate-200">Stripe pour le traitement sécurisé des transactions financières.</p>
                            </div>
                            <div class="p-4 rounded-lg bg-slate-900 border border-slate-800">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="material-symbols-outlined text-blue-400">cloud</span>
                                    <span class="text-white font-medium">Hébergement Cloud</span>
                                </div>
                                <p class="text-xs text-slate-200">Serveurs hautement sécurisés situés au sein de l'Union Européenne.</p>
                            </div>
                        </div>
                    </section>
                    <section class="bg-blue-600 border border-blue-500/20 rounded-2xl p-8 text-center" id="contact">
                        <span class="material-symbols-outlined text-4xl text-blue-200 mb-4">mail</span>
                        <h2 class="text-2xl font-bold text-white mb-2">Une question sur vos données ?</h2>
                        <p class="text-slate-200 mb-6">Notre Délégué à la Protection des Données (DPO) est là pour vous répondre.</p>
                        <a class="text-blue-400 font-bold hover:text-blue-300 underline underline-offset-4" href="#">Acces@gmail.com</a>
                    </section>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <x-footer></x-footer>
        <script>
            const sections = document.querySelectorAll("section");
            const navLinks = document.querySelectorAll(".toc-link");
            window.addEventListener("scroll", () => {
                let current = "";
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 120;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY >= sectionTop) {
                        current = section.getAttribute("id");
                    }

                });
                navLinks.forEach(link => {
                    link.classList.remove(
                        "text-blue-500",
                        "border-blue-500",
                        "font-medium"
                    );
                    link.classList.add(
                        "text-slate-600",
                        "border-transparent"
                    );
                    if (link.getAttribute("href").includes(current)) {

                        link.classList.add(
                            "text-blue-500",
                            "border-blue-500",
                            "font-medium"
                        );

                        link.classList.remove(
                            "text-slate-600",
                            "border-transparent"
                        );
                    }
                });

            });
        </script>
    </body>
</html>
