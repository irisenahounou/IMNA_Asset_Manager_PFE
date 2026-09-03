<?php

namespace App\Notifications;

use App\Models\Composant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * US-06 : "Je veux être alerté par une notification système dès qu'une
 * catégorie de composants atteint son seuil de sécurité dans le stock."
 *
 * Notification "base de données" uniquement (pas d'email) : visible
 * immédiatement dès la connexion du DSI, sans dépendre d'une config SMTP.
 */

class SeuilStockAtteint extends Notification
{
    use Queueable;

    public function __construct(protected Composant $composant)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'id_composant' => $this->composant->id_composant,
            'nom_composant' => $this->composant->nom_composant,
            'quantite_stock' => $this->composant->quantite_stock,
            'seuil_alerte' => $this->composant->seuil_alerte,
            'message' => "Stock faible : « {$this->composant->nom_composant} » ({$this->composant->quantite_stock} restant(s), seuil {$this->composant->seuil_alerte}).",
        ];
    }
}