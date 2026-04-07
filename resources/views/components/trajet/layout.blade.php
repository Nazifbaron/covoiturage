<!DOCTYPE html>

<html class="light" lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>{{$title ?? 'Covoiturage -  Partagez votre parcours'}}</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <script id="tailwind-config">
                tailwind.config = {
                    darkMode: "class",
                    theme: {
                        extend: {
                            colors: {
                                "primary": "#137fec",
                                "background-light": "#f6f7f8",
                                "background-dark": "#101922",
                            },
                            fontFamily: {
                                "display": ["Plus Jakarta Sans", "sans-serif"]
                            },
                            borderRadius: {
                                "DEFAULT": "0.25rem",
                                "lg": "0.5rem",
                                "xl": "0.75rem",
                                "full": "9999px"
                            },
                        },
                    },
                }
        </script>
    </head>
    <body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
        <div class="layout-container flex h-full grow flex-col">
            <!-- Top Navigation Bar -->
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-10 py-3">
                <div class="flex items-center gap-4">
                    <div class="size-8 text-primary">
                        <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-slate-900 dark:text-white text-xl font-extrabold leading-tight tracking-tight">CovoitExpress</h2>
                </div>

            </header>
            <main class="flex-1 flex flex-col items-center">
                {{ $slot }}
            </main>
            <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-10 py-6">
                <div class="max-w-6xl mx-auto flex justify-between items-center text-slate-500 dark:text-slate-400 text-sm font-medium">
                    <p>© 2026 CovoitExpress Inc.</p>
                    <div class="flex gap-6">
                        <a class="hover:text-primary transition-colors" href="#">Help Center</a>
                        <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
                        <a class="hover:text-primary transition-colors" href="#">Safety Tips</a>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>

