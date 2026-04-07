<x-layout>
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
           <x-header></x-header>
            <main class="max-w-[1200px] mx-auto w-full px-6 py-8">
                <nav class="flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 mb-6">
                    <a class="hover:text-primary" href="#">Résultats de recherche</a>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-slate-900 dark:text-slate-100">Détails du trajet</span>
                </nav>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <section class="bg-white dark:bg-slate-900 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-800">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Cotonou à Calavi</h1>
                                    <p class="text-slate-500 font-medium">Vendredi, Octobre 25 • 08:00 </p>
                                </div>
                            </div>
                            <div class="relative space-y-0 pl-10">
                                <div class="absolute left-4 top-1 bottom-1 w-0.5 bg-slate-200 dark:bg-slate-800"></div>
                                <div class="relative pb-8">
                                    <span class="absolute -left-[30px] top-0 size-5 rounded-full border-4 border-white dark:border-slate-900 bg-others ring-2 ring-primary/20"></span>
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold">Départ : Gare de Cotonou, Calavi</span>
                                        <span class="text-slate-500">Prise en charge à l'entrée principale, à 8 h</span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <span class="absolute -left-[30px] top-0 size-5 rounded-full border-4 border-white dark:border-slate-900 bg-slate-400 ring-2 ring-slate-400/20"></span>
                                    <div class="flex flex-col">
                                        <span class="text-lg font-bold">Arrivée : Calavi</span>
                                        <span class="text-slate-500">Dépose à la station de taxis, 12h30</span>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-800">
                            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
                                <span class="material-symbols-outlined text-others">map</span>
                                <span class="font-bold">Carte routière</span>
                            </div>
                            <div class="aspect-video w-full bg-slate-200 dark:bg-slate-800 relative">
                                <div class="absolute inset-0 bg-cover bg-center" data-alt="Map showing a route between Paris and Lyon" data-location="France" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBE4znW0rUU2ynnq6G9CEb2db9hVw9XtYqg9IF98H8DmV_m1S4trUu1zwcSmRmikiNMe21SF0qCPoWJaJk3mP3W2Xj2ZY_JVB1C0CK3IQcOvj3rP3AYH7dtxLsF4Q_M86wv4J4fR3Xh13wD_Gf0i7tQYM4w3L0CAh0f3AzkzixCGf8Zr0aK55vyo3J_aLZXfdFWJRPrmSwr5Ofo4BJPrlK-xAvq_hWir8tRUmZM57R4YveBj5sbfWingfI-zbZYxtqlHJ0ankVqd_ui')"></div>
                                <div class="absolute inset-0 bg-primary/5 pointer-events-none"></div>
                            </div>
                        </section>
                        <section class="bg-white dark:bg-slate-900 rounded-xl p-8 shadow-sm border border-slate-200 dark:border-slate-800">
                            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-others">person</span>
                                                    Votre chauffeur
                            </h3>
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="flex flex-col items-center text-center space-y-3">
                                    <div class="relative">
                                        <div class="size-24 rounded-full bg-slate-200 overflow-hidden ring-4 ring-primary/10">
                                            <img alt="Thomas" class="w-full h-full object-cover" data-alt="Profile picture of male driver Thomas" src="{{ asset('images/avatar/conducteur.png') }}"/>
                                        </div>
                                        <div class="absolute bottom-0 right-0 bg-others text-white size-8 rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900">
                                            <span class="material-symbols-outlined text-sm">verified</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-bold">Tossou R.</h4>
                                        <div class="flex items-center justify-center gap-1 text-yellow-500">
                                            <span class="material-symbols-outlined fill-1">star</span>
                                            <span class="text-slate-900 dark:text-slate-100 font-bold">4.8</span>
                                            <span class="text-slate-500 font-normal">(124 avis)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-4">
                                    <div>
                                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed italic">
                                            « Bonjour ! Je fais souvent la navette entre Cotonou et Calavi. Je ne fume pas, j'aime écouter des podcasts et j'ai généralement un peu de place pour des bagages légers. Au plaisir de partager le trajet ! »
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                            <span class="material-symbols-outlined text-slate-400">directions_car</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Vehicle</p>
                                                <p class="font-medium">Tesla Model 3</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                            <span class="material-symbols-outlined text-slate-400">check_circle</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Vérifié</p>
                                                <p class="font-medium text-green-600">ID &amp; License</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">Avis</h3>
                                <button class="text-primary font-bold text-sm hover:underline">Voir tous les avis</button>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-10 rounded-full bg-slate-100 overflow-hidden">
                                                <img alt="Sarah" class="w-full h-full object-cover" data-alt="Portrait of passenger Sarah" src="{{  asset('images/avatar/avis.png') }}"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">Sarah M.</p>
                                                <p class="text-xs text-slate-500">October 2026</p>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-500">
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                        </div>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400">Excellent chauffeur, très ponctuel et la voiture était impeccable. La conversation était intéressante aussi ! Je recommande vivement Thomas.</p>
                                </div>
                                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-10 rounded-full bg-slate-100 overflow-hidden">
                                                <img alt="Julian" class="w-full h-full object-cover" data-alt="Portrait of passenger Julian" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHvQoYPavn5zhvu03xzuc6gjUt7C7PwcAmYeHuSzCledTSsUa4mqJBtLwCZALygmZiO6eXvE3j1lq9OSEldTAQfsuqfB8_BIcSumk1pmngR4nFKP3_OEhwPzko6s4iyER-OQ0W7OzxCRuaaPvcauyBO3siMUJvvJu2vYmmB88cv9HYQjqOWIzy9Lhi_V_knbzw_2EXe0rklLZONI0qnU1c3Z-IpsQXtH070KWZEc69NlkbrMuPS8bnFgzZoWG4FuAGLF2AeDncIu8E"/>
                                            </div>
                                            <div>
                                                <p class="font-bold">Julian K.</p>
                                                <p class="text-xs text-slate-500">September 2026</p>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-500">
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined fill-1 text-sm">star</span>
                                            <span class="material-symbols-outlined text-sm">star</span>
                                        </div>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400">Trajet agréable, arrivée à Lyon pile à l'heure. Thomas est un conducteur très prudent.</p>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-4">
                            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="flex flex-col">
                                        <span class="text-slate-500 text-sm font-medium">Prix par siège</span>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-3xl font-extrabold text-others">34.00F CFA</span>
                                        </div>
                                    </div>
                                    <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-tight">
                                        Il reste 3 places disponibles.
                                    </div>
                                </div>
                                <div class="space-y-4 mb-6">
                                    <label class="block">
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">Nombre de sièges</span>
                                            <div class="relative">
                                                <select class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg py-3 pl-4 pr-10 appearance-none focus:ring-primary focus:border-primary transition-all font-medium">
                                                    <option>1 siège</option>
                                                    <option>2 sièges</option>
                                                    <option>3 sièges</option>
                                                </select>
                                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                                            </div>
                                    </label>
                                </div>
                                <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mb-6">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-slate-600 dark:text-slate-400">Coût du voyage (1 place)</span>
                                        <span class="font-bold">34F CFA</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-slate-600 dark:text-slate-400">Frais de service</span>
                                        <span class="font-bold text-green-600">Gratuit</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-lg font-extrabold">Prix total</span>
                                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white">34F CFA</span>
                                    </div>
                                </div>
                                <button class="w-full bg-others hover:bg-others/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
                                    Réserver maintenant
                                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </button>
                                <p class="text-center text-xs text-slate-500 mt-4">Aucun paiement n'est encore requis. La réservation sera en attente de l'approbation du chauffeur.</p>
                            </div>
                            <!--<div class="bg-primary/5 border border-primary/20 rounded-xl p-4 flex gap-3">
                                <span class="material-symbols-outlined text-primary">security</span>
                                <div class="text-xs">
                                    <p class="font-bold text-primary mb-1">Confiance &amp; Sécurité</p>
                                    <p class="text-slate-600 dark:text-slate-400">Chaque chauffeur est vérifié et chaque trajet est assuré.</p>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </main>

        </div>
        <script>

            const btn = document.getElementById("menu-btn");
            const menu = document.getElementById("mobile-menu");

            btn.addEventListener("click", () => {
                menu.classList.toggle("hidden");
            });

        </script>
</x-layout>
