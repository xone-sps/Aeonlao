<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/1/2019
 * Time: 8:48 PM
 */

namespace App\Responses\User;


use App\Department;
use App\Http\Controllers\Helpers\Helpers;
use App\Organize;
use App\User;
use Illuminate\Contracts\Support\Responsable;

class UserProfileSingle implements Responsable
{

    protected $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
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
            $user = User::find($this->user_id);
            $data = [];
            $data['organizes'] = Organize::select('id', 'name')->orderBy('id', 'desc')
                ->get()->map(function ($data) {
                    $data->label = $data->name;
                    $data->value = $data->id;
                    return $data;
                });
            $data['departments'] = Department::select('id', 'name')->orderBy('id', 'desc')
                ->get()->map(function ($data) {
                    $data->label = $data->name;
                    $data->value = $data->id;
                    return $data;
                });
            $data['user_profile'] = $this->transformUserProfile($user);
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
            $data['user_auth_info'] = $this->getUserAuthInfo($user);
            $data['yearOfGraduated'] = ['text' => '', 'value' => ''];
            $userProfile = $user->profile;
            if (isset($userProfile)) {
                if (isset($userProfile->date_of_birth)) {
                    if (User::isAdminUser(auth()->user())) {
                        $data['dateOfBirth'] = $userProfile->date_of_birth->format('c');
                    } else {
                        $data['dateOfBirth'] = $userProfile->date_of_birth->format('d-m');
                    }
                } else {
                    $data['dateOfBirth'] = '';
                }
                $data['marital_status'] = ['text' => (new UserProfileOptions())->getTextMaritalStatus($userProfile->marital_status), 'value' => $userProfile->marital_status];
                $data['personalDescription'] = $userProfile->description;
                $data['phone_number'] = User::isAdminUser(auth()->user()) ? $userProfile->phone_number : '';
                $data['placeOfBirth'] = $userProfile->place_of_birth;
                $data['placeOfResident'] = User::isAdminUser(auth()->user()) ? $userProfile->current_address : '';
            }
            $data['member_educations'] = $user->member_educations();
            $data['member_careers'] = $user->member_careers();
        }
        return $data;
    }

    public function getUserAuthInfo($user)
    {
        $info = $user->transformUser();
        unset($info['type']);
        return $info;
    }

}
