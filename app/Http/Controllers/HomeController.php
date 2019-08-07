<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactInfo;
use App\Models\InstituteCategory;
use App\Responses\Home\PostsResponse;
use App\Responses\Home\SinglePostsResponse;
use App\Traits\DefaultData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Helpers\Helpers;
use App\Models\Banner;
use App\Models\File;
use App\Models\Site;
use App\Models\Posts;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use DefaultData;
    public $rootView = 'main.general';

    /**
     * @Responses and Actions api|web
     */

    /***@SinglePostsResponse *
     * @param Request $request
     * @param $type
     * @param $id
     * @return SinglePostsResponse
     */

    public function responsePostsSingle(Request $request, $type, $id): SinglePostsResponse
    {
        return new SinglePostsResponse(['rootView' => $this->rootView], $type, $id);
    }
    /***@SinglePostsResponse * */


    /***@PostsResponse *
     * @param Request $request
     * @param $type
     * @return PostsResponse
     */
    public function responsePosts(Request $request, $type): PostsResponse
    {
        return new PostsResponse(['rootView' => $this->rootView], $type);
    }
    /***@PostsResponse * */

    /**
     * @Responses and Actions api|web
     */
    /**
     * @TODO home guest or all users  can view
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (Helpers::isAjax($request)) {
            return response()->json(['data' => $this->getHomeData($request)]);
        }
        return view((string)$this->rootView, $this->getDefaultData($request));
    }

    /***@Get Home Data
     * @param $request
     * @return array
     */
    public function getHomeData($request): array
    {
        $data = [];
        $data['banners'] = Banner::getBanners(8);
        $data['latest_news'] = Posts::getPosts('news', 5);
        $data['latest_scholarship'] = Posts::getPosts('scholarship', 3);
        $data['latest_activity'] = Posts::getPosts('activity', 3);
        $data['mostViewScholarship'] = Posts::where('type', 'scholarship')->where('status', 'open')->orderBy('view', 'desc')->first();
        $data['instituteCategories'] = InstituteCategory::select('id', 'name', 'have_parent')->orderBy('id', 'desc')->get();
        $data['instituteCategoriesHome'] = InstituteCategory::select('id', 'name')->where('have_parent', 'no')->orderBy('id', 'desc')->get();
        $data['latest_institutes'] = Posts::getPosts('institute', 3);
        $data['files'] = File::select('id','fileName','filePath')->orderBy('id','desc')->limit(20)->get();

        if ($data['mostViewScholarship']) {//set image
            $data['mostViewScholarship']->image = Posts::$uploadPath . $data['mostViewScholarship']->image;
            $data['mostViewScholarship']->formatted_deadline = date('H:i A, M d Y', strtotime($data['mostViewScholarship']->deadline));
        }
        return $data;
    }
    /***@Get Home Data */

    /***@POST_CONTACTINFO
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function responsePostContactInfo(Request $request): JsonResponse
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191'],
            'subject' => ['required', 'string', 'max:191'],
            'message' => ['required', 'string'],
        ]);
        $this->dispatch(new SendContactInfo($data));
        return response()->json(['success' => true, 'msg' => 'The contact information was sent!.']);
    }

    /***@POST_CONTACTINFO */
    /**@GET_InstituteParentCategories
     *
     */
    public function getInstituteParentCategories(Request $request, $id)
    {
        $data = InstituteCategory::find($id);
        if (!isset($data)) {
            return response()->json(['data' => []]);
        }
        $data = $data->selectedParentCategories();
        return response()->json(['data' => $data]);
    }
    /**@ENDGET_InstituteParentCategories
     *
     */
}
