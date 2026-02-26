<?php 
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn'))
        {
            return redirect()->to('/');
        }

        // Check for specific roles if provided in $arguments
        if ($arguments) {
            $userRole = session()->get('userRole');

            // Admin siempre tiene acceso a todos los módulos
            if ($userRole === 'admin') {
                return;
            }

            if (!in_array($userRole, $arguments)) {
                return redirect()->to(previous_url());
            }
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}