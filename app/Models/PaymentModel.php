<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table         = 'payments';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id', 'plan_slug', 'billing_cycle', 'amount', 'currency',
        'status', 'tx_ref', 'flw_transaction_id', 'flw_payment_type',
        'customer_email', 'customer_name',
    ];

    public function findByTxRef(string $txRef): ?array
    {
        return $this->where('tx_ref', $txRef)->first();
    }
}
