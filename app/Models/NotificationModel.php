<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','user_id','type','titre','message','lien','icone','couleur','lu',
    ];

    /** Notifs non lues d'un utilisateur/tenant */
    public function getUnread(int $tenantId, ?int $userId = null): array
    {
        $q = $this->where('tenant_id', $tenantId)->where('lu', 0);
        if ($userId) {
            $q->groupStart()->where('user_id', $userId)->orWhere('user_id', null)->groupEnd();
        }
        return $q->orderBy('created_at', 'DESC')->findAll(10);
    }

    public function countUnread(int $tenantId, ?int $userId = null): int
    {
        $q = $this->where('tenant_id', $tenantId)->where('lu', 0);
        if ($userId) {
            $q->groupStart()->where('user_id', $userId)->orWhere('user_id', null)->groupEnd();
        }
        return $q->countAllResults();
    }

    public function markAllRead(int $tenantId, ?int $userId = null): void
    {
        $q = $this->where('tenant_id', $tenantId)->where('lu', 0);
        if ($userId) {
            $q->groupStart()->where('user_id', $userId)->orWhere('user_id', null)->groupEnd();
        }
        $q->set('lu', 1)->update();
    }

    /** Créer une notification rapide */
    public static function notify(int $tenantId, string $type, string $titre, string $message, string $lien = '', string $icone = 'bi-bell', string $couleur = 'primary', ?int $userId = null): void
    {
        $model = new self();
        $model->insert([
            'tenant_id' => $tenantId,
            'user_id'   => $userId,
            'type'      => $type,
            'titre'     => $titre,
            'message'   => $message,
            'lien'      => $lien,
            'icone'     => $icone,
            'couleur'   => $couleur,
        ]);
    }
}
