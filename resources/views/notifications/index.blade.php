@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-4 pb-8">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="w-9 h-9 rounded-xl bg-white dark:bg-card-dark border border-slate-200 dark:border-primary/10
                      flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Notifications</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    @if ($unreadCount > 0)
                        <span class="font-bold text-primary">{{ $unreadCount }}</span> non lue{{ $unreadCount > 1 ? 's' : '' }}
                    @else
                        Tout est lu
                    @endif
                </p>
            </div>
        </div>

        {{-- Actions globales --}}
        @if ($notifications->total() > 0)
            <div class="flex items-center gap-2">
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 px-3 py-2 rounded-xl
                                       text-xs font-bold text-primary border border-primary/30
                                       hover:bg-primary/5 transition-colors">
                            <span class="material-symbols-outlined text-base">done_all</span>
                            Tout lire
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('notifications.destroy-all') }}"
                      onsubmit="return confirm('Supprimer toutes les notifications ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-xl
                                   text-xs font-bold text-red-500 border border-red-200 dark:border-red-500/30
                                   hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <span class="material-symbols-outlined text-base">delete_sweep</span>
                        Tout effacer
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- ── Filtres ── --}}
    <div class="flex gap-2">
        @foreach ([
            ['value' => 'all',    'label' => 'Toutes',   'icon' => 'notifications'],
            ['value' => 'unread', 'label' => 'Non lues', 'icon' => 'mark_email_unread'],
            ['value' => 'read',   'label' => 'Lues',     'icon' => 'mark_email_read'],
        ] as $tab)
            <a href="{{ route('notifications.index', ['filter' => $tab['value']]) }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold transition-all
                      {{ $filter === $tab['value']
                          ? 'bg-primary text-white shadow-md shadow-primary/20'
                          : 'bg-white dark:bg-card-dark text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10 hover:border-primary/30 hover:text-primary' }}">
                <span class="material-symbols-outlined text-base">{{ $tab['icon'] }}</span>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- ── Liste ── --}}
    @if ($notifications->isEmpty())
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/10 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-3xl">notifications_off</span>
            </div>
            <p class="font-black text-slate-500 dark:text-slate-400">Aucune notification</p>
            <p class="text-xs text-slate-400 mt-1">
                @if ($filter === 'unread') Vous avez tout lu.
                @elseif ($filter === 'read') Aucune notification lue.
                @else Vous n'avez aucune notification pour le moment.
                @endif
            </p>
        </div>
    @else
        <div class="bg-white dark:bg-card-dark rounded-2xl border border-slate-100 dark:border-primary/10 shadow-sm overflow-hidden">
            @foreach ($notifications as $notif)
                @php
                    $data    = $notif->data;
                    $isRead  = !is_null($notif->read_at);
                    $icon    = $data['icon'] ?? 'notifications';
                    $iconColors = [
                        'check_circle'   => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10',
                        'hail'           => 'text-primary bg-primary/10',
                        'verified'       => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10',
                        'cancel'         => 'text-red-400 bg-red-50 dark:bg-red-500/10',
                        'notifications'  => 'text-slate-400 bg-slate-100 dark:bg-white/10',
                        'warning'        => 'text-orange-500 bg-orange-50 dark:bg-orange-500/10',
                        'lock_reset'     => 'text-blue-500 bg-blue-50 dark:bg-blue-500/10',
                    ];
                    $colorClass = $iconColors[$icon] ?? 'text-slate-400 bg-slate-100 dark:bg-white/10';
                @endphp
                <div class="flex items-start gap-4 px-5 py-4
                            {{ !$loop->last ? 'border-b border-slate-100 dark:border-white/5' : '' }}
                            {{ $isRead ? 'opacity-75' : 'bg-primary/[0.02] dark:bg-primary/5' }}
                            hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">

                    {{-- Indicateur non-lu --}}
                    <div class="flex-shrink-0 mt-1 relative">
                        <div class="w-10 h-10 rounded-xl {{ $colorClass }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
                        </div>
                        @unless ($isRead)
                            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-primary border-2 border-white dark:border-card-dark"></span>
                        @endunless
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="block group-hover:text-primary transition-colors">
                            <p class="text-sm font-black text-slate-900 dark:text-white leading-snug">
                                {{ $data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                {{ $data['body'] ?? '' }}
                            </p>
                        </a>
                        <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
                            {{ $notif->created_at->diffForHumans() }}
                            @if ($isRead)
                                · <span class="text-slate-400">Lu</span>
                            @else
                                · <span class="text-primary font-bold">Non lu</span>
                            @endif
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex-shrink-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        @unless ($isRead)
                            <a href="{{ route('notifications.read', $notif->id) }}"
                               title="Marquer comme lu"
                               class="w-8 h-8 rounded-lg flex items-center justify-center
                                      text-primary hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-base">done</span>
                            </a>
                        @endunless
                        <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center
                                           text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if ($notifications->hasPages())
            <div class="flex items-center justify-center gap-2 pt-2">
                {{-- Précédent --}}
                @if ($notifications->onFirstPage())
                    <span class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                                 flex items-center justify-center text-slate-300 dark:text-slate-600 cursor-not-allowed">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $notifications->previousPageUrl() }}"
                       class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                              flex items-center justify-center text-slate-500 dark:text-slate-400
                              hover:border-primary/30 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </a>
                @endif

                {{-- Pages --}}
                @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="w-9 h-9 rounded-xl text-sm font-bold flex items-center justify-center transition-all
                              {{ $page === $notifications->currentPage()
                                  ? 'bg-primary text-white shadow-md shadow-primary/20'
                                  : 'border border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 hover:border-primary/30 hover:text-primary' }}">
                        {{ $page }}
                    </a>
                @endforeach

                {{-- Suivant --}}
                @if ($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}"
                       class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                              flex items-center justify-center text-slate-500 dark:text-slate-400
                              hover:border-primary/30 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                @else
                    <span class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                                 flex items-center justify-center text-slate-300 dark:text-slate-600 cursor-not-allowed">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </span>
                @endif
            </div>
        @endif
    @endif

</div>

{{-- Redirect après action POST (marquer lu / supprimer) → revenir sur la même page avec filtre --}}
@push('scripts')
<script>
    // Après soumission des formulaires, rediriger vers la page courante
    document.querySelectorAll('form[action*="notifications"]').forEach(form => {
        form.addEventListener('submit', function () {
            const redirect = '{{ request()->fullUrl() }}';
            const hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = '_redirect';
            hidden.value = redirect;
            // non utilisé directement, le contrôleur fait back()
        });
    });
</script>
@endpush
@endsection
