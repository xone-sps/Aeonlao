<?php

namespace App\Http\Controllers;

use App\Responses\Admin\DashboardResponse;
use App\Responses\Admin\SendUsersAssessmentActionResponse;
use App\Responses\FieldInspector\CheckAssessmentsFieldInspectorResponse;
use App\Responses\FileResponse;
use App\Responses\IndexUserResponse;
use App\Responses\User\CheckAssessmentActionResponse;
use App\Responses\User\CheckAssessmentComment\CheckAssessmentCommentResponse;
use App\Responses\User\SaveAssessmentsFieldInspectorResponse;
use App\Responses\User\SaveAssessmentsResponse;
use App\Responses\User\UserCheckAssessmentsResponse;
use App\Responses\User\UserCredentials;
use App\Responses\User\UserFileDownloadResponse;
use App\Responses\User\UserProfileManage;
use App\Responses\User\UserProfileOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $rootView = 'main.user';

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
        $this->middleware('api-mode-user-management:super_admin,admin,institute,checker,field_inspector', $this->excepts);
    }

    /**
     * @Responses and Actions api|web
     */
    /**
     * @param Request $request
     * @return IndexUserResponse
     */
    public function index(Request $request): IndexUserResponse
    {
        return new IndexUserResponse($this->options($request));
    }

    public function responseActionUserAutoLogin(Request $request)
    {
        $user = $request->user();
        $user->confirmation_code = Str::random(218);
        $user->save();
        $data = route('get.user.UserAutoLogin', $user->confirmation_code);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function downloadFileExport(Request $request)
    {
        return new UserFileDownloadResponse();
    }
    /**
     * @Responses and Actions api|web
     */

    /****@Responses  api only ***
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        $authUser = auth()->user();
        if (!isset($authUser)) {
            return response()->json(['success' => false]);
        }
        return response()->json(['success' => true, 'auth' => $authUser->transformUser()]);
    }

    public function responseActionFetchAssessment(Request $request, $id): UserCheckAssessmentsResponse
    {
        return new UserCheckAssessmentsResponse('fetch');
    }

    public function responseActionChangeCheckAsessmentStatus(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required',//check assessment id
            'status' => 'required',
        ]);
        return new CheckAssessmentActionResponse('change-status');
    }

    public function responseSaveAessmentAnswer(Request $request, $id)
    {
        $this->validate($request, [
            'check_assessment_sections' => 'required',
        ]);
        return new SaveAssessmentsResponse();
    }


    public function responseSaveCheckAessmentAnswerFieldInspector(Request $request, $id)
    {
        $this->validate($request, [
            'check_assessment_sections' => 'required',
        ]);
        return new  SaveAssessmentsFieldInspectorResponse();
    }


    public function responseActionSaveCheckAsessmentAnswerStatusScore(Request $request, $id)
    {
        $this->validate($request, [
            'check_assessment_sections' => 'required',
        ]);
        return new CheckAssessmentActionResponse('save-answer-status-score');
    }

    public function responseActionFetchInstituteUsers(Request $request)
    {
        return new SendUsersAssessmentActionResponse('fetch-send', ['only' => 'institutes']);
    }

    public function getCheckAssessmentFieldInspector(Request $request, $id)
    {
        return new  CheckAssessmentsFieldInspectorResponse('fetch');
    }

    /**
     *
     */
    /****@ResponsesSearches api and action  *** */
    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */

    public function responseSearches(Request $request, $type): JsonResponse
    {
        $data = [];
        $paginateLimit = ($request->exists('limit') && !empty($request->get('limit'))) ? $request->get('limit') : 10;
        $paginateLimit = Helpers::isNumber($paginateLimit) ? $paginateLimit : 10;
        $text = $request->get('q');
        $user = $request->user();
        if ($type === 'check_assessments') {
            $data = (new UserCheckAssessmentsResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'downloadFiles') {
            $data = (new FileResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'check_assessments_field_inspector' && $user->hasActions('view_check_assessments')) {
            $data = (new CheckAssessmentsFieldInspectorResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        }
        if (count($data) > 0) {
            $data->appends(['limit' => $request->exists('limit'), 'q' => $request->get('q')]);
        }
        return response()->json(['data' => $data]);
    }
    /****@ResponsesSearches api and action  ** */

    /****@ResponsesUserProfile  api and action  *** */
    public function responseProfileOptions(Request $request): UserProfileOptions
    {
        return new UserProfileOptions();
    }

    public function responseProfileManage(Request $request): UserProfileManage
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'profile_image' => 'max:3000|mimes:jpeg,png,jpg,gif,svg',
            'phone_number' => 'max:191',
        ]);
        return new UserProfileManage($request);
    }

    /****@ResponsesUserProfile  api and action  *** */

    /****@ResponsesUserCredentials  api and action  *** */

    public function responseCredentialsManage(Request $request)
    {

        $this->validate($request, ['current_password' => 'required|min:6|max:191']);

        $user = User::find(auth()->user()->id);//current user

        if ($this->isNeedToValidate($request, 'new_email')) {
            $this->validate($request, ['new_email' => 'email|max:191']);
            if ($this->userExists($request->get('new_email'))) {
                return response()->json(['errors' => ['new_email' => ['Your entered email already exists in our system.']]], 422);
            }
        }


        if (!(isset($user) && Hash::check($request->get('current_password'), $user->password))) {
            return response()->json(['errors' => ['current_password' => ['Your entered current password is not match your current password.']]], 422);
        }

        if ($this->isNeedToValidate($request, 'new_password')) {
            $this->validate($request, [
                'new_password' => 'confirmed|min:6|max:191|different:current_password',
                'password_confirmation' => 'min:6|max:191'
            ]);
        }

        //check if enter current password matched the current password
        return new UserCredentials($user);
    }

    public function isNeedToValidate($request, $name)
    {
        return $request->has($name) && $request->get($name) !== null && !empty($request->get($name));
    }
    /****@EndResponsesUserCredentials  api and action  *** */

    /****@ResponsesDashboardData  api and action  *** */

    public function responseDashboardData(Request $request): DashboardResponse
    {
        return new DashboardResponse();
    }

    /****@ResponsesDashboardData  api and action  *** */

    /****@ResponsesCheckAssessmentCommnetData  api and action  *** */
    public function getCheckAssessmentComments(Request $request)
    {
        return new CheckAssessmentCommentResponse('get');
    }

    public function manageCheckAssessmentComments(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
        ]);
        return new CheckAssessmentCommentResponse('manage');
    }

    public function deleteCheckAssessmentComments(Request $request)
    {
        return new CheckAssessmentCommentResponse('delete');
    }
    /****@ResponsesCheckAssessmentCommnetData  api and action  *** */
    /**
     * @Responses api only
     */

    /**
     * @Helper helper functions
     */

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

    public static function isEngText($text): bool
    {
        return (strlen($text) === strlen(utf8_decode($text))); // is english
    }

    private function userExists($email): bool
    {
        $user = User::where('email', $email)->first();
        return isset($user);
    }

    /**
     * @Helper helper functions
     */
}
