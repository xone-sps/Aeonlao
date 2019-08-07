<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/27/2019
 * Time: 10:29 AM
 */

namespace App\Responses\Institute;


use App\Http\Controllers\Helpers\Helpers;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class InstituteCredentials implements Responsable
{

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {

            //check if admin return back
            if (User::isAdminUser(auth()->user())) {
                return response()->json(['success' => true, 'data' => 'Updated Credentials!']);
            }

            if ($this->isRequestNotEmpty($request, 'new_email')) {
                $this->user->fill([
                    'email' => $request->get('new_email')
                ])->save();
            }
            if ($this->isRequestNotEmpty($request, 'new_password')) {
                $this->user->fill([
                    'password' => Hash::make($request->get('new_password'))
                ])->save();
            }
            if ($this->isRequestNotEmpty($request, 'logout_from_all_other_devices')
                && $request->get('logout_from_all_other_devices')) {
                $currentToken = auth()->user()->token();
                $this->revokeAllValidTokens([$currentToken->id]);
            }
            return response()->json(['success' => true, 'data' => 'Updated Credentials!']);
        }
    }

    public function revokeAllValidTokens($exceptIds = [])
    {
        $deleted = Passport::$tokenModel::where('user_id', $this->user->id)
            ->where('name', $this->user->getTokenName())
            ->whereNotIn('id', $exceptIds)
            ->where('client_id', 1)
            ->where('revoked', 0)
            ->delete();
        return $deleted;
    }

    public function isRequestNotEmpty($request, $n): bool
    {
        return $request->has($n) && !empty($request->get($n));
    }
}
