<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 6/4/2019
 * Time: 3:33 PM
 */

namespace App\Http\Controllers;


use App\Models\Site;
use App\Responses\FieldInspector\DashboardResponse;
use App\Responses\FieldInspector\FieldInspectorProfileManage;
use App\Responses\FieldInspector\FieldInspectorProfileOptions;
use App\Responses\IndexFieldInspectorResponse;
use Illuminate\Http\Request;

class FieldInspectorController extends Controller
{
    protected $rootView = 'main.field_inspector';

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
        $this->middleware('api-mode-user-management:super_admin,admin,field_inspector', $this->excepts);
    }

    /**
     * @Responses and Actions api|web
     */
    /**
     * @param Request $request
     * @return IndexFieldInspectorResponse
     */
    public function index(Request $request): IndexFieldInspectorResponse
    {
        return new IndexFieldInspectorResponse($this->options($request));
    }

    public function responseDashboardData(): DashboardResponse
    {
        return new DashboardResponse();
    }


    /****@ResponsesUserProfile  api and action  *** */
    public function responseProfileOptions(Request $request): FieldInspectorProfileOptions
    {
        return new FieldInspectorProfileOptions();
    }


    public function responseProfileManage(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'profile_image' => 'max:3000|mimes:jpeg,png,jpg,gif,svg'
        ]);
        return new FieldInspectorProfileManage($request);
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
