<?php

declare(strict_types=1);

namespace App\Commands;

use App\Libraries\EmailService;
use App\Models\TenantModel;
use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * php spark account:create-test
 */
class CreateTestAccount extends BaseCommand
{
    protected $group       = 'IkelyaneERP';
    protected $name        = 'account:create-test';
    protected $description = 'Crée un compte test réel et envoie les emails de confirmation';

    public function run(array $params): void
    {
        $email      = $params[0] ?? 'huguesmpg@gmail.com';
        $orgName    = 'Entreprise Test IkelyaneERP';
        $prenom     = 'Hugues';
        $nom        = 'Test';
        $password   = 'Test1234!';

        CLI::write('');
        CLI::write('Création du compte test...', 'yellow');
        CLI::write("  Organisation : {$orgName}");
        CLI::write("  Email        : {$email}");
        CLI::write("  Password     : {$password}");
        CLI::write('');

        $userModel   = new UserModel();
        $tenantModel = new TenantModel();

        // Nettoyage d'un éventuel compte test précédent
        $existing = $userModel->where('email', $email)->first();
        if ($existing) {
            CLI::write("  Suppression de l'ancien compte test...", 'cyan');
            $tenantModel->where('email', $email)->delete();
            $userModel->where('email', $email)->delete();
        }

        $slug     = $tenantModel->generateSlug($orgName);
        $tenantId = $tenantModel->insert([
            'nom'       => $orgName,
            'slug'      => $slug,
            'email'     => $email,
            'telephone' => '0600000000',
            'pays'      => 'Algérie',
            'plan'      => 'starter',
            'actif'     => 1,
            'expire_le' => date('Y-m-d', strtotime('+30 days')),
            'max_users' => 3,
        ], true);

        if (! $tenantId) {
            CLI::error('Erreur création tenant.');
            return;
        }
        CLI::write("  [OK] Tenant créé (id={$tenantId})", 'green');

        $userId = $userModel->insert([
            'tenant_id'    => $tenantId,
            'prenom'       => $prenom,
            'nom'          => $nom,
            'email'        => $email,
            'mot_de_passe' => password_hash($password, PASSWORD_DEFAULT),
            'role'         => 'admin',
            'locale'       => 'fr',
            'actif'        => 1,
        ], true);

        if (! $userId) {
            $tenantModel->delete($tenantId, true);
            CLI::error('Erreur création utilisateur.');
            return;
        }
        CLI::write("  [OK] Utilisateur créé (id={$userId})", 'green');

        CLI::write('');
        CLI::write('Envoi des emails...', 'yellow');

        $emailService = new EmailService();
        $fullName     = $prenom . ' ' . $nom;

        $ok1 = $emailService->sendAccountCreatedToUser($email, $fullName, $orgName, $password);
        CLI::write($ok1
            ? "  [OK] Email de bienvenue envoyé à {$email}"
            : "  [ERREUR] Échec email bienvenue — voir writable/logs/",
            $ok1 ? 'green' : 'red'
        );

        $ok2 = $emailService->sendAccountCreatedToAdmin($email, $fullName, $orgName, 'starter');
        $adminDest = getenv('ADMIN_NOTIFICATION_EMAIL') ?: 'info@myecclesia.org';
        CLI::write($ok2
            ? "  [OK] Notification admin envoyée à {$adminDest}"
            : "  [ERREUR] Échec notification admin — voir writable/logs/",
            $ok2 ? 'green' : 'red'
        );

        CLI::write('');
        CLI::write('Compte créé. Connexion sur :', 'green');
        CLI::write('  ' . base_url('/login'));
        CLI::write("  Login    : {$email}");
        CLI::write("  Password : {$password}");
        CLI::write('');
    }
}
