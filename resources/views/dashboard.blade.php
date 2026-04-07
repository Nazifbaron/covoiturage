<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Global User Dashboard Overview</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec49",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102215",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
<div class="flex h-screen overflow-hidden">
<!-- Side Navigation Rail -->
<aside class="w-64 border-r border-slate-200 dark:border-primary/10 bg-white dark:bg-background-dark flex flex-col justify-between p-4">
<div class="flex flex-col gap-8">
<div class="flex items-center gap-3 px-2">
<div class="text-primary size-8 bg-primary/10 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined">directions_car</span>
</div>
<h2 class="text-xl font-bold tracking-tight">CarpoolGo</h2>
</div>
<nav class="flex flex-col gap-2">
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary font-medium" href="#">
<span class="material-symbols-outlined">dashboard</span>
<span>Dashboard</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-primary/5 transition-colors" href="#">
<span class="material-symbols-outlined">map</span>
<span>My Trips</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-primary/5 transition-colors" href="#">
<span class="material-symbols-outlined">chat_bubble</span>
<span>Messages</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-primary/5 transition-colors" href="#">
<span class="material-symbols-outlined">account_balance_wallet</span>
<span>Wallet</span>
</a>
<a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-primary/5 transition-colors" href="#">
<span class="material-symbols-outlined">settings</span>
<span>Settings</span>
</a>
</nav>
</div>
<div class="flex flex-col gap-4">
<button class="w-full bg-primary text-background-dark font-bold py-3 rounded-lg flex items-center justify-center gap-2">
<span class="material-symbols-outlined">add_circle</span>
<span>Publish a Ride</span>
</button>
<div class="flex items-center gap-3 p-2 border-t border-slate-200 dark:border-primary/10 pt-4">
<div class="size-10 rounded-full bg-slate-200 dark:bg-primary/20 bg-cover bg-center" data-alt="User profile picture of Alex Rivers" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuApsGNC2cS3yA-S4bzftxcbkyLn-Z0-S7jiUVvPFILbcmR6Dildlpc0OHvRpyDzSnpxzO1bLmYI377YKeYJFkxf8F1ZZRmSoLHelRm0ik1OL8H-_4VV7D-QEyc4fGlh8En6vD48S7ll1yaKb4itmgda5ppSU0-b7vXXGWe214IZXFGVH-q-6HbrKd-0uRlNudGwrQCB9TwZeqbSifL6kgRDa_zSOoUTGBcYxY5258lmU579V6rSh_9IobM5eqMwtOLEj65hyKKKUY3S')"></div>
<div class="flex flex-col">
<span class="text-sm font-semibold">Alex Rivers</span>
<span class="text-xs text-primary">Premium Member</span>
</div>
</div>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-background-dark/50">
<!-- Header -->
<header class="sticky top-0 z-10 flex items-center justify-between px-8 py-4 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-primary/10">
<div class="flex items-center gap-4 flex-1">
<div class="relative w-full max-w-md">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
<input class="w-full bg-slate-100 dark:bg-primary/5 border-none rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary text-sm" placeholder="Search routes, drivers, or trips..." type="text"/>
</div>
</div>
<div class="flex items-center gap-3">
<button class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-primary/10 transition-colors">
<span class="material-symbols-outlined">dark_mode</span>
</button>
<button class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-primary/10 transition-colors relative">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-2 right-2 size-2 bg-primary rounded-full border-2 border-white dark:border-background-dark"></span>
</button>
<button class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-primary/10 transition-colors">
<span class="material-symbols-outlined">help</span>
</button>
</div>
</header>
<div class="p-8 space-y-8">
<!-- Welcome Section -->
<div>
<h1 class="text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight">Hello, Alex</h1>
<p class="text-slate-500 dark:text-slate-400 mt-1">Ready for your next journey today? You have 2 rides scheduled for this week.</p>
</div>
<!-- Quick Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-white dark:bg-primary/5 p-6 rounded-xl border border-slate-200 dark:border-primary/10 shadow-sm">
<p class="text-sm font-medium text-slate-500 dark:text-slate-400">Upcoming Trips</p>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold">4</span>
<span class="text-sm font-semibold text-primary">+1 this week</span>
</div>
</div>
<div class="bg-white dark:bg-primary/5 p-6 rounded-xl border border-slate-200 dark:border-primary/10 shadow-sm">
<p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Savings</p>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold">$128.50</span>
<span class="text-sm font-semibold text-primary">+$12.20</span>
</div>
</div>
<div class="bg-white dark:bg-primary/5 p-6 rounded-xl border border-slate-200 dark:border-primary/10 shadow-sm">
<p class="text-sm font-medium text-slate-500 dark:text-slate-400">Driver Rating</p>
<div class="flex items-baseline gap-2 mt-2">
<span class="text-3xl font-bold">4.9</span>
<span class="text-sm font-semibold text-primary">Top 1%</span>
</div>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Activity Feed: Next Trip -->
<div class="lg:col-span-2 space-y-4">
<h2 class="text-lg font-bold flex items-center gap-2">
<span class="material-symbols-outlined text-primary">event_upcoming</span>
                            Next Scheduled Trip
                        </h2>
