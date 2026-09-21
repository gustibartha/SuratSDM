<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Origins allowed to call the API with credentials (cookies).
     *
     * @var array
     */
    protected $allowedOrigins = [
        'http://localhost:3000',
        'https://sdmupmkr.com',
        'https://www.sdmupmkr.com',
        'https://suratsdm.vercel.app',
    ];

    public function handle($request, Closure $next)
    {
        $origin = $request->headers->get('Origin');
        $allowOrigin = in_array($origin, $this->allowedOrigins, true) ? $origin : $this->allowedOrigins[0];

        if ($request->isMethod('OPTIONS')) {
            $response = response('', 204);
        } else {
            $response = $next($request);
        }

        $response->headers->set('Access-Control-Allow-Origin', $allowOrigin);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-XSRF-TOKEN, X-CSRF-TOKEN, Accept');
        $response->headers->set('Vary', 'Origin');

        return $response;
    }
}
