<?php
namespace App\Models;
use CodeIgniter\Model;

class LaboratoireModel extends Model
{
    protected $table         = 'laboratoire';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['tenant_id','patient_id','medecin_id','numero','type_analyse','date_demande','date_resultat','statut','urgence','notes','resultat','actif'];

    public function forTenant(int $tenantId)
    {
        return $this->where('tenant_id', $tenantId)->where('actif', 1);
    }

    public static function generateNumero(int $tenantId): string
    {
        return 'LAB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }
}
