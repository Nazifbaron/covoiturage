<x-mail::message>
# Félicitations, {{ $driver->first_name }} ! 🎉

Votre véhicule **{{ $vehicle->brand }} {{ $vehicle->model }}** ({{ $vehicle->plate }}) a été **approuvé** par notre équipe.

Vous pouvez désormais publier des trajets et commencer à transporter des passagers.

---

| | |
|---|---|
| **Type** | {{ ucfirst($vehicle->type) }} |
| **Marque** | {{ $vehicle->brand }} |
| **Modèle** | {{ $vehicle->model }} |
| **Immatriculation** | {{ $vehicle->plate }} |
| **Approuvé le** | {{ $vehicle->approved_at?->format('d/m/Y à H:i') }} |

---

<x-mail::button :url="$dashboardUrl" color="green">
Accéder à mon tableau de bord
</x-mail::button>

Merci de nous faire confiance,
**L'équipe {{ config('app.name') }}**
</x-mail::message>
