<?php
namespace App\Models;
use CodeIgniter\Model;

class RessourceHumaineModel extends Model
{
    protected $table         = 'ressources_humaines';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','nom','prenom','poste','departement','date_embauche','salaire','telephone','email','contrat','notes','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }
}
