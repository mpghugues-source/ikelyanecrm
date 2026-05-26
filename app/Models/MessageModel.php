<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table      = 'messages';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tenant_id', 'from_user_id', 'to_user_id', 'rdv_id',
        'sujet', 'message', 'is_read', 'created_at',
    ];
    protected $useTimestamps = false;

    /** Messages reçus par un utilisateur */
    public function getInbox(int $tenantId, int $userId): array
    {
        return $this->db->table('messages m')
            ->select('m.*, CONCAT(uf.prenom," ",uf.nom) AS from_nom, uf.role AS from_role')
            ->join('users uf', 'uf.id = m.from_user_id', 'left')
            ->where('m.tenant_id', $tenantId)
            ->where('m.to_user_id', $userId)
            ->orderBy('m.created_at', 'DESC')
            ->get()->getResultArray();
    }

    /** Messages envoyés par un utilisateur */
    public function getSent(int $tenantId, int $userId): array
    {
        return $this->db->table('messages m')
            ->select('m.*, CONCAT(ut.prenom," ",ut.nom) AS to_nom, ut.role AS to_role')
            ->join('users ut', 'ut.id = m.to_user_id', 'left')
            ->where('m.tenant_id', $tenantId)
            ->where('m.from_user_id', $userId)
            ->orderBy('m.created_at', 'DESC')
            ->get()->getResultArray();
    }

    /** Un message avec détails expéditeur et destinataire */
    public function getWithDetails(int $id): ?array
    {
        return $this->db->table('messages m')
            ->select('m.*, CONCAT(uf.prenom," ",uf.nom) AS from_nom, uf.role AS from_role,
                      CONCAT(ut.prenom," ",ut.nom) AS to_nom, ut.role AS to_role')
            ->join('users uf', 'uf.id = m.from_user_id', 'left')
            ->join('users ut', 'ut.id = m.to_user_id', 'left')
            ->where('m.id', $id)
            ->get()->getRowArray() ?: null;
    }

    /** Nombre de messages non lus */
    public function countUnread(int $tenantId, int $userId): int
    {
        return $this->where('tenant_id', $tenantId)
            ->where('to_user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    /** Marquer comme lu */
    public function markRead(int $id): void
    {
        $this->update($id, ['is_read' => 1]);
    }
}
