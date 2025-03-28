<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Ajax implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si la solicitud contiene la cabecera X-Requested-With con valor XMLHttpRequest
        if (!$request->isAJAX()) {
            return service('response')
                ->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Acceso directo no permitido. Esta ruta solo acepta solicitudes Ajax.'
                ]);
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
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Asegurarse de que las respuestas siempre tengan los encabezados CORS correctos
        // para permitir solicitudes desde orígenes específicos (si es necesario)
        $response->setHeader('Access-Control-Allow-Origin', site_url());
        $response->setHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        
        return $response;
    }
}
