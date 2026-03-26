<x-mail::message>
# Nouveau véhicule à valider

Un conducteur vient de soumettre un véhicule en attente de validation.

---

## Conducteur

| | |
|---|---|
| **Nom** | {{ $driver->first_name }} {{ $driver->last_name }} |
| **Email** | {{ $driver->email }} |

## Véhicule

| | |
|---|---|
| **Type** | {{ ucfirst($vehicle->type) }} |
| **Marque** | {{ $vehicle->brand }} |
| **Modèle** | {{ $vehicle->model }} |
| **Couleur** | {{ $vehicle->color }} |
| **Immatriculation** | {{ $vehicle->plate }} |
| **Soumis le** | {{ $vehicle->created_at->format('d/m/Y à H:i') }} |

---

<x-mail::button :url="$adminUrl" color="green">
Accéder à la liste des véhicules
</x-mail::button>

Merci,
**L'équipe {{ config('app.name') }}**
</x-mail::message>
