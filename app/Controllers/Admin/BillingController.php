<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PlanLimitService;
use App\Models\PaymentModel;
use App\Models\SubscriptionPlanModel;
use App\Models\TenantModel;

class BillingController extends BaseController
{
    public function index(): string
    {
        $tenantId    = $this->getTenantId();
        $tenantModel = new TenantModel();
        $tenant      = $tenantModel->find($tenantId);
        $plans       = (new SubscriptionPlanModel())->getAllPlansForUI();

        $paymentModel = new PaymentModel();
        $payments     = $paymentModel->where('tenant_id', $tenantId)
                                     ->whereIn('status', ['paid', 'pending', 'failed'])
                                     ->orderBy('created_at', 'DESC')
                                     ->limit(10)
                                     ->findAll();

        $limitService = new PlanLimitService();
        $doctorUsage  = $limitService->getDoctorUsage($tenantId);
        $patientUsage = $limitService->getPatientUsage($tenantId);

        // Jours restants avant expiration
        $daysLeft = null;
        if ($tenant['expire_le']) {
            $daysLeft = (int) ceil((strtotime($tenant['expire_le']) - time()) / 86400);
        }

        $currentPlan = $plans[$tenant['plan']] ?? $plans['starter'] ?? array_values($plans)[0];

        return view('admin/billing/index', [
            'title'        => 'Mon abonnement',
            'tenant'       => $tenant,
            'currentPlan'  => $currentPlan,
            'plans'        => $plans,
            'payments'     => $payments,
            'doctorUsage'  => $doctorUsage,
            'patientUsage' => $patientUsage,
            'daysLeft'     => $daysLeft,
        ]);
    }
}
