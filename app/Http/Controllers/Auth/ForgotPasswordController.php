<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getEmailFromToken(Request $request, $token)
    {
        $tokenEmail = explode(Helpers::trimBase64('^'), $token);
        $tokenBase64 = Helpers::decode64(isset($tokenEmail[1]) ? $tokenEmail[1] : Helpers::trimBase64('email'));
        $data = DB::table('password_resets')->select('email', 'created_at')
            ->where('email', '=', urldecode($tokenBase64))->first();
        if (isset($data) && !$this->tokenExpired($data->created_at)) {
            return response()->json(['success' => true, 'email' => urlencode($data->email), 'token' => $tokenEmail[0]]);
        }
        return response()->json(['success' => false]);
    }

    protected function tokenExpired($createdAt): bool
    {
        return Carbon::parse($createdAt)->addSeconds(config('auth.passwords.users.expire') * 60)->isPast();
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['success' => true, 'status' => trans($response)]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['errors' => ['error' => [trans($response)]]], 422);
    }
}
