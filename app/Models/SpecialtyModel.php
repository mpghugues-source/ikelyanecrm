<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecialtyModel extends Model
{
    protected $table         = 'specialites';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['nom'];

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }
}
