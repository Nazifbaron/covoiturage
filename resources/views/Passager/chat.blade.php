@extends('layouts.app')

@push('styles')
<style>
    /* Bulle de message entrante */
    .msg-in  { border-radius: 4px 18px 18px 18px; }
    /* Bulle de message sortante */
    .msg-out { border-radius: 18px 4px 18px 18px; }

    /* Animation apparition message */
    @keyframes msgPop {
        from { opacity: 0; transform: translateY(8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .msg-pop { animation: msgPop 0.2s ease-out forwards; }

    /* Pulse "en train d'écrire" */
    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
        40%            { transform: scale(1);   opacity: 1;   }
    }
    .typing-dot:nth-child(1) { animation: typingDot 1.2s infinite 0s; }
    .typing-dot:nth-child(2) { animation: typingDot 1.2s infinite .2s; }
    .typing-dot:nth-child(3) { animation: typingDot 1.2s infinite .4s; }

    /* Zone messages scrollable */
    #chat-messages { scroll-behavior: smooth; }
    #chat-messages::-webkit-scrollbar { width: 4px; }
    #chat-messages::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb { background: #13ec4933; border-radius: 99px; }

    /* Input focus glow */
    #msg-input:focus { box-shadow: 0 0 0 3px #13ec4920; }

    /* Fix clavier mobile */
    #chat-root {
        height: calc(100vh - 7rem);
        height: calc(100dvh - 7rem); /* dvh = dynamic : se recalcule avec le clavier */
    }
</style>
@endpush

@section('content')
<div id="chat-root" class="max-w-2xl mx-auto flex flex-col">

    {{-- ── Header conversation ── --}}
    <div class="flex items-center gap-3 px-4 py-3
                bg-white dark:bg-card-dark
                border border-slate-100 dark:border-primary/10
                rounded-2xl shadow-sm mb-3 flex-shrink-0">

        <a href="{{ route('driver.requests') }}"
           class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10 transition-colors flex-shrink-0">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>

        {{-- Avatar passager --}}
        <div class="relative flex-shrink-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-purple-600
                        flex items-center justify-center font-black text-white text-sm shadow-md shadow-violet-200 dark:shadow-violet-900/30">
                {{ strtoupper(substr($passenger->first_name ?? $passenger->name ?? '?', 0, 1)) }}
            </div>
            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-white dark:border-card-dark" id="online-dot"></span>
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-black text-slate-900 dark:text-white text-sm">
                {{ $passenger->first_name ?? '' }} {{ $passenger->last_name ?? $passenger->name ?? '' }}
            </p>
            <p class="text-xs text-emerald-500 font-semibold" id="status-text">En ligne</p>
        </div>

        {{-- Infos trajet --}}
        <div class="flex-shrink-0 hidden sm:flex items-center gap-2 px-3 py-1.5
                    rounded-xl bg-slate-50 dark:bg-white/5
                    border border-slate-100 dark:border-white/10 text-xs font-bold text-slate-600 dark:text-slate-400">
            <span class="material-symbols-outlined text-primary" style="font-size:16px">route</span>
            {{ $trip->departure_city }} → {{ $trip->arrival_city }}
        </div>

        {{-- Indicateur polling --}}
        <div class="w-2 h-2 rounded-full bg-primary" id="poll-indicator" title="Synchronisation active"></div>
    </div>

    {{-- ── Zone messages ── --}}
    <div id="chat-messages"
         class="flex-1 overflow-y-auto px-1 py-2 space-y-3 min-h-0">

        {{-- Séparateur date --}}
        <div class="flex items-center gap-3 my-4">
            <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
            <span class="text-xs font-semibold text-slate-400 px-3 py-1 rounded-full bg-slate-50 dark:bg-white/5">
                Conversation démarrée
            </span>
            <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
        </div>

        {{-- Messages initiaux --}}
        @foreach($messages as $msg)
        @php $isMine = $msg->sender_id === Auth::id(); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2 items-end msg-pop">
            @if(!$isMine)
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
                {{ strtoupper(substr($passenger->first_name ?? '?', 0, 1)) }}
            </div>
            @endif
            <div class="max-w-[75%]">
                <div class="px-4 py-2.5 text-sm font-medium
                            {{ $isMine
                                ? 'bg-primary text-background-dark msg-out shadow-md shadow-primary/20'
                                : 'bg-white dark:bg-card-dark text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-white/10 msg-in shadow-sm' }}">
                    {{ $msg->content }}
                </div>
                <p class="text-xs text-slate-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                    {{ $msg->created_at->format('H:i') }}
                    @if($isMine)
                        <span class="material-symbols-outlined text-xs {{ $msg->read_at ? 'text-primary' : 'text-slate-300' }}">
                            {{ $msg->read_at ? 'done_all' : 'done' }}
                        </span>
                    @endif
                </p>
            </div>
            @if($isMine)
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary/80 to-emerald-500 flex items-center justify-center flex-shrink-0 font-bold text-background-dark text-xs">
                {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '?', 0, 1)) }}
            </div>
            @endif
        </div>
        @endforeach

        {{-- Conteneur des nouveaux messages injectés via JS --}}
        <div id="new-messages"></div>

        {{-- "En train d'écrire..." (masqué par défaut) --}}
        <div id="typing-indicator" class="hidden justify-start gap-2 items-end">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
                {{ strtoupper(substr($passenger->first_name ?? '?', 0, 1)) }}
            </div>
            <div class="px-4 py-3 bg-white dark:bg-card-dark border border-slate-100 dark:border-white/10 rounded-lg msg-in shadow-sm">
                <div class="flex items-center gap-1">
                    <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span class="typing-dot w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                </div>
            </div>
        </div>

        {{-- Ancre scroll --}}
        <div id="scroll-anchor"></div>
    </div>

    {{-- ── Zone de saisie ── --}}
    <div class="flex-shrink-0 mt-2">
        <div class="flex items-end gap-3 p-3
                    bg-white dark:bg-card-dark
                    border border-slate-100 dark:border-primary/10
                    rounded-2xl shadow-sm">

            <textarea id="msg-input"
                      rows="1"
                      placeholder="Votre message…"
                      class="flex-1 resize-none bg-transparent text-sm font-medium outline-none
                             text-slate-800 dark:text-slate-200 placeholder-slate-400
                             max-h-32 py-2 px-1 border-none focus:ring-0
                             transition-all duration-200"
                      onInput="autoResize(this); handleTyping();"
                      onKeydown="handleKeydown(event)"></textarea>

            <button id="send-btn"
                    onclick="sendMessage()"
                    class="w-10 h-10 rounded-xl bg-primary hover:bg-primary/90 text-background-dark
                           flex items-center justify-center flex-shrink-0
                           transition-all shadow-md shadow-primary/20 disabled:opacity-40 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-xl">send</span>
            </button>
        </div>
        <p class="hidden sm:block text-center text-xs text-slate-400 mt-2">
            <span class="material-symbols-outlined text-xs align-middle">lock</span>
            Conversation liée à la course · {{ $trip->departure_city }} → {{ $trip->arrival_city }}
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Config ──────────────────────────────────────────────────────
const TRIP_ID      = {{ $trip->id }};
const AUTH_ID      = {{ Auth::id() }};
const PASSENGER_ID = {{ $passenger->id }};
const POLL_INTERVAL = 5000; // ms
let lastMessageId  = {{ $messages->last()?->id ?? 0 }};
let typingTimeout  = null;
let pollTimeout    = null;
let isSending      = false;

