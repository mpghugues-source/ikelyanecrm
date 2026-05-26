<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\TransactionModel;
use App\Models\Erp\AccountModel;
use App\Models\Erp\BudgetModel;

class FinanceController extends BaseController
{
    private TransactionModel $txModel;
    private AccountModel     $accModel;

    public function __construct()
    {
        $this->txModel  = new TransactionModel();
        $this->accModel = new AccountModel();
    }

    // ── TRANSACTIONS ──────────────────────────────────────────────────

    public function transactions(): string
    {
        $tid          = session()->get('tenant_id');
        $transactions = $this->txModel->getWithAccount($tid);
        $accounts     = $this->accModel->forTenant($tid)->where('actif', 1)->findAll();
        return view('erp/finance/transactions', [
            'transactions' => $transactions,
            'accounts'     => $accounts,
        ]);
    }

    public function storeTransaction(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'date_transaction','description','type','montant','account_id','notes',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $data['statut']     = 'validated';
        $data['reference']  = $this->txModel->generateReference($tid);
        $this->txModel->insert($data);
        return redirect()->to('/erp/finance/transactions')->with('success', lang('Erp.transaction_saved'));
    }

    public function deleteTransaction(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->txModel->where('tenant_id', $tid)->update($id, ['statut' => 'cancelled']);
        return redirect()->to('/erp/finance/transactions')->with('success', lang('Erp.transaction_cancelled'));
    }

    // ── CHART OF ACCOUNTS ─────────────────────────────────────────────

    public function accounts(): string
    {
        $tid      = session()->get('tenant_id');
        $accounts = $this->accModel->forTenant($tid)->orderBy('code','ASC')->findAll();
        $balances = $this->accModel->getBalanceByType($tid);
        return view('erp/finance/accounts', ['accounts' => $accounts, 'balances' => $balances]);
    }

    public function storeAccount(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost(['code','nom','type','solde','description','parent_id']);
        $data['tenant_id']  = $tid;
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->accModel->insert($data);
        return redirect()->to('/erp/finance/accounts')->with('success', lang('Erp.account_saved'));
    }

    public function deleteAccount(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->accModel->where('tenant_id', $tid)->update($id, ['actif' => 0]);
        return redirect()->to('/erp/finance/accounts')->with('success', lang('Erp.account_deleted'));
    }

    // ── BUDGETS ───────────────────────────────────────────────────────

    public function budgets(): string
    {
        $tid      = session()->get('tenant_id');
        $budgetM  = new BudgetModel();
        $budgets  = $budgetM->getWithAccount($tid);
        $accounts = $this->accModel->forTenant($tid)->where('actif',1)->findAll();
        return view('erp/finance/budgets', ['budgets' => $budgets, 'accounts' => $accounts]);
    }

    public function storeBudget(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid     = session()->get('tenant_id');
        $budgetM = new BudgetModel();
        $data    = $this->request->getPost([
            'nom','exercice','periode','mois','account_id','montant_prevu','notes',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $budgetM->insert($data);
        return redirect()->to('/erp/finance/budgets')->with('success', lang('Erp.budget_saved'));
    }

    public function deleteBudget(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid     = session()->get('tenant_id');
        $budgetM = new BudgetModel();
        $budgetM->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/erp/finance/budgets')->with('success', lang('Erp.budget_deleted'));
    }
}
