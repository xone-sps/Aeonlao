<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your assets screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'sessionLogout', 'userAutoLogin');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return ($response = $this->authenticated($request, $this->guard()->user()))
            ? $response : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($this->isNotAllowedAccount($user->status)) {//check if user is allowed or not
            return response()->json(['errors' => ['auth_failed' => ['Your account status is ' . Str::title($user->status) . '.']]], 422);
        }

        $personal_token = $user->createToken($user->getTokenName());
        if ($request->ajax()) {
            return [
                'user' => $user->transformUser(),
                'access_token' => $personal_token->accessToken,
                'expires_in' => $user->getPersonalTokenExpiresDaysInSeconds()
            ];
        }
        return false;
    }

    protected function isNotAllowedAccount($status): bool
    {
        $statuses = ['disabled', 'pending'];
        if (in_array($status, $statuses, true)) {
            return true;
        }
        return false;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if ($request->ajax()) {
            $token = Auth::guard('api')->user()->token();
            if (isset($token)) {
                $token->delete();
            }
            return response()->json([], 204);
        }
        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:191',
            'password' => 'required|string|min:6|max:191',
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'auth_failed' => [trans('auth.failed')],
        ]);
    }


    public function userAutoLogin(Request $request, $confirmation_token)
    {
        $this->validate($request, [
            'redirect_url' => 'required|string'
        ]);

        if (strlen($confirmation_token) > 218) {
            return redirect('/');
        }
        if ($user = $request->user()) {
            $this->clearConfirmationCode($user);
            return redirect($request->get('redirect_url'));
        }
        $user = User::where('confirmation_code', $confirmation_token)->first();
        if (isset($user)) {
            $this->clearConfirmationCode($user);
            auth()->login($user);
            return redirect($request->get('redirect_url'));
        }
        return redirect('/');
    }

    public function clearConfirmationCode($user)
    {
        $user->confirmation_code = null;
        $user->save();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function sessionLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/users-logout');
    }


}
