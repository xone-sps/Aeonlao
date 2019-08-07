<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/30/2019
 * Time: 10:20 PM
 */

namespace App\Responses\Institute;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\InstituteCategory;
use Illuminate\Contracts\Support\Responsable;

class InstituteProfileOptions implements Responsable
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
            $data['institute_categories'] = InstituteCategory::select('id', 'name', 'have_parent')->orderBy('id', 'desc')->get();
            $data['user_profile'] = $this->transformUserProfile($request->user());
            return response()->json(['success' => true, 'data' => $data]);
        }
    }


    public function transformUserProfile($user)
    {
        $data = null;
        if (isset($user)) {
            $data = [];
            $data['institute_name'] = $user->name;
            $data['short_institute_name'] = $user->last_name;
            $data['profile_image_base64'] = '';
            $userProfile = $user->userProfile;
            if (isset($userProfile)) {

                $data['institute_name'] = $userProfile->institute_name;
                $data['short_institute_name'] = $userProfile->short_institute_name;

                $data['public_email'] = $userProfile->public_email;
                $data['address'] = $userProfile->address;
                $data['phone_number'] = $userProfile->phone_number;

                $data['facebook'] = $userProfile->facebook;
                $data['googlemap'] = $userProfile->googlemap;
                $data['about'] = $userProfile->about;
                $data['website'] = $userProfile->website;
                $data['institute_category_id'] = $userProfile->institute_category_id;
                $data['parent_institute_category_id'] = $userProfile->parent_institute_category_id;

                if (isset($userProfile->founded)) {
                    $data['founded'] = $userProfile->founded->format('c');
                } else {
                    $data['founded'] = '';
                }
                $data['institute_category'] = $this->getTextInstituteCategory($userProfile->institute_category_id);
                if ($userProfile->parent_institute_category_id) {
                    $data['parent_institute_category'] = $this->getTextInstituteCategory($userProfile->parent_institute_category_id);
                }
            }
        }
        return $data;
    }

    public function getTextInstituteCategory($institute_category_id)
    {
        $category = InstituteCategory::find($institute_category_id);
        return $category ?? '';
    }

}
