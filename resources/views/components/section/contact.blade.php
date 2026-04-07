 <section class="relative bg-slate-50 dark:bg-background-dark overflow-hidden">
                <!-- Background accents -->
                <div class="absolute top-0 right-0 w-80 h-80 bg-green-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/10 rounded-full blur-3xl"></div>

                <div class="relative w-full h-[320px] md:h-[380px] overflow-hidden">

                    <!-- Image de fond -->
                    <img
                        src="{{ asset('images/location/img-banner-contacts.jpg') }}"
                        alt="Contact covoiturage Bénin"
                        class="absolute inset-0 w-full h-full object-cover"
                    >

                    <!-- Overlay sombre -->
                    <div class="absolute inset-0 bg-black/50"></div>

                    <!-- Contenu -->
                    <div class="relative max-w-7xl mx-auto h-full flex items-center px-6 md:px-10">

                        <div class="text-white max-w-xl">

                            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                                Contactez-nous
                            </h1>

                            <p class="text-sm md:text-base text-gray-200 leading-relaxed border-l-4 border-primary pl-4">
                                Une question concernant votre trajet ? Besoin d’aide pour utiliser notre
                                plateforme de covoiturage au Bénin ? Notre équipe est disponible pour
                                vous accompagner et améliorer votre expérience de déplacement.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="max-w-7xl mx-auto p-6 pt-8">

                    <div class="grid lg:grid-cols-2 gap-16 items-center">

                        <!-- LEFT CONTENT -->
                        <div>

                            <h2 class="text-4xl font-bold text-deep-blue dark:text-white mb-6">
                                Nous sommes présents dans les <span class="text-primary">12 Départements</span> au Bénin
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
                                    src="{{ asset('images/location/rMobility1.jpg') }}"
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
<section class="bg-slate-50 py-10">
    <div class="max-w-5xl mx-auto px-6">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-10 md:p-14">

            <!-- Header -->
            <div class="text-center mb-10">

                <h3 class="text-3xl font-bold text-slate-900 mb-3">
                    Envoyez-nous un message
                </h3>

                <p class="text-slate-500">
                    Une question sur un trajet ou besoin d’assistance ?
                    Notre équipe vous répond généralement en moins de 24 heures.
                </p>

            </div>

            <!-- Form -->
            <form action="#" method="POST" class="grid md:grid-cols-2 gap-6">

                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Nom complet
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Jean Dossou"
                        class="w-full rounded-xl border border-slate-300 p-3
                        focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Adresse email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="email@example.com"
                        class="w-full rounded-xl border border-slate-300 p-3
                        focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition"
                    >
                </div>

                <!-- Sujet -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Sujet
                    </label>

                    <input
                        type="text"
                        name="subject"
                        placeholder="Question concernant un trajet"
                        class="w-full rounded-xl border border-slate-300 p-3
                        focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition"
                    >
                </div>

                <!-- Message -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Message
                    </label>

                    <textarea
                        rows="5"
                        name="message"
                        placeholder="Expliquez votre demande..."
                        class="w-full rounded-xl border border-slate-300 p-3
                        focus:ring-2 focus:ring-primary/40 focus:border-primary outline-none transition"
                    ></textarea>
                </div>

                <!-- Button -->
                <div class="md:col-span-2 text-center mt-4">

                    <button
                        type="submit"
                        class="bg-primary text-white font-semibold px-10 py-4 rounded-xl
                        hover:shadow-lg hover:shadow-primary/30 transition"
                    >
                        Envoyer le message
                    </button>

                </div>

            </form>

        </div>

    </div>

</section>
