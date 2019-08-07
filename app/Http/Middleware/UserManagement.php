<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\Helpers;
use Closure;

class UserManagement
{
    /**
     * Handle an incoming request.
     * @decription this is for api mode important everything is client have to control all work flows
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $types
     * @return mixed
     */
    public function handle($request, Closure $next, ...$types)
    {
        $user = $request->user();
        $response = $next($request);
        if (isset($user)) {
            $typeUser = $user->type_of_user->name;
            foreach ($types as $type) {
                if ($typeUser === $type) { # check request user type
                    return $this->setResponse($response);
                }
            }// end foreach
        }
        // Failed from permission
        if (Helpers::isAjax($request)) {
            return response()->json(['token' => 'Unauthorized'], 401);
        }
        //if is normal request not ajax will return next request and the client will directly handle all things
        return $this->setResponse($response);
    }

    public function setResponse($response)
    {
        $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0,must-revalidate');
        $response->headers->set('Pragma', 'nocache');
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        return $response;
    }
}
