<?php
namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $className = class_basename($model);
            AuditService::enregistrer("Création - {$className}", "Création d'un enregistrement dans la table {$className} (ID: {$model->getKey()})");
        });

        static::updated(function ($model) {
            $className = class_basename($model);
            AuditService::enregistrer("Modification - {$className}", "Mise à jour de l'enregistrement {$className} (ID: {$model->getKey()})");
        });

        static::deleted(function ($model) {
            $className = class_basename($model);
            AuditService::enregistrer("Suppression - {$className}", "Suppression de l'enregistrement {$className} (ID: {$model->getKey()})");
        });
    }
}