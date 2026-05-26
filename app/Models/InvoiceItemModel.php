<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItemModel extends Model
{
    protected $table         = 'facture_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'facture_id','service_id','description','quantite','prix_unitaire','total',
    ];

    public function getByInvoice(int $factureId): array
    {
        return $this->where('facture_id', $factureId)->findAll();
    }
}
