<?php

namespace App\Models;

use CodeIgniter\Model;

class PrescriptionItemModel extends Model
{
    protected $table         = 'ordonnance_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'ordonnance_id','medicament_id','medicament_libre',
        'posologie','frequence','duree','quantite','instructions',
    ];

    public function getByPrescription(int $ordonnanceId): array
    {
        return $this->select('ordonnance_items.*, medicaments.nom as medicament_nom, medicaments.forme')
                    ->join('medicaments', 'medicaments.id = ordonnance_items.medicament_id', 'left')
                    ->where('ordonnance_id', $ordonnanceId)
                    ->findAll();
    }
}
