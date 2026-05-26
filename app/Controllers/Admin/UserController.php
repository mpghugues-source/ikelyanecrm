<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('admin/users/index', [
            'title' => 'Gestion des utilisateurs',
            'users' => $this->userModel->getByTenant($this->getTenantId()),
        ]);
    }

    public function create(): string
    {
        return view('admin/users/create', ['title' => 'Nouvel utilisateur']);
    }

    public function store()
    {
        $rules = [
            'nom'      => 'required',
            'prenom'   => 'required',
            'email'    => 'required|valid_email',
            'role'     => 'required',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $post = $this->request->getPost();
        $this->userModel->insert([
            'tenant_id'    => $this->getTenantId(),
            'nom'          => $post['nom'],
            'prenom'       => $post['prenom'],
            'email'        => $post['email'],
            'mot_de_passe' => password_hash($post['password'], PASSWORD_DEFAULT),
            'role'         => $post['role'],
            'telephone'    => $post['telephone'] ?? null,
            'actif'        => 1,
        ]);

        return redirect()->to('/admin/users')->with('success', 'Utilisateur créé.');
    }

    public function edit(int $id): string
    {
        $user = $this->userModel->find($id);
        if (! $user || $user['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur introuvable.');
        }
        return view('admin/users/edit', ['title' => 'Modifier utilisateur', 'user' => $user]);
    }

    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user || $user['tenant_id'] !== $this->getTenantId()) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur introuvable.');
        }

        $post = $this->request->getPost();
        $updateData = [
            'nom'       => $post['nom'],
            'prenom'    => $post['prenom'],
            'telephone' => $post['telephone'] ?? null,
            'role'      => $post['role'],
            'actif'     => $post['actif'] ?? 1,
        ];

        if (! empty($post['password'])) {
            $updateData['mot_de_passe'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $updateData);
        return redirect()->to('/admin/users')->with('success', 'Utilisateur mis à jour.');
    }

    public function delete(int $id)
    {
        $user = $this->userModel->find($id);
        if ($user && $user['tenant_id'] === $this->getTenantId() && $user['id'] !== $this->getUserId()) {
            $this->userModel->update($id, ['actif' => 0]);
        }
        return redirect()->to('/admin/users')->with('success', 'Utilisateur désactivé.');
    }
}
