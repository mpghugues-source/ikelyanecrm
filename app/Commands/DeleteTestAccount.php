<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DeleteTestAccount extends BaseCommand
{
    protected $group       = 'IkelyaneERP';
    protected $name        = 'account:delete-test';
    protected $description = 'Supprime tous les comptes test créés avec huguesmpg@gmail.com';

    public function run(array $params): void
    {
        $db    = \Config\Database::connect();
        $email = 'huguesmpg@gmail.com';

        $tenants = $db->table('tenants')->where('email', $email)->get()->getResultArray();

        if (empty($tenants)) {
            CLI::write("Aucun compte test trouvé pour {$email}.", 'yellow');
            return;
        }

        foreach ($tenants as $tenant) {
            $db->table('users')->where('tenant_id', $tenant['id'])->delete();
            $db->table('tenants')->where('id', $tenant['id'])->delete();
            CLI::write("  [OK] Supprimé : {$tenant['nom']} (tenant_id={$tenant['id']})", 'green');
        }

        CLI::write('Compte(s) test supprimé(s).', 'green');
    }
}
