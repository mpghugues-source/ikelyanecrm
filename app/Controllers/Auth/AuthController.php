<?php
namespace App\Controllers\Auth;
use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index(): string { return view('auth/login'); }

    public function login(): \CodeIgniter\HTTP\RedirectResponse
    {
        $email = $this->request->getPost('email');
        $pass  = $this->request->getPost('password');
        $model = new UserModel();
        $user  = $model->findByEmail($email);
        if (!$user || !password_verify($pass, $user['mot_de_passe']) || !$user['actif']) {
            return redirect()->back()->withInput()->with('error', lang('Auth.invalid_credentials'));
        }
        $model->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        $locale = $user['locale'] ?? 'fr';
        session()->set([
            'user_id'   => $user['id'],
            'tenant_id' => $user['tenant_id'],
            'role'      => $user['role'],
            'prenom'    => $user['prenom'],
            'nom'       => $user['nom'],
            'email'     => $user['email'],
            'avatar'    => $user['avatar'],
            'locale'    => $locale,
        ]);
        \Config\Services::language()->setLocale($locale);
        if ($user['role'] === 'super_admin') return redirect()->to('/superadmin/dashboard');
        return redirect()->to('/dashboard');
    }

    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword(): string { return view('auth/forgot_password'); }

    public function sendResetLink(): \CodeIgniter\HTTP\RedirectResponse
    {
        $email = $this->request->getPost('email');
        $model = new UserModel();
        $user  = $model->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $model->update($user['id'], ['reset_token' => $token, 'reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))]);
        }
        return redirect()->back()->with('success', lang('Auth.reset_sent'));
    }
}
