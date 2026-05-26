<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $user = $this->userModel->find($this->getUserId());
        return view('profile/index', [
            'title' => lang('Nav.profile'),
            'user'  => $user,
        ]);
    }

    public function update()
    {
        $post = $this->request->getPost();
        $rules = [
            'nom'    => 'required',
            'prenom' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($this->getUserId(), [
            'nom'       => $post['nom'],
            'prenom'    => $post['prenom'],
            'telephone' => $post['telephone'] ?? null,
        ]);

        // Mettre à jour la session
        session()->set('nom',    $post['nom']);
        session()->set('prenom', $post['prenom']);

        return redirect()->to('/profile')->with('success', 'Profil mis à jour.');
    }

    public function changePassword()
    {
        $post = $this->request->getPost();

        $user = $this->userModel->find($this->getUserId());
        if (! $user || ! password_verify($post['current_password'] ?? '', $user['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Mot de passe actuel incorrect.');
        }

        if (($post['new_password'] ?? '') !== ($post['confirm_password'] ?? '')) {
            return redirect()->back()->with('error', 'Les nouveaux mots de passe ne correspondent pas.');
        }

        if (strlen($post['new_password'] ?? '') < 8) {
            return redirect()->back()->with('error', 'Le mot de passe doit contenir au moins 8 caractères.');
        }

        $this->userModel->update($this->getUserId(), [
            'mot_de_passe' => password_hash($post['new_password'], PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/profile')->with('success', 'Mot de passe modifié avec succès.');
    }
}
