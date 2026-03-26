@extends('layouts.app')

@push('styles')
<style>
    /* ── Bulles ─────────────────────────────────────── */
    .msg-in  { border-radius: 4px 18px 18px 18px; }
    .msg-out { border-radius: 18px 4px 18px 18px; }

    @keyframes msgPop {
        from { opacity: 0; transform: translateY(6px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0)  scale(1); }
    }
    .msg-pop { animation: msgPop 0.18s ease-out forwards; }

    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
        40%            { transform: scale(1);   opacity: 1;   }
    }
    .typing-dot:nth-child(1) { animation: typingDot 1.2s infinite 0s; }
    .typing-dot:nth-child(2) { animation: typingDot 1.2s infinite .2s; }
    .typing-dot:nth-child(3) { animation: typingDot 1.2s infinite .4s; }

    /* ── Scrollbars ─────────────────────────────────── */
    #chat-messages, #conv-list {
        scroll-behavior: smooth;
    }
    #chat-messages::-webkit-scrollbar,
    #conv-list::-webkit-scrollbar { width: 4px; }
    #chat-messages::-webkit-scrollbar-track,
    #conv-list::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb,
    #conv-list::-webkit-scrollbar-thumb {
        background: #6C2BD933;
        border-radius: 99px;
    }

    /* ── Input focus ────────────────────────────────── */
    #msg-input:focus { box-shadow: 0 0 0 3px #6C2BD920; outline: none; }
    #search-input:focus { outline: none; }

    /* ── Conversation item active ───────────────────── */
    .conv-item.active {
        background: linear-gradient(90deg, #6C2BD910 0%, transparent 100%);
        border-left: 3px solid #6C2BD9;
    }
    .conv-item { border-left: 3px solid transparent; }

    /* ── Panneau vide ───────────────────────────────── */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }
    .empty-float { animation: float 3s ease-in-out infinite; }

    /* ── Mobile : masquer liste quand chat ouvert ───── */
    @media (max-width: 767px) {
        #conv-panel.hidden-mobile { display: none !important; }
        #chat-panel.hidden-mobile { display: none !important; }

        /* Sur mobile : chat plein écran, au-dessus de tout */
        #chat-panel {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 50;
            margin: 0;
            border-radius: 0;
            height: 100dvh;
            height: -webkit-fill-available;
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Safe area en haut pour les encoches */
        #chat-header {
            padding-top: max(0.875rem, env(safe-area-inset-top));
        }

        /* Input safe area */
        #chat-input-wrap {
            padding-bottom: max(1rem, env(safe-area-inset-bottom));
        }
    }

</style>
@endpush

