<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\CampaignModel;

class CampaignController extends BaseController
{
    private CampaignModel $model;

    public function __construct()
    {
        $this->model = new CampaignModel();
    }

    public function index(): string
    {
        $tid       = session()->get('tenant_id');
        $campaigns = $this->model->forTenant($tid)->orderBy('created_at','DESC')->findAll();
        $stats     = $this->model->getStats($tid);
        return view('crm/campaigns/index', ['campaigns' => $campaigns, 'stats' => $stats]);
    }

    public function create(): string
    {
        return view('crm/campaigns/form', ['campaign' => null]);
    }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','description','type','statut','budget',
            'date_debut','date_fin','objectif',
        ]);
        $data['tenant_id']  = $tid;
        $data['created_by'] = session()->get('user_id');
        $this->model->insert($data);
        return redirect()->to('/crm/campaigns')->with('success', lang('Crm.campaign_saved'));
    }

    public function edit(int $id): string
    {
        $tid      = session()->get('tenant_id');
        $campaign = $this->model->where('tenant_id', $tid)->find($id);
        if (!$campaign) return redirect()->to('/crm/campaigns');
        return view('crm/campaigns/form', ['campaign' => $campaign]);
    }

    public function update(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid  = session()->get('tenant_id');
        $data = $this->request->getPost([
            'nom','description','type','statut','budget','cout_reel',
            'nb_cibles','nb_reponses','date_debut','date_fin','objectif',
        ]);
        $this->model->where('tenant_id', $tid)->update($id, $data);
        return redirect()->to('/crm/campaigns')->with('success', lang('Crm.campaign_updated'));
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $tid = session()->get('tenant_id');
        $this->model->where('tenant_id', $tid)->delete($id);
        return redirect()->to('/crm/campaigns')->with('success', lang('Crm.campaign_deleted'));
    }
}
