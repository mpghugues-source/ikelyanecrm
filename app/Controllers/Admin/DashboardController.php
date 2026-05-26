<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Crm\ContactModel;
use App\Models\Crm\LeadModel;
use App\Models\Crm\ActivityModel;
use App\Models\Crm\CaseModel;
use App\Models\Crm\CampaignModel;
use App\Models\Erp\TransactionModel;
use App\Models\Erp\ProjectModel;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $tid = $this->getTenantId();
        $db  = \Config\Database::connect();

        $contacts   = new ContactModel();
        $leads      = new LeadModel();
        $activities = new ActivityModel();
        $cases      = new CaseModel();
        $campaigns  = new CampaignModel();
        $txModel    = new TransactionModel();
        $projModel  = new ProjectModel();

        $pipeline = $leads->getPipelineData($tid);

        // KPIs CRM
        $kpis = [
            'total_contacts'   => $contacts->where('tenant_id', $tid)->countAllResults(),
            'active_leads'     => $leads->where('tenant_id', $tid)->whereNotIn('statut', ['won','lost'])->countAllResults(),
            'open_cases'       => $cases->where('tenant_id', $tid)->whereIn('statut', ['open','in_progress'])->countAllResults(),
            'active_campaigns' => $campaigns->where('tenant_id', $tid)->where('statut', 'active')->countAllResults(),
            'pipeline_value'   => array_sum(array_column($pipeline, 'total_value')) - ($pipeline['lost']['total_value'] ?? 0),
            'won_this_month'   => $leads->where('tenant_id', $tid)
                                         ->where('statut', 'won')
                                         ->where('MONTH(updated_at)', date('n'))
                                         ->where('YEAR(updated_at)', date('Y'))
                                         ->countAllResults(),
        ];

        // Activités à venir (7 prochains jours)
        $upcoming = $activities->getUpcoming($tid);

        // Leads récents
        $recent_leads = array_slice($leads->getWithContact($tid), 0, 5);

        // Activités récentes
        $recent_activities = array_slice($activities->getWithRelations($tid), 0, 6);

        // Revenue chart (6 derniers mois) depuis ERP
        $revenue_chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            $total = $db->table('erp_transactions')
                ->selectSum('montant')
                ->where('tenant_id', $tid)
                ->where('type', 'income')
                ->where('DATE_FORMAT(date_transaction, "%Y-%m")', $month)
                ->get()->getRowArray()['montant'] ?? 0;
            $revenue_chart[] = ['label' => $label, 'value' => (float)$total];
        }

        // Projets en cours
        $active_projects = $projModel->where('tenant_id', $tid)
                                     ->whereIn('statut', ['planned','in_progress'])
                                     ->findAll(5);

        return view('dashboard/admin', [
            'title'             => lang('Nav.dashboard'),
            'kpis'              => $kpis,
            'pipeline'          => $pipeline,
            'upcoming'          => $upcoming,
            'recent_leads'      => $recent_leads,
            'recent_activities' => $recent_activities,
            'revenue_chart'     => $revenue_chart,
            'active_projects'   => $active_projects,
        ]);
    }
}
