<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\TransactionModel;
use App\Models\Erp\InventoryItemModel;
use App\Models\Erp\PurchaseOrderModel;
use App\Models\Erp\ProjectModel;
use App\Models\Erp\SupplierModel;
use App\Models\Erp\BudgetModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tid = session()->get('tenant_id');

        $transactions = new TransactionModel();
        $inventory    = new InventoryItemModel();
        $po           = new PurchaseOrderModel();
        $projects     = new ProjectModel();
        $suppliers    = new SupplierModel();

        $yearlyRevenue  = $transactions->where('tenant_id', $tid)->where('type','income')->where('statut','validated')->where("YEAR(date_transaction)", date('Y'))->selectSum('montant')->first()['montant'] ?? 0;
        $yearlyExpenses = $transactions->where('tenant_id', $tid)->where('type','expense')->where('statut','validated')->where("YEAR(date_transaction)", date('Y'))->selectSum('montant')->first()['montant'] ?? 0;

        $kpis = [
            'yearly_revenue'  => $yearlyRevenue,
            'yearly_expenses' => $yearlyExpenses,
            'net_result'      => $yearlyRevenue - $yearlyExpenses,
            'low_stock'       => count($inventory->getLowStock($tid)),
            'pending_po'      => $po->where('tenant_id', $tid)->whereIn('statut',['draft','sent'])->countAllResults(),
            'active_projects' => $projects->where('tenant_id', $tid)->where('statut','active')->countAllResults(),
            'total_suppliers' => $suppliers->where('tenant_id', $tid)->where('statut','active')->countAllResults(),
        ];

        $monthly = $transactions->getMonthlySummary($tid, (int)date('Y'));
        $recent_transactions = $transactions->getWithAccount($tid);
        $recent_transactions = array_slice($recent_transactions, 0, 8);

        $low_stock_items  = $inventory->getLowStock($tid);
        $recent_projects  = $projects->getWithChef($tid);
        $recent_projects  = array_slice($recent_projects, 0, 5);

        return view('erp/dashboard', [
            'kpis'                => $kpis,
            'monthly'             => $monthly,
            'recent_transactions' => $recent_transactions,
            'low_stock_items'     => $low_stock_items,
            'recent_projects'     => $recent_projects,
        ]);
    }
}
