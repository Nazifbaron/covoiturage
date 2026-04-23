<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Conditions Générales d'Utilisation - Acces Universel</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <script id="tailwind-config">
                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            colors: {
                                "primary": "#10b981", // Emerald Green accent
                            },
                            borderRadius: {
                                "DEFAULT": "0.25rem",
                                "lg": "0.5rem",
                                "xl": "0.75rem",
                                "full": "9999px"
                            },
                            fontFamily: {
                                "headline": ["Public Sans"],
                                "body": ["Public Sans"],
                                "label": ["Public Sans"]
                            }
                        },
                    },
                }
        </script>
        <style>
                .material-symbols-outlined {
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                }
                body {
                    font-family: 'Public Sans', sans-serif;
                    background-color: #f8f6fb; /* Slate-900 / Anthracite */
                }
                .legal-content h2 {
                    scroll-margin-top: 100px;
                }
        </style>
    </head>
    <body class="text-slate-700 antialiased bg-[#f8f6fb]">
        <!-- TopNavBar Component -->
       <x-header></x-header>
        <main class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Side Navigation / Table of Contents -->
                <aside class="lg:col-span-3">
                    <div class="sticky top-28 space-y-6">
                        <div>
                            <h3 class="text-xm font-bold uppercase tracking-widest text-slate-900 mb-4">Sommaire</h3>
                                <nav class="flex flex-col space-y-1">
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-primary text-slate-700 text-primary bg-primary/10 transition-colors" href="#objet">1. Objet</a>
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-transparent text-slate-700 hover:text-slate-500 hover:border-slate-700 transition-colors" href="#acces">2. Accès au service</a>
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-transparent text-slate-700 hover:text-slate-500 hover:border-slate-700 transition-colors" href="#inscription">3. Inscription</a>
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-transparent text-slate-700 hover:text-slate-500 hover:border-slate-700 transition-colors" href="#fonctionnement">4. Fonctionnement</a>
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-transparent text-slate-700 hover:text-slate-500 hover:border-slate-700 transition-colors" href="#responsabilites">5. Responsabilités</a>
                                    <a class="px-3 py-2 text-sm font-medium border-l-2 border-transparent text-slate-700 hover:text-slate-500 hover:border-slate-700 transition-colors" href="#donnees">6. Données Personnelles</a>
                                </nav>
                        </div>
                        <div class="p-6 rounded-xl bg-white border border-slate-300 shadow-sm">
                            <span class="material-symbols-outlined text-primary mb-3" data-icon="description">description</span>
                            <h4 class="text-slate-900 font-bold text-sm mb-2">Besoin d'aide ?</h4>
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">Si vous avez des questions concernant nos conditions, notre équipe juridique est à votre disposition.</p>
                            <a class="text-xs font-bold text-primary hover:underline transition-all" href="/contact">Contacter le support →</a>
                        </div>
                    </div>
                </aside>
                <!-- Main Legal Content -->
                <article class="lg:col-span-9 max-w-3xl">
                    <header class="mb-12">
                        <div class="inline-flex items-center space-x-2 text-primary font-medium text-sm mb-4">
                            <span class="material-symbols-outlined text-sm" data-icon="gavel">gavel</span>
                            <span>Documents Juridiques</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">Conditions Générales d'Utilisation</h1>
                        <p class="text-lg text-slate-700 leading-relaxed">Dernière mise à jour : à partir de ce jour. Veuillez lire attentivement ces conditions avant d'utiliser les services de Acces Universel.</p>
                    </header>
                    <div class="legal-content space-y-12">
                        <!-- Section 1 -->
                        <section id="objet">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">01.</span> Objet
                            </h2>
                            <div class="prose prose-invert prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
                                <p>Les présentes Conditions Générales d'Utilisation (ci-après « CGU ») ont pour objet de définir les modalités et conditions dans lesquelles la société Acces Universel met à la disposition de ses utilisateurs sa plateforme de mise en relation de conducteurs et de passagers.</p>
                                <p>L’utilisation de la plateforme implique l’acceptation pleine et entière de l’ensemble des présentes CGU par l’Utilisateur. Tout manquement à ces règles peut entraîner la suspension ou la clôture du compte.</p>
                            </div>
                        </section>
                        <!-- Section 2 -->
                        <section id="acces">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">02.</span> Accès au service
                            </h2>
                            <div class="prose prose-invert prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
                                <p>La plateforme est accessible gratuitement à tout Utilisateur disposant d’un accès à internet. Tous les coûts afférents à l’accès au service sont exclusivement à la charge de l’Utilisateur.</p>
                                <p>Acces Universel s'éfforce de permettre l'accès à la plateforme 24 heures sur 24, 7 jours sur 7, sauf en cas de force majeure ou d'un événement hors de son contrôle, et sous réserve des éventuelles pannes et interventions de maintenance nécessaires au bon fonctionnement du service.</p>
                            </div>
                        </section>
                            <!-- Section 3 -->
                        <section id="inscription">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">03.</span> Inscription et Compte
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="p-5 rounded-xl bg-primary border border-slate-200">
                                    <h4 class="text-slate-900 font-semibold mb-2 flex items-center">
                                    <span class="material-symbols-outlined text-primary text-sm mr-2" data-icon="verified">verified</span>
                                        Éligibilité
                                    </h4>
                                    <p class="text-sm text-slate-900">Vous devez être âgé de 18 ans minimum et posséder la pleine capacité juridique.</p>
                                </div>
                                <div class="p-5 rounded-xl bg-primary border border-slate-200">
                                    <h4 class="text-slate-900 font-semibold mb-2 flex items-center">
                                        <span class="material-symbols-outlined text-primary text-sm mr-2" data-icon="lock">lock</span>
                                                                    Confidentialité
                                    </h4>
                                    <p class="text-sm text-slate-900">Vos identifiants sont strictement personnels et ne doivent pas être partagés.</p>
                                </div>
                            </div>
                            <p class="text-slate-700 leading-relaxed">L'Utilisateur s'engage à fournir des informations exactes, complètes et à jour. Toute usurpation d'identité ou fourniture de fausses informations pourra donner lieu à des poursuites judiciaires.</p>
                        </section>
                        <!-- Section 4 -->
                        <section class="relative" id="fonctionnement">
                            <div class="absolute -left-6 top-0 bottom-0 w-1 bg-primary/40 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">04.</span> Fonctionnement du Service
                            </h2>
                            <div class="bg-primary rounded-2xl p-8 border border-slate-200 mb-6">
                                <ul class="space-y-6">
                                    <li class="flex gap-4">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">A</div>
                                        <div>
                                            <h5 class="text-slate-900 font-semibold mb-1">Publication d'annonces</h5>
                                            <p class="text-sm text-slate-900">Les Conducteurs publient des annonces de trajets incluant la date, l'heure, l'itinéraire et le prix par place.</p>
                                        </div>
                                    </li>
                                    <li class="flex gap-4">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">B</div>
                                        <div>
                                            <h5 class="text-slate-900 font-semibold mb-1">Réservation et Paiement</h5>
                                            <p class="text-sm text-slate-900">Le Passager réserve sa place via la plateforme. Le paiement est sécurisé et conservé en séquestre jusqu'à la fin du trajet.</p>
                                        </div>
                                    </li>
                                    <li class="flex gap-4">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">C</div>
                                        <div>
                                            <h5 class="text-slate-900 font-semibold mb-1">Évaluation mutuelle</h5>
                                            <p class="text-sm text-slate-900">Après chaque trajet, les membres sont invités à laisser un avis pour garantir la confiance au sein de la communauté.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>
                        <!-- Section 5 -->
                        <section id="responsabilites">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">05.</span> Responsabilités
                            </h2>
                            <div class="prose prose-invert prose-slate max-w-none text-slate-600 leading-relaxed space-y-6">
                                <div class="bg-amber-50 border border-amber-200 p-6 rounded-xl">
                                    <div class="flex items-start space-x-3 text-amber-500 mb-2">
                                        <span class="material-symbols-outlined" data-icon="warning">warning</span>
                                        <span class="font-bold">Avertissement Important</span>
                                    </div>
                                    <p class="text-sm text-amber-700">Acces Universel agit en tant que simple intermédiaire technique. Nous n'intervenons pas dans les trajets eux-mêmes et ne saurions être tenus responsables de tout incident survenant pendant le covoiturage.</p>
                                </div>
                                <p class="text-slate-700">Le Conducteur est seul responsable de la conduite de son véhicule, de l'état de celui-ci et du respect du Code de la Route. Il doit impérativement être titulaire d'un permis de conduire valide et d'une assurance couvrant le transport de passagers.</p>
                            </div>
                        </section>
                        <!-- Section 6 -->
                        <section id="donnees">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                                <span class="text-primary mr-3">06.</span> Données Personnelles (RGPD)
                            </h2>
                            <div class="prose prose-invert prose-slate max-w-none text-slate-600 leading-relaxed space-y-4">
                                <p class="text-slate-700">Conformément au Règlement Général sur la Protection des Données, Acces Universel collecte et traite vos données pour le bon fonctionnement du service. Vous disposez d’un droit d’accès, de rectification et de suppression de vos données.</p>
                                <div class="flex flex-wrap gap-3 mt-6">
                                    <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-medium text-slate-300">Droit à l'oubli</span>
                                    <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-medium text-slate-300">Portabilité des données</span>
                                    <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-medium text-slate-300">Sécurité TLS/SSL</span>
                                    <span class="px-3 py-1 bg-slate-800 rounded-full text-xs font-medium text-slate-300">Hébergement UE</span>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- Action Footer -->
                    <div class="mt-20 pt-10 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <p class="text-sm text-slate-500">En continuant à utiliser notre application, vous confirmez avoir lu et accepté ces conditions.</p>
                            <!--<div class="flex space-x-4">
                            <button class="px-6 py-2.5 rounded-lg border border-slate-700 text-slate-300 font-bold text-sm hover:bg-slate-800 transition-all">Imprimer le PDF</button>
                            <button class="px-6 py-2.5 rounded-lg bg-primary text-slate-900 font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">J'accepte les CGU</button>
                            </div>-->
                    </div>
                </article>
            </div>
        </main>
        <x-footer></x-footer>
        <script>
            const sections = document.querySelectorAll("section");
            const navLinks = document.querySelectorAll("aside nav a");

            window.addEventListener("scroll", () => {

            let current = "";

            sections.forEach(section => {

            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.clientHeight;

            if(pageYOffset >= sectionTop){
            current = section.getAttribute("id");
            }

            });

            navLinks.forEach(link => {

            link.classList.remove(
            "border-primary",
            "text-primary",
            "bg-primary/10"
            );

            link.classList.add(
            "text-slate-500",
            "border-transparent"
            );

            if(link.getAttribute("href").includes(current)){

            link.classList.add(
            "border-primary",
            "text-primary",
            "bg-primary/10"
            );

            link.classList.remove(
            "text-slate-500",
            "border-transparent"
            );

            }

            });

            });
        </script>
    </body>
</html>
