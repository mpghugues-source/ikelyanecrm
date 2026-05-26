<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table         = 'factures';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id','patient_id','medecin_id','rdv_id',
        'numero','date_facture','sous_total','remise','total',
        'montant_paye','statut','mode_paiement','notes',
    ];

    public function getWithDetails(int $tenantId): array
    {
        return $this->select('factures.*, CONCAT(p.nom, " ", p.prenom) as patient_nom, CONCAT(u.nom, " ", u.prenom) as medecin_nom')
            ->join('patients p', 'p.id = factures.patient_id')
            ->join('medecins m', 'm.id = factures.medecin_id', 'left')
            ->join('users u', 'u.id = m.user_id', 'left')
            ->where('factures.tenant_id', $tenantId)
            ->orderBy('factures.date_facture', 'DESC')
            ->findAll();
    }

    public function getOneWithDetails(int $id): ?array
    {
        return $this->select('factures.*, CONCAT(p.nom, " ", p.prenom) as patient_nom, p.telephone as patient_tel, p.adresse as patient_adresse, CONCAT(u.nom, " ", u.prenom) as medecin_nom')
            ->join('patients p', 'p.id = factures.patient_id')
            ->join('medecins m', 'm.id = factures.medecin_id', 'left')
            ->join('users u', 'u.id = m.user_id', 'left')
            ->where('factures.id', $id)
            ->first();
    }

    public function getByPatient(int $patientId): array
    {
        return $this->select('factures.*')
            ->where('factures.patient_id', $patientId)
            ->orderBy('factures.date_facture', 'DESC')
            ->findAll();
    }

    public function generateNumero(int $tenantId): string
    {
        $year  = date('Y');
        $count = $this->where('tenant_id', $tenantId)
                      ->like('numero', "FAC-{$year}-", 'after')
                      ->countAllResults();
        return sprintf('FAC-%s-%04d', $year, $count + 1);
    }

    public function getMonthlyRevenue(int $tenantId): float
    {
        $result = $this->selectSum('montant_paye')
                       ->where('tenant_id', $tenantId)
                       ->where('MONTH(date_facture)', date('n'))
                       ->where('YEAR(date_facture)', date('Y'))
                       ->first();
        return (float) ($result['montant_paye'] ?? 0);
    }

    public function getRevenueChart(int $tenantId): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('n', strtotime("-{$i} months"));
            $year  = date('Y', strtotime("-{$i} months"));
            $label = date('M Y', strtotime("-{$i} months"));
            $result = $this->selectSum('montant_paye')
                           ->where('tenant_id', $tenantId)
                           ->where('MONTH(date_facture)', $month)
                           ->where('YEAR(date_facture)', $year)
                           ->first();
            $data[] = [
                'label'  => $label,
                'total'  => (float) ($result['montant_paye'] ?? 0),
            ];
        }
        return $data;
    }
}
