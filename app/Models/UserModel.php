<?php
namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model
{
    protected $table='users'; protected $primaryKey='id'; protected $useTimestamps=true;
    protected $allowedFields=['tenant_id','prenom','nom','email','mot_de_passe','role','telephone','avatar','locale','actif','last_login','reset_token','reset_expires'];
    public function forTenant(int $tid): static { return $this->where('tenant_id',$tid); }
    public function findByEmail(string $email): ?array { return $this->where('email',$email)->first(); }
    public function fullName(array $user): string { return trim($user['prenom'].' '.$user['nom']); }
}
