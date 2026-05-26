<?php
namespace App\Models;
use CodeIgniter\Model;

class AmbulanceModel extends Model
{
    protected $table         = 'ambulances';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','immatriculation','modele','type_vehicule','statut','chauffeur_nom','chauffeur_tel','date_revision','notes','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }
}
