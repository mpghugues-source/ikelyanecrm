<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsersController extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        $users = $db->query("
            SELECT u.*, t.nom as tenant_nom, t.ville as tenant_ville
            FROM users u
            LEFT JOIN tenants t ON t.id = u.tenant_id
            WHERE u.role != 'super_admin'
            ORDER BY u.created_at DESC
        ")->getResultArray();

        $stats = [
            'total'      => count($users),
            'admin'      => count(array_filter($users, fn($u) => $u['role'] === 'admin')),
            'medecin'    => count(array_filter($users, fn($u) => $u['role'] === 'medecin')),
            'secretaire' => count(array_filter($users, fn($u) => $u['role'] === 'secretaire')),
            'patient'    => count(array_filter($users, fn($u) => $u['role'] === 'patient')),
        ];

        return view('superadmin/users/index', [
            'title' => lang('SuperAdmin.users_title'),
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    public function toggle(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);
        if ($user) {
            $userModel->update($id, ['actif' => $user['actif'] ? 0 : 1]);
            $msg = $user['actif'] ? 'Utilisateur désactivé.' : 'Utilisateur activé.';
            return redirect()->back()->with('success', $msg);
        }
        return redirect()->back()->with('error', 'Utilisateur introuvable.');
    }
}
