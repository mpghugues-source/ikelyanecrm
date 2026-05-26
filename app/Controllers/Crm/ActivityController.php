<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\ActivityModel;
use App\Models\Crm\ContactModel;
use App\Models\Crm\LeadModel;
use App\Models\UserModel;

class ActivityController extends BaseController
{
    private ActivityModel $model;

    public function __construct()
    {
        $this->model = new ActivityModel();
    }

    public function index(): string
    {
        $tid = session()->get('tenant_id');
        $activities = $this->model->getWithRelations($tid);
        return view('crm/activities/index', ['activities' => $activities]);
    }

    public function create(): string
    {
        $tid      = session()->get('tenant_id');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $leads    = (new LeadModel())->where('tenant_id', $tid)->whereNotIn('statut',['won','lost'])->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('crm/activities/form', [
            'activity' => null,
            'contacts' => $contacts,
            'leads'    => $leads,
            'users'    => $users,
        ]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'type','sujet','description','date_debut','date_fin',
            'statut','priorite','contact_id','lead_id','assigned_to',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $this->model->insert($data);
        return redirect()->to('/crm/activities')->with('success', lang('Crm.activity_saved'));
    }

    public function edit(int $id): string
    {
        $tid      = session()->get('tenant_id');
        $activity = $this->model->where('tenant_id', $tid)->find($id);
        if (!$activity) return redirect()->to('/crm/activities');
        $contacts = (new ContactModel())->where('tenant_id', $tid)->findAll();
        $leads    = (new LeadModel())->where('tenant_id', $tid)->findAll();
        $users    = (new UserModel())->where('tenant_id', $tid)->findAll();
        return view('crm/activities/form', [
            'activity' => $activity,
            'contacts' => $contacts,
            'leads'    => $leads,
            'users'    => $users,
        ]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'type','sujet','description','date_debut','date_fin',
            'statut','priorite','contact_id','lead_id','assigned_to',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/crm/activities')->with('success', lang('Crm.activity_updated'));
    }

    public function complete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->update($id, ['statut' => 'completed']);
        return redirect()->back()->with('success', lang('Crm.activity_completed'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/crm/activities')->with('success', lang('Crm.activity_deleted'));
    }
}
