<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Helpers\Helpers;
use App\Jobs\SendUserChangeStatus;
use App\Models\InstituteCategory;
use App\Models\InstituteParentCategory;
use App\Models\Posts;
use App\Responses\Admin\AssessmentActionResponse;
use App\Responses\Admin\SendUsersAssessmentActionResponse;
use App\Responses\FieldInspector\CheckAssessmentsFieldInspectorResponse;
use App\Responses\IndexAdminResponse;
use App\Responses\ContactInfoResponse;
use App\Responses\AboutInfoResponse;
use App\Responses\NewsResponse;
use App\Responses\ActivityResponse;
use App\Responses\ScholarshipResponse;
use App\Responses\BannerResponse;
use App\Responses\FileResponse;

use App\Models\Site;
use App\Responses\User\UserCheckAssessmentsResponse;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    use  UserRoleTrait;

    protected $rootView = 'main.admin';

    protected $excepts = [
        'except' => [
            'responseActionUploadImages', 'responseActionGetImages', 'responseActionDeleteImages'
        ]
    ];

    /**
     * @description @ApiMode Only admin and super admin can access and do works
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('api-mode-user-management:super_admin,admin', $this->excepts);
    }


    /**
     * @Responses and Actions api|web
     */
    /**
     * @param Request $request
     * @return IndexAdminResponse
     */
    public function index(Request $request): IndexAdminResponse
    {
        return new IndexAdminResponse($this->options($request));
    }

    /**
     * @Responses and Actions api|web
     */


    /**
     * @Responses and Actions api only
     * @SiteInfo Manger
     */
    public function responseActionManageSiteInfo(Request $request)
    {
        $this->validate($request, [
            'site_name' => 'required|max:191',
            'website_logo' => 'mimes:png,jpg,jpeg,svg,gif|max:2000',
            'favorite_icon' => 'mimes:png,jpg,jpeg,svg,gif|max:2000'
        ]);
        $oldData = Site::where('key', 'site_name')->first();
        if (isset($oldData)) {
            $oldData->value = $request->get('site_name');
            $oldData->save();
            $this->updateFreshVersion();
        }
        if ($request->hasFile('website_logo')) {
            $oldData = Site::where('key', 'website_logo')->first();
            if (isset($oldData)) {
                $file = $request->file('website_logo');
                $filename = md5(date('Y-m-d') . microtime()) . time() . '_site_info_.' . $file->getClientOriginalExtension();
                $location = public_path(Site::$uploadPath);
                $file->move($location, $filename);
                if ($oldData->value !== 'logo.png') {
                    Helpers::removeFile(Site::$uploadPath . $oldData->value);
                }
                $oldData->value = $filename;
                $oldData->save();
                //save for email logo same as website logo
                $oldData = Site::where('key', 'email_logo')->first();
                if (isset($oldData)) {
                    $oldData->value = $filename;
                    $oldData->save();
                }
                //save for email logo same as website logo
                $this->updateFreshVersion();
            }
        }
        if ($request->hasFile('favorite_icon')) {
            $oldData = Site::where('key', 'fav_icon')->first();
            if (isset($oldData)) {
                $file = $request->file('favorite_icon');
                $filename = md5(date('Y-m-d') . microtime()) . time() . '_site_info_.' . $file->getClientOriginalExtension();
                $location = public_path(Site::$uploadPath);
                $file->move($location, $filename);
                if ($oldData->value !== 'fav.png') {
                    Helpers::removeFile(Site::$uploadPath . $oldData->value);
                }
                $oldData->value = $filename;
                $oldData->save();
                $this->updateFreshVersion();
            }
        }

        return response()->json(['success' => true, 'data' => 'Saved successfully!']);
    }

    public function updateFreshVersion(): void
    {
        Site::where('key', 'fresh_version')->update(['value' => "?v" . md5(date('y-mm-dd h:i:sa'))]);
    }

    public function getSiteInfo()
    {
        $settings = $this->getSettings();
        $settings['website_logo'] = Site::$uploadPath . $settings['website_logo'];
        $settings['fav_icon'] = Site::$uploadPath . $settings['fav_icon'];
        return response()->json($settings);
    }

    /**
     * @Responses
     * @SiteInfo Manger
     * /
     *
     * /**
     * @Responses BannerAction
     */
    public function insertBanner(Request $request)
    {
        $this->validate($request, [
            'image' => 'max:3000|mimes:jpeg,png,jpg,gif',
        ]);
        return new BannerResponse("insert");
    }

    public function updateBanner(Request $request)
    {
        $this->validate($request, [
            'image' => 'max:3000|mimes:jpeg,png,jpg,gif',
        ]);
        return new BannerResponse('update');
    }

    public function deleteBanner()
    {
        return new BannerResponse('delete');
    }
    /**
     * @Responses BannerAction
     */
    /**
     * @Responses FileAction
     */
    public function insertFile(Request $request)
    {
        $this->validate($request, [
            'fileName' => 'required|string|max:191',
            'file' => 'required|max:10000|mimes:pdf,doc,docx,xlsx,pptx',
        ]);
        return new FileResponse("insert");
    }

    public function updateFile(Request $request)
    {
        $this->validate($request, [
            'fileName' => 'required|string|max:191',
            'file' => 'max:10000|mimes:pdf,doc,docx,xlsx,pptx',
        ]);
        return new FileResponse('update');
    }

    public function deleteFile()
    {
        return new FileResponse('delete');
    }
    /**
     * @Responses FileAction
     */

    /**
     * @Responses ContactAction
     */

    public function getContactInfo(Request $request)
    {
        return new ContactInfoResponse('get');
    }

    public function manageContactInfo(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'address' => 'required|string',
        ]);
        return new ContactInfoResponse('manage');
    }
    /**
     * @Responses ContactAction
     */
    /**
     * @Responses NewsAction
     */
    public function insertNews(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'required|max:3000|mimes:jpeg,png,jpg,gif',
            'description' => 'required|string',
        ]);
        return new NewsResponse("insert");
    }

    public function updateNews(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'max:3000|mimes:jpeg,png,jpg,gif',
            'description' => 'required|string',
        ]);
        return new NewsResponse('update');
    }

    public function deleteNews()
    {
        return new NewsResponse('delete');
    }
    /**
     * @Responses NewsAction
     */
    /**
     * @Responses ActivityAction
     */

    public function insertActivity(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'required|max:3000|mimes:jpeg,png,jpg,gif',
            'activity_date' => 'required',
            'description' => 'required|string',
        ]);
        return new ActivityResponse('insert');
    }

    public function updateActivity(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'max:3000|mimes:jpeg,png,jpg,gif',
            'activity_date' => 'required',
            'description' => 'required|string',
        ]);
        return new ActivityResponse('update');
    }

    public function deleteActivity()
    {
        return new ActivityResponse('delete');
    }
    /**
     * @Responses ActivityAction
     */
    /**
     * @Responses ScholarshipAction
     */
    public function insertScholarship(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'required|max:3000|mimes:jpeg,png,jpg,gif',
            'deadline' => 'required',
            'place' => 'required',
            'scholarship_type' => 'required|string',
            'description' => 'required|string',
        ]);
        return new ScholarshipResponse('insert');
    }

    public function updateScholarship(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'image' => 'max:3000|mimes:jpeg,png,jpg,gif',
            'scholarship_deadline' => 'required',
            'place' => 'required',
            'scholarship_type' => 'required|string',
            'description' => 'required|string',
        ]);
        return new ScholarshipResponse('update');
    }

    public function deleteScholarship()
    {
        return new ScholarshipResponse('delete');
    }
    /**
     * @Responses ScholarshipAction
     */
    /**
     * @Responses AboutAction
     */

    public function getAboutInfo(Request $request)
    {
        return new AboutInfoResponse('get');
    }

    public function manageAboutInfo(Request $request)
    {
        $this->validate($request, [
            'description' => 'string',
        ]);
        return new AboutInfoResponse('manage');
    }

    /**
     * @Responses AboutAction
     *     /**
     *
     * /**
     * @Responses PostsImageAction
     */

    public function responseActionUploadImages(Request $request): JsonResponse
    {
        return (new PostImageController())->upload($request);
    }

    public function responseActionGetImages(Request $request)
    {
        return (new PostImageController())->fetch($request);
    }

    public function responseActionDeleteImages(Request $request)
    {
        return (new PostImageController())->delete($request);
    }

    /**
     * @Responses PostsImageAction
     */

    /**
     * @Responses InstituteCategoryAction
     */
    public function responseActionListInstituteCategories(Request $request)
    {
        $data = InstituteCategory::orderBy('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function responseActionCreateInstituteCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'have_parent' => 'required|max:10',
        ]);
        $oldData = InstituteCategory::where('name', $request->get('name'))->first();
        if (isset($oldData)) {
            return response()->json(['errors' => ['name' => ['Your entered institute category name already exists in our system.']]], 422);
        }

        if ($request->get('have_parent')) {
            $this->validate($request, [
                'parent_categories' => 'required',
            ]);
        }

        $data = InstituteCategory::CreateItem($request->get('name'), $request->get('have_parent') ? 'yes' : 'no');
        if ($request->get('have_parent')) {
            $parents = InstituteCategory::whereIn('id', array_column($request->get('parent_categories'), 'id'))->where('have_parent', 'no')->get();
            foreach ($parents as $item) {
                $data->instituteParentCategories()->save(new InstituteParentCategory([
                    'parent_id' => $item->id,
                ]));
            }
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function responseActionUpdateInstituteCategory(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'have_parent' => 'required|max:10',
        ]);

        if ($request->get('have_parent')) {
            $this->validate($request, [
                'parent_categories' => 'required',
            ]);
        }

        $name = $request->get('name');
        $have_parent = $request->get('have_parent') ? 'yes' : 'no';
        $oldData = InstituteCategory::find($id);
        if (isset($oldData) && $oldData->name !== $name) {
            $exits = InstituteCategory::where('name', $name)->where('id', '!=', $id)->first();
            if (isset($exits)) {
                return response()->json(['errors' => ['category_name' => ['Your entered category name already exists in our system.']]], 422);
            }
        }
        if (isset($oldData)) {
            $exits_parent = InstituteParentCategory::where('parent_id', $id)->get();
            if ($oldData->have_parent === 'no' && $have_parent === 'yes' && count($exits_parent) > 0) {
                return response()->json(['errors' => ['have_parent' => ['You cannot change the category to Have Parent due it is already have child.']]], 422);
            }
        }
        $oldData->instituteParentCategories()->delete();
        $saved = InstituteCategory::UpdateItem($id, $name, $have_parent);
        if ($saved && $request->get('have_parent')) {
            $parents = InstituteCategory::whereIn('id', array_column($request->get('parent_categories'), 'id'))->where('have_parent', 'no')->get();
            foreach ($parents as $item) {
                $saved->instituteParentCategories()->save(new InstituteParentCategory([
                    'parent_id' => $item->id,
                ]));
            }
        }
        return response()->json(['success' => $saved]);
    }

    public function responseActionDeleteInstituteCategory(Request $request, $id)
    {
        $deleted = InstituteCategory::DeleteItem($id);
        return response()->json(['success' => $deleted]);
    }

    /**
     * @Responses @EndInstituteCategoryAction
     */

    /**
     * @Response @AssessmentAction
     *
     */
    public function responseActionCreateAsessment(Request $request)
    {
        $this->validate($request, [
            'assessment' => 'required',
            'sections' => 'required',
        ]);

        return new AssessmentActionResponse('create');
    }

    public function responseActionFecthAsessment(Request $request, $id)
    {
        return new AssessmentActionResponse('fetch');
    }

    public function responseActionUpdateAsessment(Request $request, $id)
    {
        $this->validate($request, [
            'assessment' => 'required',
            'sections' => 'required',
        ]);
        return new AssessmentActionResponse('update');
    }

    public function responseActionUpdateStatusAsessment(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        return new AssessmentActionResponse('update-status');
    }

    public function responseActionDeleteAsessment(Request $request, $id)
    {
        return new AssessmentActionResponse('delete');
    }

    public function responseActionFetchSendAsessmentUsers(Request $request)
    {
        return new SendUsersAssessmentActionResponse('fetch-send');
    }

    public function responseActionSendAsessmentUsers(Request $request, $type)
    {
        return new SendUsersAssessmentActionResponse('post-send');
    }
    /**
     * @Response @EndAssessmentAction
     *
     */


    /**
     * @todo reset user password
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function responseActionSendUserResetPasswordLink(Request $request, $id): JsonResponse
    {
        $this->validate($request, [
            'id' => ['required']
        ]);
        $user = User::find($id);
        if (isset($user) && (int)$id === (int)$request->get('id')) {

            if ($user->status !== 'approved') {
                return response()->json(['success' => false, 'message' => 'Only approved user can send reset password link!']);
            }
            //@send reset password link
            (new ForgotPasswordController())->sendResetLinkEmail($request);
            //@send reset password link
            return response()->json(['success' => true, 'message' => 'The user reset password link has been sent!']);
        }
        return response()->json(['success' => false, 'message' => 'The given user id does not exists!', $request->get('id')]);
    }


    /**
     * @todo delete user
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function responseActionDeleteUser(Request $request, $id): JsonResponse
    {
        $this->validate($request, [
            'id' => ['required']
        ]);
        $user = User::find($id);
        if (isset($user) && (int)$id === (int)$request->get('id') && $user->destroyInfo()) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'The user account has been deleted!']);
        }
        return response()->json(['success' => false, 'message' => 'The given user id does not exists!', $request->get('id')]);
    }

    /**
     * * @todo add user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseActionAddUser(Request $request): JsonResponse
    {
        return response()->json((new RegisterController())->register($request));
    }

    /**
     * * @todo update user statuses
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function responseActionChangeUserStatus(Request $request, $id): JsonResponse
    {
        $this->validate($request, [
            'status' => ['required', 'string', 'max:25']
        ]);
        $user = User::find($id);
        $oldUserInfo = clone $user;
        $status = $request->get('status');
        if (isset($user) && $user->setStatus($request->get('status'))) {
            //check if user status change from disabled to approved
            $status = ($status === 'approved' && $oldUserInfo->status === 'disabled') ? 'enabled' : $status;
            $this->dispatch(new SendUserChangeStatus($user));
            return response()->json(['success' => true, 'message' => "The user account has been $status!"]);
        }
        return response()->json(['success' => false, 'message' => 'The given user id does not exists!']);
    }

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
        if ($type === 'users_checker') {
            $fields = ['users.id', 'users.status', 'users.name', 'users.last_name', 'users.email', 'users.created_at'];
            $request->request->add(['fields' => $fields]);
            $data = User::select(array_merge(['users.image'], $fields))->join('user_types', 'user_types.user_id', 'users.id')
                ->where('user_types.type_user_id', $this->getTypeUserId('checker'))
                ->whereIn('user_types.type_user_id', User::getNonAdminUserIds());
            $data->where(function ($query) use ($request, $text) {
                foreach ($request->fields as $k => $f) {
                    if ($f === 'users.created_at') {
                        if (Helpers::isEngText($text)) {
                            $query->orWhere($f, 'LIKE', "%{$text}%");
                        } else {
                            continue;
                        }
                    }
                    $query->orWhere($f, 'LIKE', "%{$text}%");
                }
                $query->orWhere(
                    DB::raw("CONCAT (users.name, ' ', users.last_name)"),
                    'LIKE',
                    "%{$text}%"
                );
            });
            $data = $data->orderBy('users.created_at', 'desc')->paginate($paginateLimit);
            $data->map(function ($d) {
                $d->image = "/assets/images/user_profiles/{$d->image}";
                $d->statusColor = $d->status === 'approved' ? '#00bfa5' : ($d->status === 'disabled' ? '#d50000' : '');
                return $d;
            });
        } else if ($type === 'users_field_inspector') {
            $fields = ['users.id', 'users.status', 'users.name', 'users.last_name', 'users.email', 'users.created_at'];
            $request->request->add(['fields' => $fields]);
            $data = User::select(array_merge(['users.image'], $fields))->join('user_types', 'user_types.user_id', 'users.id')
                ->where('user_types.type_user_id', $this->getTypeUserId('field_inspector'))
                ->whereIn('user_types.type_user_id', User::getNonAdminUserIds());
            $data->where(function ($query) use ($request, $text) {
                foreach ($request->fields as $k => $f) {
                    if ($f === 'users.created_at') {
                        if (Helpers::isEngText($text)) {
                            $query->orWhere($f, 'LIKE', "%{$text}%");
                        } else {
                            continue;
                        }
                    }
                    $query->orWhere($f, 'LIKE', "%{$text}%");
                }
                $query->orWhere(
                    DB::raw("CONCAT (users.name, ' ', users.last_name)"),
                    'LIKE',
                    "%{$text}%"
                );
            });
            $data = $data->orderBy('users.created_at', 'desc')->paginate($paginateLimit);
            $data->map(function ($d) {
                $d->image = "/assets/images/user_profiles/{$d->image}";
                $d->statusColor = $d->status === 'approved' ? '#00bfa5' : ($d->status === 'disabled' ? '#d50000' : '');
                return $d;
            });
        } else if ($type === 'users_institute') {
            $fields = ['users.id', 'users.status', 'user_profiles.institute_name', 'user_profiles.short_institute_name', 'users.email', 'users.created_at'];
            $request->request->add(['fields' => $fields]);
            $data = User::select(array_merge(['users.image', 'user_profiles.institute_category_id'], $fields))->join('user_types', 'user_types.user_id', 'users.id')
                ->join('user_profiles', 'user_profiles.user_id', 'users.id')
                ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
                ->whereIn('user_types.type_user_id', User::getNonAdminUserIds());
            $data->where(function ($query) use ($request, $text) {
                foreach ($request->fields as $k => $f) {
                    if ($f === 'users.created_at') {
                        if (Helpers::isEngText($text)) {
                            $query->orWhere($f, 'LIKE', "%{$text}%");
                        } else {
                            continue;
                        }
                    }
                    $query->orWhere($f, 'LIKE', "%{$text}%");
                }
                $query->orWhere(
                    DB::raw("CONCAT (user_profiles.institute_name, ' ', user_profiles.short_institute_name)"),
                    'LIKE',
                    "%{$text}%"
                );
            });
            $data = $data->orderBy('users.created_at', 'desc')->paginate($paginateLimit);
            $data->map(function ($d) {
                $category = InstituteCategory::find($d->institute_category_id);
                $d->category = isset($category) ? $category->name : 'No data.';
                $d->image = "/assets/images/user_profiles/{$d->image}";
                $d->statusColor = $d->status === 'approved' ? '#00bfa5' : ($d->status === 'disabled' ? '#d50000' : '');
                return $d;
            });
        } else if ($type === 'institute_category') {
            $fields = ['id', 'name', 'have_parent', 'created_at', 'updated_at'];
            $request->request->add(['fields' => $fields]);
            $data = InstituteCategory::select($fields);
            $data->where(function ($query) use ($request, $text) {
                foreach ($request->fields as $k => $f) {
                    if ($f === 'created_at' || $f === 'updated_at') {
                        if (Helpers::isEngText($text)) {
                            $query->orWhere($f, 'LIKE', "%{$text}%");
                        } else {
                            continue;
                        }
                    }
                    $query->orWhere($f, 'LIKE', "%{$text}%");
                }
            });
            $data = $data->orderBy('created_at', 'desc')->paginate($paginateLimit);
            $data->map(function ($d) {
                $d->have_parent = $d->have_parent === 'yes';
                $d->parent_categories = $d->selectedParentCategories();
                unset($d->instituteParentCategories);
                return $d;
            });
        } else if ($type === 'assessments') {
            $data = (new AssessmentActionResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'news') {
            $data = (new NewsResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'activity') {
            $data = (new ActivityResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'scholarship') {
            $data = (new ScholarshipResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'banner') {
            $data = (new BannerResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'file') {
            $data = (new FileResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'check_assessments') {
            $data = (new UserCheckAssessmentsResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        } else if ($type === 'check_assessments_field_inspector') {
            $data = (new CheckAssessmentsFieldInspectorResponse('get', ['text' => $text, 'limit' => $paginateLimit]))->get($request);
        }

        if (count($data) > 0) {
            $data->appends(['limit' => $request->exists('limit'), 'q' => $request->get('q')]);
        }
        return response()->json(['data' => $data]);
    }

    /*** @postManagePostsStatus * */
    public function responseActionManagePostsStatus(Request $request)
    {
        $data = $this->validate($request, [
            'id' => 'required|max:191',
            'changeStatusTo' => 'required|max:191',
        ]);
        $info = Posts::find($data['id']);
        if (isset($info)) {
            if ($info->status === 'pending' && $data['changeStatusTo'] === 'approve') {
                $info->status = 'open';
            } else if ($info->status === 'open' && $data['changeStatusTo'] === 'close') {
                $info->status = 'close';
            } else if ($info->status === 'close' && $data['changeStatusTo'] === 'open') {
                //check if post type is event or scholarship
                if ($info->type === 'event') {//set new dates for expired post
                    $this->validate($request, [
                        'start_event' => 'required',
                        'end_event' => 'required',
                    ]);
                    $info->start_date = Helpers::toFormatDateString($request->get('start_event'), 'Y-m-d H:i:s');
                    $info->deadline = Helpers::toFormatDateString($request->get('end_event'), 'Y-m-d H:i:s');
                } else if ($info->type === 'scholarship') {//set new date for expired post
                    $this->validate($request, [
                        'scholarship_deadline' => 'required',
                    ]);
                    $info->deadline = Helpers::toFormatDateString($request->get('scholarship_deadline'), 'Y-m-d H:i:s');
                }
                $info->status = 'open';
            }
            $info->save();
            return response()->json(['success' => true, 'msg' => 'The post status was successfully changed!']);
        }
        return response()->json(['success' => false, 'msg' => 'Failed to change the post status!']);
    }
    /*** @postManagePostsStatus * */
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

    /**
     * @Helper helper functions
     */
}
