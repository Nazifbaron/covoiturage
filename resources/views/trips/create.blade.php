<x-trajet.layout title="Créer trajet">

    <!-- Content Area -->
    <div class="flex flex-col lg:flex-row gap-8 items-start m-20">
        <!-- Form Section -->

        <div class="flex-1 w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Où vas-tu ?</h1>
                <p class="text-slate-500 dark:text-slate-400">Indiquez votre point de départ et votre destination finale pour aider les passagers à trouver votre trajet.</p>
            </div>
            <form method="POST" action="{{ route('trips.store') }}">
                @csrf
                <!-- Departure -->  <!-- Destination -->
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div class="space-y-2">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-lg">my_location</span> Point de départ
                        </label>
                        <div class="relative group">
                            <select name="departure_city_id" class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400">
                                <option value="">Sélectionnez une ville</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">
                                    {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">search</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500 text-lg">location_on</span> Ville de destination
                        </label>
                        <div class="relative group">
                            <select name="arrival_city_id" class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400">
                                <option value="">Sélectionnez une ville</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">
                                    {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">search</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DATE -->
                <div class="space-y-2 mb-5">
                    <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                        Date et heure de départ
                    </label>
                    <input class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400"
                    type="datetime-local" name="departure_time"/>
                </div>
                <!-- VEHICLE -->
                <div class="space-y-2 mb-5">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                            Véhicule
                        </label>
                        <select name="vehicle_id" class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400">
                            <option value="">Sélectionnez un véhicule</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                {{ $vehicle->brand }} - {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                </div>
                <!-- SEATS + PRICE -->
                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div class="space-y-2 ">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                            Places disponibles
                        </label>
                        <input class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400"
                        type="number" name="total_seats"/>
                    </div>
                    <div class="space-y-2 ">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-bold flex items-center gap-2">
                            Prix par place
                        </label>
                        <input class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400"
                        type="number" name="price" step="0.01"/>
                    </div>
                </div>
                <div>
                    <label class="font-semibold block mb-2">
                        Arrêts intermédiaires
                    </label>
                    <div id="stops-container"></div>
                    <button type="button" id="add-stop" class="flex items-center gap-2 text-primary font-bold text-sm hover:underline decoration-2 underline-offset-4">
                        <span class="material-symbols-outlined">add_circle</span> Ajoutez des escales pour attirer davantage de passagers
                    </button>
                </div>
                <!-- Stopovers (Optional) -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-center items-center">
                    <button class="bg-primary text-white font-bold py-4 px-10 rounded-lg shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        Publier
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </button>
                </div>
                <script>
                    let index = 0;
                    document.getElementById('add-stop').addEventListener('click',function(){
                    index++;
                    let html = `
                    <div class="flex gap-3 mt-3">
                    <select name="stops[${index}][city_id]"
                    class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400">
                    <option value="">Sélectionnez une ville</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">
                    {{ $city->name }}
                    </option>
                    @endforeach
                    </select>
                    <input
                    type="datetime-local"
                    name="stops[${index}][arrival_time]"
                    class="w-full h-14 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-4 pl-12 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-slate-400"
                    />
                    <button
                    type="button"
                    onclick="this.parentElement.remove()"
                    class="text-red-500"
                    >
                    Remove
                    </button>
                    </div>
                    `;
                    document.getElementById('stops-container')
                    .insertAdjacentHTML('beforeend',html);
                    });
                </script>
            </form>
        </div>

    </div>

</x-trajet.layout>
