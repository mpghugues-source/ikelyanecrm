<?php
namespace App\Models;
use CodeIgniter\Model;

class NaissanceDecesModel extends Model
{
    protected $table         = 'naissances_deces';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','type_evenement','nom','prenom','date_evenement','heure_evenement','lieu','medecin_id','numero_certificat','notes','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }
}
