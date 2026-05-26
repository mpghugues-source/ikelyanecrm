<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\CaseModel;
use App\Models\Crm\ContactModel;
use App\Models\UserModel;

class CaseController extends BaseController
{
    private CaseModel $model;

    public function __construct()
    {
        $this->model = new CaseModel();
    }

    public function index(): string
    {
        $tid   = session()->get('tenant_id');
        $cases = $this->model->getWithContact($tid);
        return view('crm/cases/index', ['cases' => $cases]);
    }

    public function create(): string
    {
        $tid      = session()->get('tenant_id');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('crm/cases/form', ['case' => null, 'contacts' => $contacts, 'users' => $users]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'sujet','description','type','priorite','statut','contact_id','assigned_to',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $data['numero']     = $this->model->generateNumber($tid);
        $this->model->insert($data);
        return redirect()->to('/crm/cases')->with('success', lang('Crm.case_saved'));
    }

    public function edit(int $id): string
    {
        $tid      = session()->get('tenant_id');
        $case     = $this->model->where('tenant_id', $tid)->find($id);
        if (!$case) return redirect()->to('/crm/cases');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('crm/cases/form', ['case' => $case, 'contacts' => $contacts, 'users' => $users]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'sujet','description','type','priorite','statut','contact_id','assigned_to','resolution',
        ]);
        if (in_array($data['statut'], ['resolved','closed']) && empty($data['date_resolution'])) {
            $data['date_resolution'] = date('Y-m-d H:i:s');
        }
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/crm/cases')->with('success', lang('Crm.case_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/crm/cases')->with('success', lang('Crm.case_deleted'));
    }
}
