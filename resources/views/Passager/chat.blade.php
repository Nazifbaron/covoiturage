@extends('layouts.app')

@push('styles')
<style>
    .msg-in  { border-radius: 4px 18px 18px 18px; }
    .msg-out { border-radius: 18px 4px 18px 18px; }

    @keyframes msgPop {
        from { opacity: 0; transform: translateY(8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .msg-pop { animation: msgPop 0.2s ease-out forwards; }

    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.7); opacity: 0.4; }
        40%            { transform: scale(1);   opacity: 1;   }
    }
    .typing-dot:nth-child(1) { animation: typingDot 1.2s infinite 0s; }
    .typing-dot:nth-child(2) { animation: typingDot 1.2s infinite .2s; }
    .typing-dot:nth-child(3) { animation: typingDot 1.2s infinite .4s; }

    #chat-messages { scroll-behavior: smooth; }
    #chat-messages::-webkit-scrollbar { width: 4px; }
    #chat-messages::-webkit-scrollbar-track { background: transparent; }
    #chat-messages::-webkit-scrollbar-thumb { background: #13ec4933; border-radius: 99px; }
    #msg-input:focus { box-shadow: 0 0 0 3px #13ec4920; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto h-[calc(100vh-7rem)] flex flex-col">

    {{-- ── Header ── --}}
    <div class="flex items-center gap-3 px-4 py-3
                bg-white dark:bg-card-dark border border-slate-100 dark:border-primary/10
                rounded-2xl shadow-sm mb-3 flex-shrink-0">

        {{-- ← Retour vers mes réservations passager --}}
        <a href="{{ route('passenger.my-requests') }}"
           class="w-9 h-9 rounded-xl border border-slate-200 dark:border-white/10
                  flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/10
                  transition-colors flex-shrink-0">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-xl">arrow_back</span>
        </a>

        {{-- Avatar du conducteur (interlocuteur du passager) --}}
        <div class="relative flex-shrink-0">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600
                        flex items-center justify-center font-black text-white text-sm
                        shadow-md shadow-blue-200 dark:shadow-blue-900/30">
                {{ strtoupper(substr($passenger->first_name ?? $passenger->name ?? '?', 0, 1)) }}
            </div>
            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400
                         border-2 border-white dark:border-card-dark"></span>
        </div>

        <div class="flex-1 min-w-0">
            <p class="font-black text-slate-900 dark:text-white text-sm">
                {{ $passenger->first_name ?? '' }} {{ $passenger->last_name ?? $passenger->name ?? '' }}
            </p>
            {{-- Label rôle : conducteur --}}
            <p class="text-xs font-semibold text-slate-400">
                <span class="material-symbols-outlined" style="font-size:12px;vertical-align:middle">directions_car</span>
                Conducteur
            </p>
        </div>

        {{-- Trajet --}}
        <div class="flex-shrink-0 hidden sm:flex items-center gap-2 px-3 py-1.5
                    rounded-xl bg-slate-50 dark:bg-white/5
                    border border-slate-100 dark:border-white/10
                    text-xs font-bold text-slate-600 dark:text-slate-400">
            <span class="material-symbols-outlined text-primary" style="font-size:16px">route</span>
            {{ $trip->departure_city }} → {{ $trip->arrival_city }}
        </div>

        <div class="w-2 h-2 rounded-full bg-primary flex-shrink-0" id="poll-indicator" title="Synchronisation active"></div>
    </div>

    {{-- ── Zone messages ── --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto px-1 py-2 space-y-3 min-h-0">

        <div class="flex items-center gap-3 my-4">
            <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
            <span class="text-xs font-semibold text-slate-400 px-3 py-1 rounded-full bg-slate-50 dark:bg-white/5">
                Conversation démarrée
            </span>
            <div class="flex-1 h-px bg-slate-100 dark:bg-white/10"></div>
        </div>

        @foreach($messages as $msg)
        @php $isMine = (int)$msg->sender_id === (int)Auth::id(); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2 items-end msg-pop">
            @if(!$isMine)
            {{-- Avatar conducteur = bleu --}}
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600
                        flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
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
            {{-- Avatar passager = vert --}}
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary/80 to-emerald-500
                        flex items-center justify-center flex-shrink-0 font-bold text-background-dark text-xs">
                {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '?', 0, 1)) }}
            </div>
            @endif
        </div>
        @endforeach

        <div id="new-messages"></div>

        {{-- Indicateur "en train d'écrire" --}}
        <div id="typing-indicator" class="hidden flex justify-start gap-2 items-end">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600
                        flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">
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

        <div id="scroll-anchor"></div>
    </div>

    {{-- ── Saisie ── --}}
    <div class="flex-shrink-0 mt-2">
        <div class="flex items-end gap-3 p-3
                    bg-white dark:bg-card-dark
                    border border-slate-100 dark:border-primary/10 rounded-2xl shadow-sm">
            <textarea id="msg-input" rows="1"
                      placeholder="Votre message…"
                      class="flex-1 resize-none bg-transparent text-sm font-medium outline-none
                             text-slate-800 dark:text-slate-200 placeholder-slate-400
                             max-h-32 py-2 px-1 border-none focus:ring-0 transition-all duration-200"
                      onInput="autoResize(this); handleTyping();"
                      onKeydown="handleKeydown(event)"></textarea>
            <button id="send-btn" onclick="sendMessage()"
                    class="w-10 h-10 rounded-xl bg-primary hover:bg-primary/90 text-background-dark
                           flex items-center justify-center flex-shrink-0
                           transition-all shadow-md shadow-primary/20
                           disabled:opacity-40 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-xl">send</span>
            </button>
        </div>
        <p class="text-center text-xs text-slate-400 mt-2">
            <span class="material-symbols-outlined text-xs align-middle">lock</span>
            Conversation liée à la course · {{ $trip->departure_city }} → {{ $trip->arrival_city }}
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Config ────────────────────────────────────────────────────────
const TRIP_ID    = {{ $trip->id }};
const AUTH_ID    = {{ Auth::id() }};
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

