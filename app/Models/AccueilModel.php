<?php
namespace App\Models;
use CodeIgniter\Model;

class AccueilModel extends Model
{
    protected $table         = 'accueil';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','patient_id','nom_visiteur','type_operation','date_heure','motif','chambre','statut','notes','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }
}
