<?php 
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     if (!session()->get('isLoggedIn'))
    //     {
    //         return redirect()->to('/');
    //     }
    // }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn'))
        {
            return redirect()->to('/');
        }

        // Check for specific roles if provided in $arguments
        if ($arguments) {
            $userRole = session()->get('userRole'); // Assuming role is stored in session
            
            if (!in_array($userRole, $arguments)) {
                // Optionally, show an error or redirect
                return redirect()->to('/inactive'); // Customize this route
            }
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}