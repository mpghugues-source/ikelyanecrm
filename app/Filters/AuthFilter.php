<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter.');
        }
        if ($arguments) {
            $role = session()->get('role');
            if (!in_array($role, $arguments)) {
                // Redirect to appropriate dashboard based on role
                $fallback = match($role) {
                    'super_admin' => '/superadmin/dashboard',
                    default       => '/admin/dashboard',
                };
                return redirect()->to($fallback)->with('error', 'Accès non autorisé.');
            }
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