// ── DOM refs ─────────────────────────────────────────────────────
const chatBox      = document.getElementById('chat-messages');
const newMsgsBox   = document.getElementById('new-messages');
const input        = document.getElementById('msg-input');
const sendBtn      = document.getElementById('send-btn');
const typingEl     = document.getElementById('typing-indicator');
const pollDot      = document.getElementById('poll-indicator');
const anchor       = document.getElementById('scroll-anchor');

// ── Init ─────────────────────────────────────────────────────────
scrollToBottom();
startPolling();

// ── Polling ──────────────────────────────────────────────────────
function startPolling() {
    poll();
}

async function poll() {
    try {
        const res  = await fetch(`/chat/${TRIP_ID}/messages?after=${lastMessageId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('Poll failed');
        const data = await res.json();

        // Clignotement du dot pour signaler la synchro
        pollDot.classList.add('opacity-0');
        setTimeout(() => pollDot.classList.remove('opacity-0'), 300);

        // Nouveaux messages
        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(msg => appendMessage(msg));
            lastMessageId = data.messages[data.messages.length - 1].id;
            scrollToBottom();
        }

        // Indicateur "en train d'écrire"
        if (data.is_typing) {
            typingEl.classList.remove('hidden');
            typingEl.classList.add('flex');
            scrollToBottom();
        } else {
            typingEl.classList.add('hidden');
            typingEl.classList.remove('flex');
        }

        // Marquer messages lus
        markRead();

    } catch (e) {
        console.warn('Polling error:', e);
    }

    // Re-schedule
    pollTimeout = setTimeout(poll, POLL_INTERVAL);
}

// ── Envoyer ───────────────────────────────────────────────────────
async function sendMessage() {
    const content = input.value.trim();
    if (!content || isSending) return;

    isSending = true;
    sendBtn.disabled = true;

    // Affichage optimiste immédiat
    const tempId = 'temp-' + Date.now();
    appendMessage({
        id:         tempId,
        sender_id:  AUTH_ID,
        content:    content,
        created_at: new Date().toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' }),
        read_at:    null,
        temp:       true,
    });
    input.value = '';
    autoResize(input);
    scrollToBottom();

    try {
        const res = await fetch(`/chat/${TRIP_ID}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ content }),
        });

        if (!res.ok) {
            const errText = await res.text();
            console.error(`Send failed [${res.status}]:`, errText);
            throw new Error(`HTTP ${res.status}`);
        }
        const data = await res.json();

        // Remplacer message temporaire par le vrai
        const tempEl = document.getElementById(tempId);
        if (tempEl) tempEl.remove();
        appendMessage(data.message);
        lastMessageId = Math.max(lastMessageId, data.message.id);
        scrollToBottom();

    } catch (e) {
        // Marquer le message en erreur
        const tempEl = document.getElementById(tempId);
        if (tempEl) {
            tempEl.querySelector('.msg-content').classList.add('opacity-50');
            tempEl.insertAdjacentHTML('beforeend', '<p class="text-xs text-red-400 mt-0.5 text-right">Échec d\'envoi</p>');
        }
        console.error('Send error:', e);
    }

    isSending = false;
    sendBtn.disabled = false;
    input.focus();
}

