<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileFilter implements FilterInterface
{
    /**
     * Verifica si el usuario tiene el perfil adecuado para acceder a la ruta
     *
     * @param RequestInterface $request
     * @param array|null       $arguments Perfiles permitidos: 'admin', 'therapist', 'patient'
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        $session = session();
        
        if (!$session->has('logged_in') || !$session->get('logged_in')) {
            return redirect()->to('/');
        }
        
        // Obtener el perfil del usuario actual
        $userProfile = $session->get('profile');
        
        // Mapeo de valores numéricos a nombres de perfil
        $profileNames = [
            1 => 'admin',
            2 => 'therapist',
            3 => 'patient'
        ];
        
        // Convertir el valor numérico a nombre de perfil
        $currentProfile = $profileNames[$userProfile] ?? 'unknown';
        
        // Si no se especifican perfiles permitidos, permitir cualquier perfil autenticado
        if (empty($arguments)) {
            return;
        }
        
        // Verificar si el perfil actual está entre los permitidos
        if (!in_array($currentProfile, $arguments)) {
            // Determinar a dónde redirigir según el perfil del usuario
            switch ($currentProfile) {
                case 'admin':
                    $redirect = '/admin';
                    break;
                case 'therapist':
                    $redirect = '/therapist';
                    break;
                case 'patient':
                    $redirect = '/patient';
                    break;
                default:
                    $redirect = '/login';
                    break;
            }
            
            return redirect()->to($redirect);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción después de la solicitud
    }
}