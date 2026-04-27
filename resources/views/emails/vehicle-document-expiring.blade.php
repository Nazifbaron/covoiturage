<x-mail::message>
# Vos documents de véhicule expirent bientôt

Bonjour **{{ $driver->first_name }} {{ $driver->last_name }}**,

Nous vous informons que les pièces de votre véhicule **{{ $vehicle->brand }} {{ $vehicle->model }}** ({{ strtoupper($vehicle->plate) }}) arrivent à expiration.

---

<x-mail::table>
| Document | Date d'expiration | Jours restants |
|:---------|:-----------------|:---------------|
@foreach($docs as $doc)
| **{{ $doc['label'] }}** | {{ $doc['expires_at']->format('d/m/Y') }} | {{ $doc['days_remaining'] == 0 ? 'Expire aujourd\'hui !' : $doc['days_remaining'] . ' jour(s)' }} |
@endforeach
</x-mail::table>

---

@if(collect($docs)->min('days_remaining') <= 7)
> **Attention** : Un ou plusieurs documents expirent dans moins de 7 jours. Veuillez les renouveler immédiatement pour continuer à utiliser votre véhicule sur la plateforme.
@endif

Mettez à jour vos documents dès que possible dans votre espace profil.

<x-mail::button :url="$profileUrl" color="green">
Mettre à jour mes documents
</x-mail::button>

Merci de votre confiance,
**L'équipe {{ config('app.name') }}**
</x-mail::message>
