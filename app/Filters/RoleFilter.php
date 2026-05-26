<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if ($arguments) {
            $roles    = array_map('trim', (array) $arguments);
            $userRole = session()->get('role');

            if (! in_array($userRole, $roles) && $userRole !== 'super_admin') {
                return redirect()->back()->with('error', 'Permission insuffisante.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
