<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    /** API : récupérer les notifs non lues (JSON) */
    public function getJson()
    {
        $tenantId = session()->get('tenant_id');
        $userId   = session()->get('user_id');
        $model    = new NotificationModel();

        return $this->response->setJSON([
            'count'         => $model->countUnread($tenantId, $userId),
            'notifications' => $model->getUnread($tenantId, $userId),
        ]);
    }

    /** Marquer toutes comme lues */
    public function markRead()
    {
        $tenantId = session()->get('tenant_id');
        $userId   = session()->get('user_id');
        $model    = new NotificationModel();
        $model->markAllRead($tenantId, $userId);

        return $this->response->setJSON(['success' => true]);
    }

    /** Marquer une notif comme lue et rediriger */
    public function markOneRead(int $id)
    {
        $tenantId = session()->get('tenant_id');
        $model    = new NotificationModel();
        $notif    = $model->find($id);

        if ($notif && $notif['tenant_id'] == $tenantId) {
            $model->update($id, ['lu' => 1]);
            if ($notif['lien']) {
                return redirect()->to($notif['lien']);
            }
        }

        return redirect()->back();
    }
}
