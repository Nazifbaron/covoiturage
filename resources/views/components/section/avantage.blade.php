<section class="relative py-20 bg-slate-950 overflow-hidden">
  <!-- Glow background -->
  <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full" style="background: #61BE2E33; filter: blur(120px);"></div>
  <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full" style="background: #04537333; filter: blur(120px);"></div>

  <div class="relative max-w-7xl mx-auto px-6">
    <!-- Title -->
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="text-lg md:text-4xl font-bold text-white leading-tight">
        Découvrez vos
        <span class="bg-clip-text text-transparent" style="background-image: linear-gradient(to right, #61BE2E, #045373);">
          avantages personnalisés
        </span>
        🙌
      </h2>
      <p class="mt-4 text-gray-400 text-sm md:text-lg">
        Choisissez votre rôle et découvrez comment le covoiturage peut
        transformer vos trajets quotidiens.
      </p>
    </div>

    <!-- Cards -->
    <div class="mt-10 grid md:grid-cols-2 gap-10">

      <!-- PASSAGER -->
      <a href="#"
        class="group relative rounded-3xl p-[1px]"
        style="background: linear-gradient(to bottom right, #61BE2E66, #04537366);">
        <div class="relative flex items-center justify-between h-full rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 p-6 md:p-10 overflow-hidden transition duration-500 group-hover:scale-[1.02]">
          <!-- Glow hover -->
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500"
            style="background: linear-gradient(to right, #61BE2E1A, #0453731A);"></div>
          <!-- Content -->
          <div class="relative z-10 max-w-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs md:text-sm font-semibold"
              style="background: #61BE2E33; color: #61BE2E;">
              🚗 Passager
            </span>
            <p class="mt-4 md:mt-6 text-base md:text-xl text-white font-medium leading-relaxed">
              Le confort de la voiture au prix
              d'un ticket de bus 🚌
            </p>
            <span class="inline-block mt-5 md:mt-8 font-semibold group-hover:translate-x-1 transition text-sm md:text-base"
              style="color: #61BE2E;">
              Découvrir mes avantages →
            </span>
          </div>
          <!-- Image -->
          <img
            src="{{ asset('images/passager1.png') }}"
            class="relative w-32 md:w-56 transform group-hover:scale-110 transition duration-500"
            alt="">
        </div>
      </a>

      <!-- CONDUCTEUR -->
      <a href="/lancez-vous/interest/driver"
        class="group relative rounded-3xl p-[1px]"
        style="background: linear-gradient(to bottom right, #04537366, #61BE2E66);">
        <div class="relative flex items-center justify-between h-full rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 p-6 md:p-10 overflow-hidden transition duration-500 group-hover:scale-[1.02]">
          <!-- Glow hover -->
          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition duration-500"
            style="background: linear-gradient(to right, #0453731A, #61BE2E1A);"></div>
          <!-- Content -->
          <div class="relative z-10 max-w-sm">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs md:text-sm font-semibold"
              style="background: #04537333; color: #5BB8D4;">
              🚘 Conducteur
            </span>
            <p class="mt-4 md:mt-6 text-base md:text-xl text-white font-medium leading-relaxed">
              Gagnez en moyenne
              <span class="font-bold" style="color: #5BB8D4;">
                100 000F CFA par mois
              </span>
              en partageant vos trajets 💸
            </p>
            <span class="inline-block mt-5 md:mt-8 font-semibold group-hover:translate-x-1 transition text-sm md:text-base"
              style="color: #5BB8D4;">
              Découvrir mes avantages →
            </span>
          </div>
          <!-- Image -->
          <img
            src="{{ asset('images/passager2.png') }}"
            class="relative w-32 md:w-56 transform group-hover:scale-110 transition duration-500"
            alt="">
        </div>
      </a>

    </div>
  </div>
</section>
