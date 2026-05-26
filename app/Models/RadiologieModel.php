<?php
namespace App\Models;
use CodeIgniter\Model;

class RadiologieModel extends Model
{
    protected $table         = 'radiologie';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','patient_id','medecin_id','numero','type_examen','region','date_demande','date_examen','statut','urgence','description','compte_rendu','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }

    public static function generateNumero(): string
    {
        return 'RAD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }
}
