<?php

namespace App\Http\Controllers;

use App\Models\InstituteCategory;
use App\Models\InstituteParentCategory;
use App\Responses\IndexInstituteResponse;
use App\Http\Controllers\Helpers\Helpers;
use App\Responses\Institute\InstituteProfileManage;
use App\Responses\Institute\InstituteProfileOptions;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Responses\Institute\DashboardResponse;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class InstituteProfileController extends Controller
{

    protected $rootView = 'main.institute';

    protected $excepts = [
        'except' => [
        ]
    ];

    /**
     * @description @ApiMode Only admin, super admin and user can access and do works
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('api-mode-user-management:super_admin,admin,institute', $this->excepts);
    }

    /**
     * @Responses and Actions api|web
     */
    /**
     * @param Request $request
     * @return IndexInstituteResponse
     */
    public function index(Request $request): IndexInstituteResponse
    {
        return new IndexInstituteResponse($this->options($request));
    }

    public function responseDashboardData(): DashboardResponse
    {
        return new DashboardResponse();
    }

    /****@ResponsesUserProfile  api and action  *** */
    public function responseProfileOptions(Request $request): InstituteProfileOptions
    {
        return new InstituteProfileOptions();
    }

    public function responseProfileManage(Request $request)
    {
        $this->validate($request, [
            'institute_name' => 'required|string|max:191',
            'short_institute_name' => 'required|string|max:191',
            'profile_image' => 'max:3000|mimes:jpeg,png,jpg,gif,svg',
            'phone_number' => 'max:191',
            'institute_category' => 'required|string|max:191'
        ]);
        $public_email = $request->get('public_email');
        if ($public_email !== null && $public_email !== '') {
            $this->validate($request, [
                'public_email' => 'email|max:191',
            ]);
        }
        $user = $request->user();
        $item = InstituteCategory::find($request->input('institute_category'));
        if (!isset($item)) {
            return response()->json(['errors' => ['institute_category' => ['Your entered institute category is not exists in our system.']]], 422);
        }
        $checking_name = UserProfile::where('institute_name', $request->get('institute_name'))
            ->where('user_id', '!=', $user->id)->first();
        if (isset($checking_name)) {
            return response()->json(['errors' => ['institute_name' => ['Your entered institute name already exists in our system.']]], 422);
        }
        $checking_short_name = UserProfile::where('short_institute_name', $request->get('short_institute_name'))
            ->where('user_id', '!=', $user->id)->first();
        if (isset($checking_short_name)) {
            return response()->json(['errors' => ['short_institute_name' => ['Your entered short institute name already exists in our system.']]], 422);
        }

        if ($item->have_parent === 'yes') {
            $parent_item = InstituteParentCategory::where('child', $item->id)->where('parent_id', $request->input('parent_institute_category'));
            if (!isset($parent_item)) {
                return response()->json(['errors' => ['parent_institute_category' => ['Your entered parent institute category is not exists in our system.']]], 422);
            }
        } else {
            $request->request->set('parent_institute_category', null);
        }

        return new InstituteProfileManage($request);
    }


    /**
     * @return array
     */
    public function getSettings(): array
    {
        $settings = Site::select('id', 'key', 'value')
            ->whereNotIn('key', [])->get();
        $s = [];
        foreach ($settings as $setting) {
            $s[$setting->key] = $setting->value;
        }
        return $s;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function options(Request $request): array
    {
        return [
            'settings' => $this->getSettings(),
            'rootView' => $this->rootView,
        ];
    }
}
