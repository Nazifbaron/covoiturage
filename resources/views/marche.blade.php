<x-layout>
    <x-header></x-header>
    <section class="relative h-[420px] flex items-center justify-center text-center">
        <!-- Image -->
        <img src="{{ asset('images/covoiturage-banner.jpg') }}"
            class="absolute inset-0 w-full h-full object-cover"
            alt="Covoiturage Bénin" >
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60"></div>
            <!-- Contenu -->
            <div class="relative max-w-3xl px-6 text-white">
                <h1 class="text-4xl md:text-5xl font-extrabold mb-6">
                    Comment fonctionne notre covoiturage ?
                </h1>
                <p class="text-lg text-gray-200 leading-relaxed">
                    Notre plateforme facilite les déplacements au Bénin en permettant aux
                    conducteurs et aux passagers de partager un trajet simple, économique
                    et convivial.
                </p>
            </div>
    </section>
    <section class="py-24 bg-white dark:bg-background-dark">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-extrabold mb-4">Comment ça marche</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">
                    Trois étapes simples pour voyager facilement à travers le Bénin.
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <!-- étape 1 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary transition">
                            <span class="material-symbols-outlined text-4xl text-primary group-hover:text-white">search</span>
                        </div>
                        <span class="absolute -top-3 -right-3 bg-primary text-white text-sm px-3 py-1 rounded-full">1</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Rechercher un trajet</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Entrez votre ville de départ, votre destination et la date pour découvrir
                        les trajets disponibles proposés par les conducteurs.
                    </p>
                </div>
                <!-- étape 2 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary transition">
                            <span class="material-symbols-outlined text-4xl text-primary group-hover:text-white">event_available</span>
                        </div>
                        <span class="absolute -top-3 -right-3 bg-primary text-white text-sm px-3 py-1 rounded-full">2</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Réserver votre place</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Consultez les profils des conducteurs, les avis et les détails du trajet
                        avant de réserver votre place en quelques secondes.
                    </p>
                </div>
                <!-- étape 3 -->
                <div class="text-center group">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 mx-auto rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary transition">
                            <span class="material-symbols-outlined text-4xl text-primary group-hover:text-white">directions_car</span>
                        </div>
                        <span class="absolute -top-3 -right-3 bg-primary text-white text-sm px-3 py-1 rounded-full">3</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Voyager ensemble</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Retrouvez votre conducteur au point de rendez-vous et profitez d’un trajet
                        confortable tout en partageant les frais.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-3xl font-extrabold mb-6">
                    Pourquoi utiliser notre plateforme ?
                </h2>
                <ul class="space-y-4 text-slate-800">
                    <li>✔ Réduisez vos frais de transport</li>
                    <li>✔ Voyagez en toute sécurité</li>
                    <li>✔ Rencontrez de nouvelles personnes</li>
                    <li>✔ Contribuez à réduire les embouteillages</li>
                </ul>
            </div>
            <div class="rounded-xl overflow-hidden shadow-lg">
                <img src="{{ asset('images/covoiturage-passagers.jpg') }}" class="w-full h-full object-cover">
            </div>
        </div>
    </section>
    <section class="py-20 bg-primary text-center text-white">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-6">
                Prêt à commencer votre trajet ?
            </h2>
            <p class="mb-8 text-lg">
                Recherchez un trajet ou proposez le vôtre et rejoignez la communauté
                de covoiturage au Bénin.
            </p>
            <div class="flex justify-center gap-6">
                <a href="/trajets" class="bg-white text-primary px-8 py-3 rounded-lg font-bold">
                    Trouver un trajet
                </a>
                <a href="/trajets/create" class="border border-white px-8 py-3 rounded-lg font-bold">
                    Demander un trajet
                </a>
            </div>
        </div>
    </section>

</x-layout>
