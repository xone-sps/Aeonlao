<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 6/4/2019
 * Time: 3:33 PM
 */

namespace App\Http\Controllers;


use App\Models\Site;
use App\Responses\Checker\CheckerProfileManage;
use App\Responses\Checker\CheckerProfileOptions;
use App\Responses\Checker\DashboardResponse;
use App\Responses\IndexCheckerResponse;
use Illuminate\Http\Request;

class CheckerController extends Controller
{
    protected $rootView = 'main.checker';

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
        $this->middleware('api-mode-user-management:super_admin,admin,checker', $this->excepts);
    }

    /**
     * @Responses and Actions api|web
     */
    /**
     * @param Request $request
     * @return IndexCheckerResponse
     */
    public function index(Request $request): IndexCheckerResponse
    {
        return new IndexCheckerResponse($this->options($request));
    }

    public function responseDashboardData(): DashboardResponse
    {
        return new DashboardResponse();
    }

    /****@ResponsesUserProfile  api and action  *** */
    public function responseProfileOptions(Request $request): CheckerProfileOptions
    {
        return new CheckerProfileOptions();
    }

    public function responseProfileManage(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'profile_image' => 'max:3000|mimes:jpeg,png,jpg,gif,svg'
        ]);
        return new CheckerProfileManage($request);
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
