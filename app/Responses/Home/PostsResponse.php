<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/3/2019
 * Time: 11:33 AM
 */

namespace App\Responses\Home;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\InstituteCategory;
use App\Models\InstituteParentCategory;
use App\Models\Posts;
use App\Traits\DefaultData;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class PostsResponse implements Responsable
{
    use DefaultData, UserRoleTrait;

    protected $options = [];
    protected $type = '';

    public function __construct($options, $type)
    {
        $this->options = $options;
        $this->type = $type;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response | mixed
     */
    public function toResponse($request)
    {
        $post_type_name = ucfirst($this->type);
        if (Helpers::isAjax($request)) {
            return $this->postsPaginator($request);
        }

        $type = $this->getPostsType($this->type);
        if (empty($type)) {
            return redirect('/');
        }
        return view("{$this->options['rootView']}.posts",
            array_merge(['post_type_name' => $post_type_name], $this->getDefaultData($request)));
    }

    public function postsPaginator($request): array
    {
        $type = $this->getPostsType($this->type);
        $paginateLimit = ($request->exists('limit') && !empty($request->get('limit'))) ? $request->get('limit') : 6;
        $paginateLimit = Helpers::isNumber($paginateLimit) ? $paginateLimit : 6;
        $text = $request->get('q');

        //@for institutes only
        if ($type === 'institute') {
            return $this->getInstitutes($request, $text, $paginateLimit);
        }
        //@for institutes only


        $fields = ['id', 'title', 'type', 'image', 'place', 'scholarship_type', 'start_date', 'description', 'status', 'deadline', 'updated_at', 'user_id',];
        $request->request->add(['fields' => $fields]);
        $data = Posts::select($fields)->where('type', $type)->whereIn('status', ['open', 'close']);
        /**@Query */
        $data->where(function ($query) use ($request, $text) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'start_date' || $f === 'deadline' || $f === 'updated_at') {
                    if (Helpers::isEngText($text)) {
                        $query->orWhere($f, 'LIKE', "%{$text}%");
                    } else {
                        continue;
                    }
                }
                $query->orWhere($f, 'LIKE', "%{$text}%");
            }
        });
        $data = $data->orderBy('status', 'desc')->paginate($paginateLimit);
        $this->mapPosts($data);
        $data->appends(['limit' => $paginateLimit, 'q' => $text]);
        /**@Query */
        /**@mostViewsPosts */
        $mostViewsPosts = Posts::select($fields)->where('type', $type)->where('status', 'open')->limit(5)->orderBy('view', 'desc')->get();
        $this->mapPosts($mostViewsPosts);
        /**@mostViewsPosts */
        return ['posts' => $data, 'total_count' => 0, 'mostViews' => $mostViewsPosts];
    }

    public function getPostsType($title)
    {
        $types = [
            'activities' => 'activity',
            'news' => 'news',
            'scholarships' => 'scholarship',
            'institutes' => 'institute',
        ];
        return $types[$title] ?? '';
    }

    public function mapPosts($data): void
    {
        $data->map(function ($d) {
            $d->author = $d->user->name;
            $d->author_image = $d->user->userInfo['imagePath'] . $d->user->userInfo['preThumb'] . $d->user->image;
            $d->image = Posts::$uploadPath . $d->image;
            $d->post_updated = Helpers::toFormatDateString($d->updated_at, 'H:i A, j M Y');
            $d->isClosed = $d->status === 'close';

            if ($d->type === 'activity') {
                $d->formatted_start_date = Helpers::toFormatDateString($d->updated_at, 'H:i A, j M Y');
            }

            if ($d->type === 'scholarship') {
                $d->formatted_deadline = date('H:i A, M d Y', strtotime($d->deadline));
            }
            //remove relationship
            unset($d->user, $d->type);
            $d->setRelations([]);
            unset($d->user_id);
            //remove relationship
            return $d;
        });
    }

    public function getInstitutes($request, $text, $paginateLimit): array
    {
        $category_id = $request->get('category_id');
        $fields = ['users.id', 'user_profiles.institute_name', 'user_profiles.short_institute_name', 'user_profiles.public_email', 'user_profiles.updated_at', 'user_profiles.founded', 'user_profiles.phone_number'];
        $request->request->add(['fields' => $fields]);
        $data = User::select(array_merge(['users.image', 'user_profiles.institute_category_id', 'user_profiles.parent_institute_category_id'], $fields))->join('user_types', 'user_types.user_id', 'users.id')
            ->join('user_profiles', 'user_profiles.user_id', 'users.id')
            ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
            ->where('status', 'approved');
        $total_institute_count = clone $data;
        $total_institute_count = $total_institute_count->get()->count();

        if (!empty($category_id)) {
            $data->where('user_profiles.parent_institute_category_id', $category_id);
            $data->orWhere('user_profiles.institute_category_id', $category_id);
        }

        /**@Query */
        $data->where(function ($query) use ($request, $text) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'users.updated_at') {
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
        $data = $data->orderBy('users.updated_at', 'desc')->paginate($paginateLimit);
        $data->map(function ($d) {
            $category = $this->getInstituteCategory($d);
            $d->category = $category ?? ['id' => '', 'name' => 'No data.'];
            $d->image = "/assets/images/user_profiles/{$d->image}";
            $d->formatted_founded = !empty($d->founded) ? Helpers::toFormatDateString($d->founded, 'M d Y') : 'N/A';
            return $d;
        });
        /**@Query */
        $data->appends(['limit' => $paginateLimit, 'q' => $text]);
        return ['posts' => $data, 'total_count' => $total_institute_count, 'mostViews' => [], 'comingEvents' => []];
    }

    public function getInstituteCategory($user_profile)
    {

        if (isset($user_profile->parent_institute_category_id)) {
            $category = InstituteCategory::find($user_profile->parent_institute_category_id);
            return $category;
        }

        $category = InstituteCategory::find($user_profile->institute_category_id);
        return $category;
    }
}
