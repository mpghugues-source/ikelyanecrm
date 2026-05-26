<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\ContactModel;

class ContactController extends BaseController
{
    private ContactModel $model;

    public function __construct()
    {
        $this->model = new ContactModel();
    }

    public function index(): string
    {
        $tid = session()->get('tenant_id');
        $contacts = $this->model->getWithStats($tid);
        return view('crm/contacts/index', ['contacts' => $contacts]);
    }

    public function create(): string
    {
        return view('crm/contacts/form', ['contact' => null]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $data = $this->request->getPost([
            'type','prenom','nom','email','telephone',
            'entreprise','poste','adresse','ville','pays','source','statut','notes',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $this->model->insert($data);
        return redirect()->to('/crm/contacts')->with('success', lang('Crm.contact_saved'));
    }

    public function view(int $id): string
    {
        $tid     = session()->get('tenant_id');
        $contact = $this->model->where('tenant_id', $tid)->find($id);
        if (!$contact) return redirect()->to('/crm/contacts');
        return view('crm/contacts/view', ['contact' => $contact]);
    }

    public function edit(int $id): string
    {
        $tid     = session()->get('tenant_id');
        $contact = $this->model->where('tenant_id', $tid)->find($id);
        if (!$contact) return redirect()->to('/crm/contacts');
        return view('crm/contacts/form', ['contact' => $contact]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $data = $this->request->getPost([
            'type','prenom','nom','email','telephone',
            'entreprise','poste','adresse','ville','pays','source','statut','notes',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/crm/contacts')->with('success', lang('Crm.contact_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/crm/contacts')->with('success', lang('Crm.contact_deleted'));
    }
}