const URL_POLL        = "{{ route('chat.poll',        $trip->id) }}";
const URL_SEND        = "{{ route('chat.send',        $trip->id) }}";
const URL_READ        = "{{ route('chat.read',        $trip->id) }}";
const URL_TYPING      = "{{ route('chat.typing',      $trip->id) }}";
const URL_TYPING_STOP = "{{ route('chat.typing.stop', $trip->id) }}";

// Initiales pour les bulles JS (conducteur = interlocuteur du passager)
const INTERLOCUTOR_INITIAL = "{{ strtoupper(substr($passenger->first_name ?? $passenger->name ?? '?', 0, 1)) }}";
const MY_INITIAL           = "{{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? '?', 0, 1)) }}";

let lastMessageId = {{ $messages->last()?->id ?? 0 }};
let typingTimeout = null;
let pollTimeout   = null;
let isSending     = false;

const newMsgsBox = document.getElementById('new-messages');
const input      = document.getElementById('msg-input');
const sendBtn    = document.getElementById('send-btn');
const typingEl   = document.getElementById('typing-indicator');
const pollDot    = document.getElementById('poll-indicator');
const anchor     = document.getElementById('scroll-anchor');

scrollToBottom();
poll();

async function poll() {
    try {
        const res = await fetch(`${URL_POLL}?after=${lastMessageId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error(`Poll ${res.status}`);
        const data = await res.json();

        pollDot.style.opacity = '0';
        setTimeout(() => pollDot.style.opacity = '1', 300);

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(m => appendMessage(m));
            lastMessageId = data.messages[data.messages.length - 1].id;
            scrollToBottom();
            markRead();
        }

        typingEl.classList.toggle('hidden', !data.is_typing);
        typingEl.classList.toggle('flex',    data.is_typing);
        if (data.is_typing) scrollToBottom();

    } catch(e) {
        console.warn('Poll error:', e);
    }
    pollTimeout = setTimeout(poll, 5000);
}

async function sendMessage() {
    const content = input.value.trim();
    if (!content || isSending) return;

    isSending = true;
    sendBtn.disabled = true;

    const tempId = 'temp-' + Date.now();
    appendMessage({ id: tempId, sender_id: AUTH_ID, content, created_at: now(), read_at: null });
    input.value = '';
    autoResize(input);
    scrollToBottom();

    try {
        const res = await fetch(URL_SEND, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ content }),
        });

        if (!res.ok) {
            const txt = await res.text();
            console.error(`Send ${res.status}:`, txt);
            throw new Error(`HTTP ${res.status}`);
        }

        const data = await res.json();
        document.getElementById(tempId)?.remove();
        appendMessage(data.message);
        lastMessageId = Math.max(lastMessageId, data.message.id);
        scrollToBottom();

    } catch(e) {
        console.error('Send error:', e);
        const el = document.getElementById(tempId);
        if (el) {
            el.querySelector('.msg-content')?.classList.add('opacity-50');
            const p = document.createElement('p');
            p.className = 'text-xs text-red-400 mt-0.5 text-right';
            p.textContent = 'Échec — réessayez';
            el.appendChild(p);
        }
    }

    isSending = false;
    sendBtn.disabled = false;
    input.focus();
}

function appendMessage(msg) {
    const isMine = parseInt(msg.sender_id) === parseInt(AUTH_ID);

    const time = msg.created_at && msg.created_at.includes('T')
        ? new Date(msg.created_at).toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' })
        : (msg.created_at ?? '');

    // Conducteur = bleu, passager (moi) = vert
    const avatarMine  = `<div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary/80 to-emerald-500
        flex items-center justify-center flex-shrink-0 font-bold text-background-dark text-xs">${MY_INITIAL}</div>`;
    const avatarOther = `<div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600
        flex items-center justify-center flex-shrink-0 font-bold text-white text-xs">${INTERLOCUTOR_INITIAL}</div>`;

    const bubbleClass = isMine
        ? 'bg-primary text-background-dark msg-out shadow-md shadow-primary/20'
        : 'bg-white dark:bg-card-dark text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-white/10 msg-in shadow-sm';

    const check = msg.read_at
        ? '<span class="material-symbols-outlined text-xs text-primary">done_all</span>'
        : '<span class="material-symbols-outlined text-xs text-slate-300">done</span>';

    const el = document.createElement('div');
    el.id = String(msg.id).startsWith('temp-') ? msg.id : `msg-${msg.id}`;
    el.className = `flex ${isMine ? 'justify-end' : 'justify-start'} gap-2 items-end msg-pop`;
    el.innerHTML = `
        ${!isMine ? avatarOther : ''}
        <div class="max-w-[75%]">
            <div class="px-4 py-2.5 text-sm font-medium msg-content ${bubbleClass}">${escapeHtml(msg.content)}</div>
            <p class="text-xs text-slate-400 mt-1 ${isMine ? 'text-right' : 'text-left'}">
                ${time}${isMine ? ' ' + check : ''}
            </p>
        </div>
        ${isMine ? avatarMine : ''}
    `;
    newMsgsBox.appendChild(el);
}

async function markRead() {
    try { await fetch(URL_READ, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' } }); } catch(e) {}
}

async function handleTyping() {
    clearTimeout(typingTimeout);
    try { await fetch(URL_TYPING, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' } }); } catch(e) {}
    typingTimeout = setTimeout(async () => {
        try { await fetch(URL_TYPING_STOP, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' } }); } catch(e) {}
    }, 3000);
}

function scrollToBottom() { anchor.scrollIntoView({ behavior: 'smooth' }); }
function now() { return new Date().toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' }); }
function autoResize(el) { el.style.height = 'auto'; el.style.height = Math.min(el.scrollHeight, 128) + 'px'; }
function handleKeydown(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } }
function escapeHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

window.addEventListener('beforeunload', () => {
    clearTimeout(pollTimeout);
    clearTimeout(typingTimeout);
    navigator.sendBeacon(URL_TYPING_STOP, new Blob([JSON.stringify({ _token: CSRF_TOKEN })], { type: 'application/json' }));
});
</script>
@endpush