// ── Créer une bulle message ────────────────────────────────────────
function appendMessage(msg) {
    const isMine = msg.sender_id === AUTH_ID;
    const time   = typeof msg.created_at === 'string' && msg.created_at.includes('T')
        ? new Date(msg.created_at).toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' })
        : msg.created_at;

    const avatarMine = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary/80 to-emerald-500
                    flex items-center justify-center flex-shrink-0 font-bold text-background-dark text-xs">
            {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '?', 0, 1)) }}
        </div>`;
    const avatarOther = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-violet-400 to-purple-600
                    flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
            {{ strtoupper(substr($passenger->first_name ?? '?', 0, 1)) }}
        </div>`;

    const bubbleClass = isMine
        ? 'bg-primary text-background-dark msg-out shadow-md shadow-primary/20'
        : 'bg-white dark:bg-card-dark text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-white/10 msg-in shadow-sm';

    const checkIcon = msg.read_at
        ? '<span class="material-symbols-outlined text-xs text-primary">done_all</span>'
        : '<span class="material-symbols-outlined text-xs text-slate-300">done</span>';

    const el = document.createElement('div');
    el.id = msg.temp ? msg.id : `msg-${msg.id}`;
    el.className = `flex ${isMine ? 'justify-end' : 'justify-start'} gap-2 items-end msg-pop`;
    el.innerHTML = `
        ${!isMine ? avatarOther : ''}
        <div class="max-w-[75%]">
            <div class="px-4 py-2.5 text-sm font-medium msg-content ${bubbleClass}">
                ${escapeHtml(msg.content)}
            </div>
            <p class="text-xs text-slate-400 mt-1 ${isMine ? 'text-right' : 'text-left'}">
                ${time}
                ${isMine ? checkIcon : ''}
            </p>
        </div>
        ${isMine ? avatarMine : ''}
    `;

    // Insérer avant l'ancre
    newMsgsBox.appendChild(el);
}

// ── Marquer comme lu ─────────────────────────────────────────────
async function markRead() {
    try {
        await fetch(`/chat/${TRIP_ID}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
    } catch(e) {}
}

// ── Typing indicator (envoi au serveur) ──────────────────────────
async function handleTyping() {
    clearTimeout(typingTimeout);
    try {
        await fetch(`/chat/${TRIP_ID}/typing`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
    } catch(e) {}
    typingTimeout = setTimeout(async () => {
        try {
            await fetch(`/chat/${TRIP_ID}/typing/stop`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
        } catch(e) {}
    }, 3000);
}

// ── Helpers ──────────────────────────────────────────────────────
function scrollToBottom() {
    anchor.scrollIntoView({ behavior: 'smooth' });
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 128) + 'px';
}

function handleKeydown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// ── Nettoyage avant quitter la page ──────────────────────────────
window.addEventListener('beforeunload', () => {
    clearTimeout(pollTimeout);
    clearTimeout(typingTimeout);
    // Arrêter le typing
    navigator.sendBeacon(`/chat/${TRIP_ID}/typing/stop`,
        new Blob([JSON.stringify({ _token: document.querySelector('meta[name="csrf-token"]').content })],
        { type: 'application/json' })
    );
});
</script>
@endpush
