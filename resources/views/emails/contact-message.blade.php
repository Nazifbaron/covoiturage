<x-mail::message>
# Nouveau message de contact

Vous avez reçu un message via le formulaire de contact de **Covoiturage**.

---

**Nom :** {{ $senderName }}
**Email :** {{ $senderEmail }}
**Sujet :** {{ $subject }}

---

**Message :**

{{ $body }}

---

<x-mail::button :url="'mailto:' . $senderEmail">
Répondre à {{ $senderName }}
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
