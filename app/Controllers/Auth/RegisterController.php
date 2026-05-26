<?php
namespace App\Controllers\Auth;
use App\Controllers\BaseController;
use App\Libraries\EmailService;
use App\Models\TenantModel;
use App\Models\UserModel;

class RegisterController extends BaseController
{
    public function index(): string { return view('auth/register'); }

    public function store(): \CodeIgniter\HTTP\RedirectResponse
    {
        $tenantModel   = new TenantModel();
        $userModel     = new UserModel();
        $nomEntreprise = $this->request->getPost('nom_entreprise');
        $email         = $this->request->getPost('email');
        $password      = $this->request->getPost('password');
        $prenom        = $this->request->getPost('prenom');
        $nom           = $this->request->getPost('nom');

        $slug = $tenantModel->generateSlug($nomEntreprise);
        $tenantId = $tenantModel->insert([
            'nom'       => $nomEntreprise,
            'slug'      => $slug,
            'email'     => $email,
            'telephone' => $this->request->getPost('telephone'),
            'pays'      => $this->request->getPost('pays') ?? 'Algérie',
            'plan'      => 'starter',
            'actif'     => 1,
            'expire_le' => date('Y-m-d', strtotime('+30 days')),
            'max_users' => 3,
        ], true);

        $userModel->insert([
            'tenant_id'    => $tenantId,
            'prenom'       => $prenom,
            'nom'          => $nom,
            'email'        => $email,
            'mot_de_passe' => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'admin',
            'locale'       => $this->request->getPost('locale') ?? 'fr',
            'actif'        => 1,
        ]);

        $fullName = trim($prenom . ' ' . $nom);
        try {
            $emailService = new EmailService();
            $emailService->sendAccountCreatedToUser($email, $fullName, $nomEntreprise, $password);
            $emailService->sendAccountCreatedToAdmin($email, $fullName, $nomEntreprise, 'starter');
        } catch (\Exception $e) {
            log_message('error', 'RegisterController email error: ' . $e->getMessage());
        }

        return redirect()->to('/login')->with('success', lang('Auth.register_success'));
    }
}
