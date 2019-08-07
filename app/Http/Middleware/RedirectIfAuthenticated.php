<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\Helpers;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $api = Helpers::isAjax($request);
        if (Auth::guard($guard)->check()) {
            if ($api)
                return $this->apiResponseIfAuthenticated();

            return redirect('/');
        }
        return $next($request);
    }

    public function apiResponseIfAuthenticated()
    {
        return response()->json(['success' => false, 'status' => 'loggedIn',
            'msg' => "Currently you're logged in."]);
    }
}
