<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\PatientModel;
use App\Models\DoctorModel;
use App\Models\ServiceModel;
use App\Models\TenantModel;

class InvoiceController extends BaseController
{
    private InvoiceModel     $invoiceModel;
    private InvoiceItemModel $itemModel;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->itemModel    = new InvoiceItemModel();
    }

    public function index(): string
    {
        return view('invoices/index', [
            'title'    => 'Facturation',
            'factures' => $this->invoiceModel->getWithDetails($this->getTenantId()),
        ]);
    }

    public function create(int $patientId = 0): string
    {
        $tenantId = $this->getTenantId();
        return view('invoices/create', [
            'title'    => 'Nouvelle facture',
            'patients' => (new PatientModel())->getByTenant($tenantId),
            'medecins' => (new DoctorModel())->getWithUser($tenantId),
            'services' => (new ServiceModel())->getByTenant($tenantId),
            'patient_id' => $patientId,
        ]);
    }

    public function store()
    {
        $tenantId = $this->getTenantId();
        $post     = $this->request->getPost();

        $items    = $post['items'] ?? [];
        $sousTotal = 0;

        foreach ($items as $item) {
            $sousTotal += ($item['prix_unitaire'] ?? 0) * ($item['quantite'] ?? 1);
        }

        $remise = (float) ($post['remise'] ?? 0);
        $total  = $sousTotal - $remise;

        $factureId = $this->invoiceModel->insert([
            'tenant_id'    => $tenantId,
            'patient_id'   => $post['patient_id'],
            'medecin_id'   => $post['medecin_id'] ?: null,
            'numero'       => $this->invoiceModel->generateNumero($tenantId),
            'date_facture' => $post['date_facture'] ?? date('Y-m-d'),
            'sous_total'   => $sousTotal,
            'remise'       => $remise,
            'total'        => $total,
            'montant_paye' => 0,
            'statut'       => 'en_attente',
            'notes'        => $post['notes'] ?? null,
        ]);

        foreach ($items as $item) {
            if (empty($item['description'])) continue;
            $itemTotal = ($item['prix_unitaire'] ?? 0) * ($item['quantite'] ?? 1);
            $this->itemModel->insert([
                'facture_id'    => $factureId,
                'service_id'    => $item['service_id'] ?: null,
                'description'   => $item['description'],
                'quantite'      => $item['quantite'] ?? 1,
                'prix_unitaire' => $item['prix_unitaire'] ?? 0,
                'total'         => $itemTotal,
            ]);
        }

        return redirect()->to('/admin/invoices')->with('success', 'Facture créée.');
    }

    public function view(int $id): string
    {
        $facture = $this->invoiceModel->getOneWithDetails($id);
        if (! $facture || $facture['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/invoices')->with('error', 'Facture introuvable.');
        }
        return view('invoices/view', [
            'title'   => 'Facture ' . $facture['numero'],
            'facture' => $facture,
            'items'   => $this->itemModel->getByInvoice($id),
        ]);
    }

    public function printView(int $id): string
    {
        $facture = $this->invoiceModel->getOneWithDetails($id);
        if (! $facture || $facture['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/invoices')->with('error', 'Facture introuvable.');
        }
        $tenant = (new TenantModel())->find($this->getTenantId());
        return view('invoices/print', [
            'facture' => $facture,
            'items'   => $this->itemModel->getByInvoice($id),
            'tenant'  => $tenant,
        ]);
    }

    public function markPaid(int $id)
    {
        $post    = $this->request->getPost();
        $facture = $this->invoiceModel->find($id);
        if ($facture && $facture['tenant_id'] === $this->getTenantId()) {
            $montantPaye = (float) ($post['montant'] ?? $facture['total']);
            $statut      = $montantPaye >= $facture['total'] ? 'paye' : 'partiel';
            $this->invoiceModel->update($id, [
                'montant_paye'  => $montantPaye,
                'statut'        => $statut,
                'mode_paiement' => $post['mode_paiement'] ?? 'especes',
            ]);
        }
        return redirect()->back()->with('success', 'Paiement enregistré.');
    }
}
