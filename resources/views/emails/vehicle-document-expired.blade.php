<x-mail::message>
# Votre compte a été suspendu

Bonjour **{{ $driver->first_name }} {{ $driver->last_name }}**,

Nous vous informons que votre compte a été **suspendu automatiquement** car les documents de votre véhicule **{{ $vehicle->brand }} {{ $vehicle->model }}** ({{ strtoupper($vehicle->plate) }}) ont expiré.

---

<x-mail::table>
| Document | Date d'expiration | Statut |
|:---------|:-----------------|:-------|
@foreach($docs as $doc)
| **{{ $doc['label'] }}** | {{ $doc['expires_at']->format('d/m/Y') }} | Expiré depuis {{ abs($doc['days_expired']) }} jour(s) |
@endforeach
</x-mail::table>

---

## Que faire ?

Pour réactiver votre compte, vous devez :

1. Renouveler les documents expirés auprès des autorités compétentes
2. Mettre à jour les documents dans votre espace profil
3. Notre équipe vérifiera et réactivera votre compte dans les plus brefs délais

<x-mail::button :url="$profileUrl" color="green">
Mettre à jour mes documents
</x-mail::button>

Pour toute question, contactez notre support.

Cordialement,
**L'équipe {{ config('app.name') }}**
</x-mail::message>
