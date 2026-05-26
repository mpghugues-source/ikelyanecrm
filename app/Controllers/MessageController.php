<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\NotificationModel;

class MessageController extends BaseController
{
    private MessageModel $model;

    public function __construct()
    {
        $this->model = new MessageModel();
    }

    /** Boîte de réception */
    public function index(): string
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();
        $box      = $this->request->getGet('box') ?? 'inbox';

        $messages = $box === 'sent'
            ? $this->model->getSent($tenantId, $userId)
            : $this->model->getInbox($tenantId, $userId);

        return view('messages/index', [
            'title'    => $box === 'sent' ? lang('Nav.sent_messages') : lang('Nav.messages'),
            'messages' => $messages,
            'box'      => $box,
            'unread'   => $this->model->countUnread($tenantId, $userId),
        ]);
    }

    /** Lire un message */
    public function view(int $id): string
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();
        $msg      = $this->model->getWithDetails($id);

        if (! $msg || $msg['tenant_id'] != $tenantId
            || ($msg['to_user_id'] != $userId && $msg['from_user_id'] != $userId)) {
            return redirect()->to('/messages')->with('error', 'Message introuvable.');
        }

        if ($msg['to_user_id'] == $userId && ! $msg['is_read']) {
            $this->model->markRead($id);
        }

        // Destinataires disponibles pour la réponse
        $destinataires = $this->getDestinataires($tenantId, $userId);

        return view('messages/view', [
            'title'         => $msg['sujet'] ?? 'Message',
            'msg'           => $msg,
            'destinataires' => $destinataires,
        ]);
    }

    /** Formulaire nouveau message */
    public function create(): string
    {
        $tenantId      = $this->getTenantId();
        $userId        = $this->getUserId();
        $destinataires = $this->getDestinataires($tenantId, $userId);
        $toUserId      = (int)$this->request->getGet('to') ?: 0;

        return view('messages/create', [
            'title'         => lang('Nav.new_message'),
            'destinataires' => $destinataires,
            'to_user_id'    => $toUserId,
            'sujet'         => $this->request->getGet('sujet') ?? '',
        ]);
    }

    /** Envoyer un message */
    public function store()
    {
        $tenantId = $this->getTenantId();
        $userId   = $this->getUserId();

        if (! $this->validate([
            'to_user_id' => 'required|integer',
            'sujet'      => 'required|max_length[255]',
            'message'    => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $toUserId = (int)$this->request->getPost('to_user_id');

        $id = $this->model->insert([
            'tenant_id'    => $tenantId,
            'from_user_id' => $userId,
            'to_user_id'   => $toUserId,
            'rdv_id'       => $this->request->getPost('rdv_id') ?: null,
            'sujet'        => $this->request->getPost('sujet'),
            'message'      => $this->request->getPost('message'),
            'is_read'      => 0,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        // Notification in-app pour le destinataire
        $sender = session()->get('prenom') . ' ' . session()->get('nom');
        (new NotificationModel())->insert([
            'tenant_id'  => $tenantId,
            'user_id'    => $toUserId,
            'type'       => 'message',
            'icone'      => 'bi-envelope',
            'couleur'    => 'primary',
            'titre'      => 'Nouveau message de ' . $sender,
            'message'    => substr($this->request->getPost('sujet'), 0, 100),
            'lien'       => '/messages/' . $id . '/view',
            'lu'         => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/messages')->with('success', 'Message envoyé.');
    }

    /** Supprimer (côté expéditeur seulement) */
    public function delete(int $id)
    {
        $userId = $this->getUserId();
        $msg    = $this->model->find($id);
        if ($msg && $msg['from_user_id'] == $userId) {
            $this->model->delete($id);
        }
        return redirect()->to('/messages?box=sent')->with('success', 'Message supprimé.');
    }

    /** Liste des utilisateurs auxquels l'utilisateur courant peut écrire */
    private function getDestinataires(int $tenantId, int $userId): array
    {
        $role = session()->get('role');
        $db   = $this->model->db;

        if ($role === 'patient') {
            // Un patient peut écrire aux médecins et secrétaires de son tenant
            return $db->table('users')
                ->select('id, CONCAT(prenom, " ", nom) AS nom, role')
                ->whereIn('role', ['medecin', 'admin', 'secretaire'])
                ->where('tenant_id', $tenantId)
                ->where('actif', 1)
                ->where('id !=', $userId)
                ->orderBy('nom')->get()->getResultArray();
        }

        // Médecins, admins et secrétaires peuvent écrire à tout le monde dans le tenant
        return $db->table('users')
            ->select('id, CONCAT(prenom, " ", nom) AS nom, role')
            ->where('tenant_id', $tenantId)
            ->where('actif', 1)
            ->where('id !=', $userId)
            ->orderBy('role')->orderBy('nom')->get()->getResultArray();
    }
}
