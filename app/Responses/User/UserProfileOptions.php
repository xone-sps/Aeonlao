<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/25/2019
 * Time: 11:03 PM
 */

namespace App\Responses\User;


use App\Http\Controllers\Helpers\Helpers;
use App\User;
use Illuminate\Contracts\Support\Responsable;
// use App\EducationDegree;
// use App\Organize;
// use App\Department;

class UserProfileOptions implements Responsable
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
            // $data['organizes'] = Organize::select('id', 'name')->orderBy('id', 'desc')
            //     ->get()->map(function ($data) {
            //         $data->label = $data->name;
            //         $data->value = $data->id;
            //         return $data;
            //     });
            // $data['departments'] = Department::select('id', 'name')->orderBy('id', 'desc')
            //     ->get()->map(function ($data) {
            //         $data->label = $data->name;
            //         $data->value = $data->id;
            //         return $data;
            //     });

            // $data['education_degrees'] = EducationDegree::select('id', 'name')->orderBy('id', 'desc')
            //     ->get()->map(function ($data) {
            //         $data->label = $data->name;
            //         $data->value = $data->id;
            //         return $data;
            //     });

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
            $data['marital_status'] = ['text' => '', 'value' => ''];
            $userProfile = $user->profile;
            if (isset($userProfile)) {
                $data['phone_number'] = $userProfile->phone_number;
                if (isset($userProfile->date_of_birth)) {
                    $data['dateOfBirth'] = $userProfile->date_of_birth->format('c');
                } else {
                    $data['dateOfBirth'] = '';
                }
                $data['marital_status'] = ['text' => $this->getTextMaritalStatus($userProfile->marital_status), 'value' => $userProfile->marital_status];
            }
            // $data['member_educations'] = $user->member_educations();
            // $data['member_careers'] = $user->member_careers();
        }
        return $data;
    }

    public function getYearsOptions()
    {
        $years = [];
        for ($i = 1960, $iMax = date('Y'); $i <= $iMax + 42; $i++) {
            $years[] = (string)$i;
        }
        return $years;
    }

    public function getTextMaritalStatus($title)
    {
        $statuses = ['none' => 'Not specified', 'single' => 'Single', 'married' => 'Married'];
        return $statuses[$title] ?? 'Not specified';
    }
}
