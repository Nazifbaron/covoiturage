<x-mail::message>
# Bonjour {{ $driver->first_name }},

Après vérification, votre véhicule **{{ $vehicle->brand }} {{ $vehicle->model }}** ({{ $vehicle->plate }}) n'a malheureusement **pas pu être validé**.

---

**Motif du rejet :**

> {{ $vehicle->rejection_reason }}

---

Vous pouvez corriger les informations ou documents concernés et soumettre à nouveau votre dossier.

<x-mail::button :url="$setupUrl" color="red">
Resoumettre mon véhicule
</x-mail::button>

Si vous pensez qu'il s'agit d'une erreur, n'hésitez pas à nous contacter.

Cordialement,
**L'équipe {{ config('app.name') }}**
</x-mail::message>
