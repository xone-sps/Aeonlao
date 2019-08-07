<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/30/2019
 * Time: 10:20 PM
 */

namespace App\Responses\Checker;


use App\Http\Controllers\Helpers\Helpers;
use Illuminate\Contracts\Support\Responsable;

class CheckerProfileOptions implements Responsable
{

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $data = [];
        if (Helpers::isAjax($request)) {
            $data['user_profile'] = $this->transformUserProfile($request->user());
            return response()->json(['success' => true, 'data' => $data]);
        }
    }


    public function transformUserProfile($user)
    {
        $data = null;
        if (isset($user)) {
            $data = [];
            $data['first_name'] = $user->name;
            $data['last_name'] = $user->last_name;
            $data['profile_image_base64'] = '';
        }
        return $data;
    }

}