@section('content')
<div class="flex flex-col gap-0" style="height:100dvh;max-height:100dvh">

    {{-- ══════════════════════════════════════════════ --}}
    {{-- PAGE HEADER                                    --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Messages</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Toutes vos conversations liées à vos trajets
            </p>
        </div>
        @php $totalUnread = $conversations->sum('unread_count'); @endphp
        @if($totalUnread > 0)
        <span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-[#6C2BD9] to-[#8B5CF6]
                     text-white text-sm font-black px-3 py-1.5 rounded-full shadow-md shadow-violet-500/20">
            <span class="material-symbols-outlined text-base">mark_chat_unread</span>
            {{ $totalUnread }} non lu{{ $totalUnread > 1 ? 's' : '' }}
        </span>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════ --}}
    {{-- LAYOUT : LISTE + CHAT                          --}}
    {{-- ══════════════════════════════════════════════ --}}
    <div class="flex gap-4 flex-1 min-h-0">

        {{-- ────────────────────────────────────────── --}}
        {{-- PANNEAU GAUCHE : liste des conversations   --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="conv-panel"
             class="w-full md:w-80 lg:w-96 flex-shrink-0 flex flex-col
                    bg-white dark:bg-card-dark
                    border border-slate-100 dark:border-primary/10
                    rounded-2xl shadow-sm overflow-hidden">

            {{-- Recherche --}}
            <div class="px-4 py-3 border-b border-slate-100 dark:border-white/5 flex-shrink-0">
                <div class="flex items-center gap-2 px-3 py-2
                            bg-slate-50 dark:bg-white/5
                            border border-slate-100 dark:border-white/10 rounded-xl">
                    <span class="material-symbols-outlined text-slate-400 text-lg">search</span>
                    <input id="search-input" type="text" placeholder="Rechercher une conversation…"
                           class="flex-1 bg-transparent text-sm font-medium text-slate-700 dark:text-slate-300
                                  placeholder-slate-400 border-none"
                           oninput="filterConversations(this.value)" />
                </div>
            </div>

            {{-- Liste --}}
            <div id="conv-list" class="flex-1 overflow-y-auto divide-y divide-slate-50 dark:divide-white/5">

                @forelse($conversations as $conv)
                @php
                    $other   = $conv->participant;
                    $lastMsg = $conv->lastMessage;
                    $isMine  = $lastMsg && (int)$lastMsg->sender_id === (int)Auth::id();
                    $initial = strtoupper(substr($other->first_name ?? $other->name ?? '?', 0, 1));
                    $colors  = [
                        'from-violet-400 to-purple-600',
                        'from-blue-400 to-indigo-500',
                        'from-orange-400 to-red-500',
                        'from-emerald-400 to-teal-500',
                        'from-pink-400 to-rose-500',
                    ];
                    $grad    = $colors[$loop->index % count($colors)];
                    $isFirst = $loop->first;
                @endphp

                <div class="conv-item {{ $isFirst ? 'active' : '' }} cursor-pointer
                            flex items-center gap-3 px-4 py-3.5
                            hover:bg-violet-50/60 dark:hover:bg-white/5 transition-all duration-150"
                     data-trip-id="{{ $conv->trip_id }}"
                     data-search-text="{{ strtolower(($other->first_name ?? $other->name ?? '').' '.($other->last_name ?? '').' '.$conv->trip->departure_city.' '.$conv->trip->arrival_city) }}"
                     onclick="openConversation({{ $conv->trip_id }}, this)">

                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $grad }}
                                    flex items-center justify-center font-black text-white text-sm shadow-sm">
                            {{ $initial }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full
                                     bg-emerald-400 border-2 border-white dark:border-card-dark"></span>
                    </div>

                    {{-- Infos --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <p class="font-black text-sm text-slate-900 dark:text-white truncate">
                                {{ $other->first_name ?? '' }} {{ $other->last_name ?? $other->name ?? '' }}
                            </p>
                            <span class="text-[11px] text-slate-400 flex-shrink-0">
                                @if($lastMsg)
                                    {{ $lastMsg->created_at->isToday()
                                        ? $lastMsg->created_at->format('H:i')
                                        : ($lastMsg->created_at->isYesterday() ? 'Hier' : $lastMsg->created_at->format('d M')) }}
                                @endif
                            </span>
                        </div>

                        <p class="text-xs truncate
                                  {{ $conv->unread_count > 0
                                      ? 'font-bold text-slate-800 dark:text-slate-100'
                                      : 'font-medium text-slate-500 dark:text-slate-400' }}">
                            @if($isMine)<span class="text-slate-400 font-normal">Vous : </span>@endif
                            {{ $lastMsg?->content ?? 'Démarrez la conversation' }}
                        </p>

                        <span class="inline-flex items-center gap-1 mt-1.5
                                     text-[10px] font-bold text-[#6C2BD9]
                                     bg-violet-100 dark:bg-violet-500/20 px-2 py-0.5 rounded-full">
                            <span class="material-symbols-outlined" style="font-size:11px">route</span>
                            {{ $conv->trip->departure_city }} → {{ $conv->trip->arrival_city }}
                        </span>
                    </div>

                    {{-- Badge non lu --}}
                    @if($conv->unread_count > 0)
                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-[#6C2BD9] to-[#8B5CF6]
                                flex items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-black text-white">{{ $conv->unread_count }}</span>
                    </div>
                    @endif
                </div>

                @empty
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-violet-100 dark:bg-violet-500/20
                                flex items-center justify-center mb-4 empty-float">
                        <span class="material-symbols-outlined text-[#6C2BD9] text-2xl">forum</span>
                    </div>
                    <p class="font-black text-sm text-slate-700 dark:text-slate-300 mb-1">
                        Aucune conversation
                    </p>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Vos échanges avec les
                        {{ Auth::user()->role === 'driver' ? 'passagers' : 'conducteurs' }}
                        apparaîtront ici.
                    </p>
                </div>
                @endforelse

            </div>
        </div>

        {{-- ────────────────────────────────────────── --}}
        {{-- PANNEAU DROIT : fenêtre de chat            --}}
        {{-- ────────────────────────────────────────── --}}
        <div id="chat-panel" class="flex-1 flex flex-col min-w-0
                                    bg-white dark:bg-card-dark
                                    border border-slate-100 dark:border-primary/10
                                    rounded-2xl shadow-sm overflow-hidden">

            {{-- ── État vide (aucune conversation sélectionnée) ── --}}
            <div id="chat-empty"
                 class="{{ $conversations->isEmpty() || true ? 'flex' : 'hidden' }}
                        flex-col items-center justify-center flex-1 gap-4 px-8 text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-100 to-purple-100
                            dark:from-violet-500/20 dark:to-purple-500/10
                            flex items-center justify-center empty-float shadow-inner">
                    <span class="material-symbols-outlined text-[#6C2BD9] text-4xl">chat_bubble</span>
                </div>
                <div>
                    <p class="font-black text-lg text-slate-800 dark:text-white mb-1">
                        Sélectionnez une conversation
                    </p>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
                        Cliquez sur une conversation à gauche pour afficher les messages.
                    </p>
                </div>
            </div>

            {{-- ── Zone de chat (chargée dynamiquement) ── --}}
            <div id="chat-active" class="hidden flex-col flex-1 min-h-0">

                {{-- Header du chat --}}
                <div id="chat-header"
                     class="flex items-center gap-3 px-5 py-3.5 flex-shrink-0
                            border-b border-slate-100 dark:border-white/5">

                    {{-- Bouton retour mobile --}}
                    <button onclick="backToList()"
                            class="md:hidden w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                                   flex items-center justify-center flex-shrink-0
                                   hover:bg-slate-50 dark:hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined text-slate-500 text-xl">arrow_back</span>
                    </button>

                    <div class="relative flex-shrink-0" id="header-avatar-wrap">
                        <div id="header-avatar"
                             class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-purple-600
                                    flex items-center justify-center font-black text-white text-sm shadow-md">
                            ?
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full
                                     bg-emerald-400 border-2 border-white dark:border-card-dark"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p id="header-name" class="font-black text-slate-900 dark:text-white text-sm"></p>
                        <p id="header-role" class="text-xs font-semibold text-slate-400"></p>
                    </div>

                    <div id="header-route"
                         class="flex-shrink-0 hidden sm:flex items-center gap-2 px-3 py-1.5
                                rounded-xl bg-slate-50 dark:bg-white/5
                                border border-slate-100 dark:border-white/10
                                text-xs font-bold text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[#6C2BD9]" style="font-size:16px">route</span>
                        <span id="header-route-text"></span>
                    </div>

                    <div class="w-2 h-2 rounded-full bg-[#6C2BD9] flex-shrink-0
                                opacity-0 transition-opacity duration-300"
                         id="poll-dot" title="Synchronisation active"></div>
                </div>

                {{-- Messages --}}
                <div id="chat-messages"
                     class="flex-1 overflow-y-auto px-4 py-4 space-y-3 min-h-0">

                    <div id="msgs-separator" class="flex items-center gap-3 my-4">
                        <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
                        <span class="text-xs font-semibold text-slate-400 px-3 py-1
                                     rounded-full bg-slate-50 dark:bg-white/5">
                            Conversation démarrée
                        </span>
                        <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
                    </div>

                    <div id="msgs-container"></div>

                    <div id="typing-indicator"
                         class="hidden flex justify-start gap-2 items-end">
                        <div id="typing-avatar"
                             class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600
                                    flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">?</div>
                        <div class="px-4 py-3 bg-white dark:bg-card-dark border border-slate-100
                                    dark:border-white/10 rounded-lg msg-in shadow-sm">
                            <div class="flex items-center gap-1">
                                <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            </div>
                        </div>
                    </div>

                    <div id="scroll-anchor"></div>
                </div>

                {{-- Saisie --}}
                <div id="chat-input-wrap" class="flex-shrink-0 px-4 pb-4 pt-2">
                    <div class="flex items-end gap-3 p-3
                                bg-slate-50 dark:bg-white/5
                                border border-slate-100 dark:border-white/10 rounded-2xl">
                        <textarea id="msg-input" rows="1"
                                  placeholder="Votre message…"
                                  class="flex-1 resize-none bg-transparent text-sm font-medium
                                         text-slate-800 dark:text-slate-200 placeholder-slate-400
                                         max-h-32 py-2 px-1 border-none focus:ring-0
                                         transition-all duration-200"
                                  oninput="autoResize(this); handleTyping();"
                                  onkeydown="handleKeydown(event)"></textarea>
                        <button id="send-btn" onclick="sendMessage()"
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6C2BD9] to-[#8B5CF6]
                                       hover:opacity-90 text-white
                                       flex items-center justify-center flex-shrink-0
                                       transition-all shadow-md shadow-violet-500/20
                                       disabled:opacity-40 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined text-xl">send</span>
                        </button>
                    </div>
                    <p class="text-center text-[11px] text-slate-400 mt-2 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-xs">lock</span>
                        Conversation liée à la course
                    </p>
                </div>
            </div>

        </div>{{-- fin chat-panel --}}
    </div>{{-- fin layout --}}
</div>
@endsection


@push('scripts')
<script>
// ════════════════════════════════════════════════════════════════════
//  CONFIG GLOBALE
// ════════════════════════════════════════════════════════════════════
const AUTH_ID    = {{ Auth::id() }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const MY_INITIAL = '{{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? "?", 0, 1)) }}';

// Données des conversations passées depuis le contrôleur
const CONVERSATIONS = @json($conversationsData);
// ════════════════════════════════════════════════════════════════════
//  ÉTAT COURANT
// ════════════════════════════════════════════════════════════════════
let currentConv      = null;  // objet de CONVERSATIONS
let lastMessageId    = 0;
let isSending        = false;
let typingTimeout    = null;
let pollTimeout      = null;
let interlocInitial  = '?';

// ── DOM ──────────────────────────────────────────────────────────
const chatEmpty   = document.getElementById('chat-empty');
const chatActive  = document.getElementById('chat-active');
const msgsBox     = document.getElementById('msgs-container');
const inputEl     = document.getElementById('msg-input');
const sendBtn     = document.getElementById('send-btn');
const typingEl    = document.getElementById('typing-indicator');
const typingAv    = document.getElementById('typing-avatar');
const pollDot     = document.getElementById('poll-dot');
const anchor      = document.getElementById('scroll-anchor');

// ── Init : sur desktop ouvre la 1ère conv, sur mobile affiche la liste ──
const isMobile = () => window.innerWidth < 768;

if (isMobile()) {
    // Mobile : montrer uniquement la liste, le chat reste caché
    document.getElementById('chat-panel').classList.add('hidden-mobile');
} else if (CONVERSATIONS.length > 0) {
    const firstItem = document.querySelector('.conv-item');
    if (firstItem) openConversation(CONVERSATIONS[0].trip_id, firstItem);
}

// ── Clavier virtuel mobile ──────────────────────────────────────────
function updateChatPanelHeight() {
    const h = window.visualViewport?.height ?? window.innerHeight;
    const panel = document.getElementById('chat-panel');
    if (panel && isMobile()) panel.style.height = h + 'px';
    scrollToBottom();
}
if (window.visualViewport) {
    window.visualViewport.addEventListener('resize', updateChatPanelHeight);
}

// ════════════════════════════════════════════════════════════════════
//  OUVRIR UNE CONVERSATION
// ════════════════════════════════════════════════════════════════════
async function openConversation(tripId, clickedEl) {
    // Arrêt du poll précédent
    clearTimeout(pollTimeout);
    clearTimeout(typingTimeout);

    // Marquage item actif
    document.querySelectorAll('.conv-item').forEach(el => el.classList.remove('active'));
    clickedEl.classList.add('active');

    // Trouver la conv
    currentConv = CONVERSATIONS.find(c => c.trip_id == tripId);
    if (!currentConv) return;
    interlocInitial = currentConv.initial;

    // Remplir l'en-tête
    document.getElementById('header-avatar').textContent  = currentConv.initial;
    document.getElementById('header-name').textContent    = currentConv.name;
    document.getElementById('header-role').textContent    = currentConv.role === 'driver' ? '🚗 Conducteur' : '🙋 Passager';
    document.getElementById('header-route-text').textContent =
        `${currentConv.departure_city} → ${currentConv.arrival_city}`;
    typingAv.textContent = currentConv.initial;

    // Basculer les panneaux
    chatEmpty.classList.add('hidden');
    chatEmpty.classList.remove('flex');
    chatActive.classList.remove('hidden');
    chatActive.classList.add('flex');

    // Mobile : masquer liste, afficher chat plein écran
    if (isMobile()) {
        document.getElementById('conv-panel').classList.add('hidden-mobile');
        document.getElementById('chat-panel').classList.remove('hidden-mobile');
        updateChatPanelHeight();
    }

    // Afficher le dot de poll
    pollDot.style.opacity = '1';

    // Vider les messages et charger depuis l'API
    msgsBox.innerHTML = '';
    lastMessageId = 0;

    try {
        const res = await fetch(`${currentConv.url_poll}?after=0`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error();
        const data = await res.json();
        (data.messages || []).forEach(m => appendMessage(m));
        if (data.messages?.length) lastMessageId = data.messages.at(-1).id;
    } catch(e) { console.warn('Init load error', e); }

    scrollToBottom();
    markRead();
    inputEl.focus();
    poll();
}

// ════════════════════════════════════════════════════════════════════
//  POLLING
// ════════════════════════════════════════════════════════════════════
async function poll() {
    if (!currentConv) return;
    try {
        const res = await fetch(`${currentConv.url_poll}?after=${lastMessageId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error(`Poll ${res.status}`);
        const data = await res.json();

        pollDot.style.opacity = '0.3';
        setTimeout(() => pollDot.style.opacity = '1', 300);

        if (data.messages?.length > 0) {
            data.messages.forEach(m => appendMessage(m));
            lastMessageId = data.messages.at(-1).id;
            scrollToBottom();
            markRead();

            // Mettre à jour l'aperçu dans la liste
            updateConvPreview(currentConv.trip_id, data.messages.at(-1));
        }

        typingEl.classList.toggle('hidden',  !data.is_typing);
        typingEl.classList.toggle('flex',     data.is_typing);
        if (data.is_typing) scrollToBottom();

    } catch(e) { console.warn('Poll error:', e); }
    pollTimeout = setTimeout(poll, 5000);
}

// ════════════════════════════════════════════════════════════════════
//  ENVOI DE MESSAGE
// ════════════════════════════════════════════════════════════════════
async function sendMessage() {
    if (!currentConv) return;
    const content = inputEl.value.trim();
    if (!content || isSending) return;

    isSending = true;
    sendBtn.disabled = true;

    const tempId = 'temp-' + Date.now();
    appendMessage({ id: tempId, sender_id: AUTH_ID, content, created_at: nowISO(), read_at: null });
    inputEl.value = '';
    autoResize(inputEl);
    scrollToBottom();
    updateConvPreview(currentConv.trip_id, { sender_id: AUTH_ID, content, created_at: nowISO() });

    try {
        const res = await fetch(currentConv.url_send, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content }),
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        document.getElementById(tempId)?.remove();
        appendMessage(data.message);
        lastMessageId = Math.max(lastMessageId, data.message.id);
        scrollToBottom();
    } catch(e) {
        console.error('Send error:', e);
        const el = document.getElementById(tempId);
        if (el) {
            const p = document.createElement('p');
            p.className = 'text-xs text-red-400 mt-0.5 text-right';
            p.textContent = 'Échec — réessayez';
            el.appendChild(p);
        }
    }

    isSending = false;
    sendBtn.disabled = false;
    inputEl.focus();
}

// ════════════════════════════════════════════════════════════════════
//  RENDU D'UNE BULLE
// ════════════════════════════════════════════════════════════════════
function appendMessage(msg) {
    const isMine = parseInt(msg.sender_id) === parseInt(AUTH_ID);

    const time = msg.created_at
        ? (msg.created_at.includes('T')
            ? new Date(msg.created_at).toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' })
            : msg.created_at)
        : '';

    const avatarMine = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#6C2BD9]/80 to-emerald-500
                    flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
            ${MY_INITIAL}
        </div>`;

    const avatarOther = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600
                    flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
            ${interlocInitial}
        </div>`;

    const bubbleClass = isMine
        ? 'bg-gradient-to-br from-[#6C2BD9] to-[#8B5CF6] text-white msg-out shadow-md shadow-violet-500/20'
        : 'bg-white dark:bg-card-dark text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-white/10 msg-in shadow-sm';

    const check = msg.read_at
        ? '<span class="material-symbols-outlined text-xs text-violet-300">done_all</span>'
        : '<span class="material-symbols-outlined text-xs text-slate-300/70">done</span>';

    const el = document.createElement('div');
    el.id = String(msg.id).startsWith('temp-') ? msg.id : `msg-${msg.id}`;
    el.className = `flex ${isMine ? 'justify-end' : 'justify-start'} gap-2 items-end msg-pop`;
    el.innerHTML = `
        ${!isMine ? avatarOther : ''}
        <div class="max-w-[75%]">
            <div class="px-4 py-2.5 text-sm font-medium msg-content ${bubbleClass}">
                ${escapeHtml(msg.content)}
            </div>
            <p class="text-xs text-slate-400 mt-1 ${isMine ? 'text-right' : 'text-left'}">
                ${time}${isMine ? ' ' + check : ''}
            </p>
        </div>
        ${isMine ? avatarMine : ''}
    `;
    msgsBox.appendChild(el);
}

// ════════════════════════════════════════════════════════════════════
//  MISE À JOUR APERÇU LISTE
// ════════════════════════════════════════════════════════════════════
function updateConvPreview(tripId, msg) {
    const item = document.querySelector(`.conv-item[data-trip-id="${tripId}"]`);
    if (!item) return;
    const preview = item.querySelector('.text-xs.truncate');
    if (!preview) return;
    const isMine = parseInt(msg.sender_id) === parseInt(AUTH_ID);
    preview.innerHTML = isMine
        ? `<span class="text-slate-400 font-normal">Vous : </span>${escapeHtml(msg.content)}`
        : escapeHtml(msg.content);
    preview.classList.remove('font-bold', 'text-slate-800', 'dark:text-slate-100');

    // Remonter en tête de liste
    const list = document.getElementById('conv-list');
    list.prepend(item);
}

// ════════════════════════════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════════════════════════════
async function markRead() {
    if (!currentConv) return;
    try {
        await fetch(currentConv.url_read, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' }
        });
    } catch(e) {}
}

async function handleTyping() {
    if (!currentConv) return;
    clearTimeout(typingTimeout);
    try {
        await fetch(currentConv.url_typing, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' }
        });
    } catch(e) {}
    typingTimeout = setTimeout(async () => {
        try {
            await fetch(currentConv.url_typing_stop, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' }
            });
        } catch(e) {}
    }, 3000);
}

function filterConversations(query) {
    const q = query.toLowerCase().trim();
    document.querySelectorAll('.conv-item').forEach(el => {
        const text = el.dataset.searchText || '';
        el.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
}

function backToList() {
    document.getElementById('conv-panel').classList.remove('hidden-mobile');
    document.getElementById('chat-panel').classList.add('hidden-mobile');
}

function scrollToBottom() { anchor.scrollIntoView({ behavior: 'smooth' }); }
function nowISO()         { return new Date().toISOString(); }
function autoResize(el)   { el.style.height = 'auto'; el.style.height = Math.min(el.scrollHeight, 128) + 'px'; }
function handleKeydown(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } }
function escapeHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

window.addEventListener('beforeunload', () => {
    clearTimeout(pollTimeout);
    clearTimeout(typingTimeout);
    if (currentConv) {
        navigator.sendBeacon(currentConv.url_typing_stop,
            new Blob([JSON.stringify({ _token: CSRF_TOKEN })], { type: 'application/json' }));
    }
});
</script>
@endpush