<div class="bg-white dark:bg-primary/5 rounded-xl border border-slate-200 dark:border-primary/10 overflow-hidden shadow-sm">
<div class="h-48 bg-slate-200 dark:bg-slate-800 relative">
<div class="absolute inset-0 bg-cover bg-center opacity-80" data-alt="Map showing route between San Francisco and San Jose" data-location="San Francisco" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAUDwipcZEQM8BgLKEdIyuBxDIkeQXvnv5wINQw9Z6COOzmkSavzt3PUAFTmLLLyRy4eAbo9xk0Rcj88XGp8KtbAiF_tj9mKwLOKhp2ClgL_W6MFYV0JzrpQ_QqnGP2NAynl0Yt917dNezlIhhZL0zAfq_ar0NxJ6vx5bRL9-0XCoJRFZ-R0GD2ZUxMarH9JpD_l1UWgx_xmsTKvXwofLeK8xd-haZjT2qvQqmENu9ovcQC_IdX89M8knb-6IiPGY-HJSA5Zmox_8jt')"></div>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<div class="absolute bottom-4 left-4 text-white">
<div class="text-xs font-bold uppercase tracking-wider opacity-75">Today at 5:30 PM</div>
<div class="text-xl font-bold">San Francisco → San Jose</div>
</div>
</div>
<div class="p-6 flex items-center justify-between">
<div class="flex items-center gap-4">
<div class="size-12 rounded-full border-2 border-primary overflow-hidden">
<img alt="Driver Profile" class="w-full h-full object-cover" data-alt="Driver profile picture of Sarah J." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAt8-WWzik01j7VEkcKQEMio9hU-HTzzDdV22H3seKiuIWZ8PyO__C8nCNoqSmQgk49563UpPeZ5eSlKGd_ibbtJiJubU0qoGRunAwEAxctfX52OelJCHenVWSsk_aPiScGck4NgB0ryBVbeggW6NcfQ5O8CrQdqNvs6AIa9s1sd0gjJ3oPPCnfgO4bhsdIDsWKzJ4uw-c2C88A2B2O-LNd-OmzPTs25pLll2nZvsTil0MyPWf_UuVEzFcztRhUc9GNsji-ItilzfQP"/>
</div>
<div>
<p class="font-bold">Sarah J.</p>
<p class="text-sm text-slate-500 dark:text-slate-400">Tesla Model 3 • White</p>
</div>
</div>
<button class="px-6 py-2 bg-primary/10 text-primary border border-primary/20 rounded-lg font-bold hover:bg-primary/20 transition-all">
                                    View Details
                                </button>
</div>
</div>
</div>
<!-- Right Column Widgets -->
<div class="space-y-8">
<!-- Wallet Summary -->
<div class="bg-primary text-background-dark p-6 rounded-xl shadow-lg relative overflow-hidden">
<div class="absolute top-0 right-0 p-4 opacity-20">
<span class="material-symbols-outlined text-6xl">payments</span>
</div>
<p class="text-sm font-bold opacity-80 uppercase tracking-widest">Wallet Balance</p>
<h3 class="text-4xl font-black mt-1">$420.00</h3>
<button class="mt-6 w-full bg-background-dark/10 hover:bg-background-dark/20 py-2 rounded-lg font-bold border border-background-dark/20 transition-colors">
                                Top Up Balance
                            </button>
