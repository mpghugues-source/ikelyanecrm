<?php
namespace App\Models;
use CodeIgniter\Model;
class TenantModel extends Model
{
    protected $table='tenants'; protected $primaryKey='id'; protected $useTimestamps=true;
    protected $allowedFields=['nom','slug','email','telephone','adresse','ville','pays','logo','couleur','plan','actif','expire_le','max_users'];
    public function findBySlug(string $slug): ?array { return $this->where('slug',$slug)->first(); }
    public function generateSlug(string $nom): string {
        $slug=strtolower(preg_replace('/[^a-z0-9]+/','',str_replace([' ','\''],'-',$nom)));
        $count=$this->like('slug',$slug)->countAllResults();
        return $count>0 ? $slug.'-'.($count+1) : $slug;
    }
}
