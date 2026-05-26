<?php

namespace App\Controllers\Crm;

use App\Controllers\BaseController;
use App\Models\Crm\ContactModel;
use App\Models\Crm\LeadModel;
use App\Models\Crm\ActivityModel;
use App\Models\Crm\CaseModel;
use App\Models\Crm\CampaignModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tid = session()->get('tenant_id');

        $contacts  = new ContactModel();
        $leads     = new LeadModel();
        $activities = new ActivityModel();
        $cases     = new CaseModel();
        $campaigns = new CampaignModel();

        $pipeline = $leads->getPipelineData($tid);

        $kpis = [
            'total_contacts'   => $contacts->where('tenant_id', $tid)->countAllResults(),
            'active_leads'     => $leads->where('tenant_id', $tid)->whereNotIn('statut', ['won','lost'])->countAllResults(),
            'open_cases'       => $cases->where('tenant_id', $tid)->whereIn('statut', ['open','in_progress'])->countAllResults(),
            'active_campaigns' => $campaigns->where('tenant_id', $tid)->where('statut', 'active')->countAllResults(),
            'pipeline_value'   => array_sum(array_column(
                array_filter($pipeline, fn($k) => !in_array($k, ['won','lost']), ARRAY_FILTER_USE_KEY),
                'total_value'
            )),
            'won_value'        => $pipeline['won']['total_value'] ?? 0,
        ];

        $recent_activities = $activities->getWithRelations($tid);
        $recent_activities = array_slice($recent_activities, 0, 8);

        $upcoming = $activities->getUpcoming($tid);
        $recent_leads = $leads->getWithContact($tid);
        $recent_leads = array_slice($recent_leads, 0, 5);

        return view('crm/dashboard', [
            'kpis'              => $kpis,
            'pipeline'          => $pipeline,
            'recent_activities' => $recent_activities,
            'upcoming'          => $upcoming,
            'recent_leads'      => $recent_leads,
        ]);
    }
}