</div>
<!-- Recent Messages -->
<div class="space-y-4">
<div class="flex items-center justify-between">
<h2 class="text-lg font-bold">Recent Messages</h2>
<button class="text-primary text-sm font-bold">See all</button>
</div>
<div class="bg-white dark:bg-primary/5 rounded-xl border border-slate-200 dark:border-primary/10 divide-y divide-slate-100 dark:divide-primary/10">
<div class="p-4 flex gap-3 hover:bg-slate-50 dark:hover:bg-primary/10 transition-colors cursor-pointer">
<img class="size-10 rounded-full" data-alt="Profile of Mark Thompson" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDIfSCNhGP5h0Fy7xRfDVl_N1lzUa1tLdVHJl84Y06t0FuwWKK3MOboD2dV2vkbDAtm4GeHj7VJ3MISoea77nH6HVkxaAh1sjoxboCYHyrngmKBm-SJCiiudoRV4H90RlcIZFWipvc_8Aj4K79f2P6FCWAtqEi9dD_kxdCl5r97oHrzCw3br9E_i7ryoxLEHHL7uDFnFRXiddO3-3By-tASd5Uavke91n0qoyZc-ywUj5VjXmB5wW5vvWC3FVSH6TSnboZiJ_3JNcTB"/>
<div class="flex-1 overflow-hidden">
<div class="flex justify-between items-center">
<span class="font-bold text-sm">Mark Thompson</span>
<span class="text-[10px] text-slate-400">12m ago</span>
</div>
<p class="text-sm text-slate-500 dark:text-slate-400 truncate">I'll be at the pickup point in 5 minutes.</p>
</div>
</div>
<div class="p-4 flex gap-3 hover:bg-slate-50 dark:hover:bg-primary/10 transition-colors cursor-pointer">
<img class="size-10 rounded-full" data-alt="Profile of Elena Rodriguez" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDPFo_FHmr8uYjbZpclt9C_yRF2wXfbPJpuJdnYfbI7flgq6fepW2t-Hf_bGWe60Rrloh7QWV2UnKrT-Fn5W20VNHSPjM3HCX6WuIqLBcwERvBXqzrcZIy5ujZdBC98CooPUakHmVdeNTgiMjPAlXm88CJqhBcfnJTQyo216tGS4-hEMabzufwOWHqwm1UAYZj4M7wuJAlyligr-lPWNqcxcT_iTKvLFOl8WN30_JQKo6nogogIyVngsDFn_c04N4cybWlN1mjdsQ8v"/>
<div class="flex-1 overflow-hidden">
<div class="flex justify-between items-center">
<span class="font-bold text-sm">Elena Rodriguez</span>
<span class="text-[10px] text-slate-400">2h ago</span>
</div>
<p class="text-sm text-slate-500 dark:text-slate-400 truncate">Thanks for the smooth ride yesterday!</p>
</div>
</div>
<div class="p-4 flex gap-3 hover:bg-slate-50 dark:hover:bg-primary/10 transition-colors cursor-pointer">
<img class="size-10 rounded-full" data-alt="Profile of David Kim" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-SPBhT7qhXqIJ6tktpBCxB6zdet6Xb106G6Xl4Uzbp5EOttcDGL2Ft93plu2BAeshJnadShyWPu7x38HaLLCD8svqXgDDy-lMI8GFWF1qPXAOTgRfUQnG5j0AQDx95PKOkMWHIVf8fzIbtezXOVQvQUfuqlfHXHpHJJ1-ykvGiYrT9jWXP-JxM_1yzEDETJr0S6Mw2ruTFRVrArIVVUS5FzQv74BugJi3Knb5zIWJlryic_x1dKEO7O48c82vW2Mchnc6I4Mz6JLj"/>
<div class="flex-1 overflow-hidden">
<div class="flex justify-between items-center">
<span class="font-bold text-sm">David Kim</span>
<span class="text-[10px] text-slate-400">5h ago</span>
</div>
<p class="text-sm text-slate-500 dark:text-slate-400 truncate">Is there space for one extra bag?</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>
