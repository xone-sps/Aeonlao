<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParseBearToken
{
    protected $redirect_path = '/login';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $type
     * @return mixed
     */
    public function handle($request, Closure $next, $type = '')
    {
        $token = $this->getToken($request);
        if ($token) {
            return $next($this->setResponse($request, $token));
        }

        if($type==='guest-bearer'){//use for parse the token form guest page
            return $next($this->setResponse($request, $type));
        }

        // Failed from permission
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['token' => 'Unauthorized'], 401);
        }
        return redirect($this->redirect_path);
    }

    public function setResponse($request, $token)
    {
        $request->headers->set('Authorization', "Bearer {$token}");
        return $request;
    }

    private function getToken(Request $request)
    {
        return $request->headers->get('CL-Token');
    }
}
