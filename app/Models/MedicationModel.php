<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicationModel extends Model
{
    protected $table         = 'medicaments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['nom','dci','forme','dosage','fabricant','actif'];

    public function search(string $q): array
    {
        return $this->where('actif', 1)
                    ->groupStart()
                    ->like('nom', $q)
                    ->orLike('dci', $q)
                    ->groupEnd()
                    ->orderBy('nom', 'ASC')
                    ->findAll(20);
    }
}
