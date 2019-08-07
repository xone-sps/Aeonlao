<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/2/2019
 * Time: 2:46 PM
 */

namespace App\Responses\Admin;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\Posts;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Contracts\Support\Responsable;

class DashboardResponse implements Responsable
{

    use UserRoleTrait;

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            $data = [];
            $data['latest_members_count'] = User::join('user_types', 'user_types.user_id', 'users.id')->whereIn('user_types.type_user_id', User::getNonAdminUserIds())->count();
            $data['scholarships_count'] = $this->getPostsCount('scholarship');
            $data['activities_count'] = $this->getPostsCount('activity')['all'];
            $data['news_count'] = $this->getPostsCount('news')['all'];
            $data['members'] = $this->getMembersCount();

            $data['assessment_count'] = $this->getAssessmentCount();

            return response()->json(['data' => $data]);
        }
    }

    public function getAssessmentCount(): array
    {
        $data = [];
        $data['active'] = Assessment::where('status', 'opening')->count();
        $data['all'] = Assessment::all()->count();
        $data['close'] = Assessment::where('status', 'close')->count();
        $data['success'] = Assessment::where('status', 'success')->count();
        return $data;
    }

    public function getPostsCount($type): array
    {
        $data = [];
        $data['active'] = Posts::where('type', $type)->where('status', 'open')->count();
        $data['all'] = Posts::where('type', $type)->count();
        return $data;
    }

    public function getMembersCount()
    {
        $data = [];
        $data['institute_count'] = $this->getMemberCount('institute');
        $data['field_inspector_count'] = $this->getMemberCount('field_inspector');
        $data['checker_count'] = $this->getMemberCount('checker');
        return $data;
    }

    public function getMemberCount($title)
    {
        return User::join('user_types', 'user_types.user_id', 'users.id')->where('user_types.type_user_id', $this->getTypeUserId($title))->count();
    }
}
