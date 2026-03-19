<x-layout>
    <section class="relative h-[500px] flex items-center justify-center">

    <!-- Image de fond -->
    <img
        src="{{ asset('images/search.png') }}"
        class="absolute inset-0 w-full h-full object-cover"
        alt="Covoiturage Bénin"
    >

    <!-- Overlay sombre -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Contenu centré -->
    <div class="relative w-full max-w-5xl mx-auto px-6 text-center text-white">

        <!-- Titre -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-3">
                Trouvez votre prochain trajet
            </h2>
            <p class="text-gray-200">
                Voyagez moins cher en partageant votre trajet avec d'autres passagers.
            </p>
        </div>

        <!-- FORMULAIRE -->
        <div class="bg-white text-black rounded-xl shadow-xl p-6">

            <form action="#" method="GET">
                <div class="grid md:grid-cols-5 gap-4">

                    <!-- Départ -->
                    <div class="flex items-center gap-3 border rounded-lg px-4 py-3">
                        <span class="material-symbols-outlined text-primary">
                            location_on
                        </span>
                        <input type="text" name="departure_city_id" placeholder="Départ"
                            class="w-full border-none focus:ring-0 text-sm">
                    </div>

                    <!-- Destination -->
                    <div class="flex items-center gap-3 border rounded-lg px-4 py-3">
                        <span class="material-symbols-outlined text-primary">
                            near_me
                        </span>
                        <input type="text" name="destination" placeholder="Destination"
                            class="w-full border-none focus:ring-0 text-sm">
                    </div>

                    <!-- Date -->
                    <div class="flex items-center gap-3 border rounded-lg px-4 py-3">
                        <span class="material-symbols-outlined text-primary">
                            calendar_today
                        </span>
                        <input type="date" name="date"
                            class="w-full border-none focus:ring-0 text-sm">
                    </div>

                    <!-- Passagers -->
                    <div class="flex items-center gap-3 border rounded-lg px-4 py-3">
                        <span class="material-symbols-outlined text-primary">
                            group
                        </span>
                        <select name="passengers"
                            class="w-full border-none focus:ring-0 text-sm">
                            <option value="1">1 passager</option>
                            <option value="2">2 passagers</option>
                            <option value="3">3 passagers</option>
                            <option value="4">4 passagers</option>
                        </select>
                    </div>

                    <!-- Bouton -->
                    <button type="submit"
                        class="bg-primary text-white font-bold rounded-lg px-6 py-3 hover:bg-green-600 transition">
                        Rechercher
                    </button>

                </div>
            </form>

        </div>

    </div>

</section>
</x-layout>
