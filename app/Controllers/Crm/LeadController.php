<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\LeadModel;
use App\Models\Crm\ContactModel;
use App\Models\UserModel;

class LeadController extends BaseController
{
    private LeadModel $model;

    public function __construct()
    {
        $this->model = new LeadModel();
    }

    public function index(): string
    {
        $tid  = session()->get('tenant_id');
        $leads = $this->model->getWithContact($tid);
        $pipeline = $this->model->getPipelineData($tid);
        return view('crm/leads/index', ['leads' => $leads, 'pipeline' => $pipeline]);
    }

    public function kanban(): string
    {
        $tid  = session()->get('tenant_id');
        $leads = $this->model->getWithContact($tid);
        $stages = ['new','qualified','proposition','negotiation','won','lost'];
        $board = [];
        foreach ($stages as $s) {
            $board[$s] = array_filter($leads, fn($l) => $l['statut'] === $s);
        }
        return view('crm/leads/kanban', ['board' => $board]);
    }

    public function create(): string
    {
        $tid = session()->get('tenant_id');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->where('actif', 1)->findAll();
        return view('crm/leads/form', ['lead' => null, 'contacts' => $contacts, 'users' => $users]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $data = $this->request->getPost([
            'contact_id','titre','valeur_estimee','devise','source',
            'statut','probabilite','date_cloture_prevue','notes','assigned_to',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $this->model->insert($data);
        return redirect()->to('/crm/leads')->with('success', lang('Crm.lead_saved'));
    }

    public function edit(int $id): string
    {
        $tid  = session()->get('tenant_id');
        $lead = $this->model->where('tenant_id', $tid)->find($id);
        if (!$lead) return redirect()->to('/crm/leads');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('crm/leads/form', ['lead' => $lead, 'contacts' => $contacts, 'users' => $users]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'contact_id','titre','valeur_estimee','devise','source',
            'statut','probabilite','date_cloture_prevue','notes','assigned_to','raison_perte',
        ]);
        if ($data['statut'] === 'won' || $data['statut'] === 'lost') {
            $data['date_cloture_reelle'] = date('Y-m-d');
        }
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/crm/leads')->with('success', lang('Crm.lead_updated'));
    }

    public function updateStatus(): \CodeIgniter\HTTP\Response
    {
        $tid    = session()->get('tenant_id');
        $id     = (int)$this->request->getPost('id');
        $statut = $this->request->getPost('statut');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => $statut]);
        return $this->response->setJSON(['success' => true]);
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/crm/leads')->with('success', lang('Crm.lead_deleted'));
    }
}
