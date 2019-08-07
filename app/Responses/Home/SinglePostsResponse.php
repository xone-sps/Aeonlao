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
use App\Models\Posts;
use App\Traits\DefaultData;
use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;

class SinglePostsResponse implements Responsable
{
    use DefaultData, UserRoleTrait;

    protected $options = [];
    protected $type = '';
    protected $id;

    public function __construct($options, $type, $id)
    {
        $this->options = $options;
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response | mixed
     */
    public function toResponse($request)
    {
        $type = $this->getPostsType($this->type);

        $post = Posts::where('type', $type)->whereIn('status', ['open', 'close'])->where('id', $this->id)->first();
        //@for institutes only
        if ($type === 'institute') {
            $fields = ['users.id', 'user_profiles.institute_name', 'user_profiles.short_institute_name', 'user_profiles.public_email', 'user_profiles.updated_at', 'user_profiles.founded', 'user_profiles.address', 'user_profiles.about', 'user_profiles.website', 'user_profiles.googlemap', 'user_profiles.facebook', 'user_profiles.phone_number'];
            $post = User::select(array_merge(['users.image', 'user_profiles.institute_category_id', 'user_profiles.parent_institute_category_id'], $fields))
                ->join('user_types', 'user_types.user_id', 'users.id')
                ->join('user_profiles', 'user_profiles.user_id', 'users.id')
                ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
                ->where('status', 'approved')
                ->where('users.id', $this->id)->first();
        }
        //@for institutes only
        $post_type_name = ucfirst($this->type);
        if (Helpers::isAjax($request)) {
            //@for institutes only
            if ($type === 'institute') {

                $fields = ['users.id', 'user_profiles.institute_name', 'user_profiles.short_institute_name', 'user_profiles.public_email', 'user_profiles.updated_at', 'user_profiles.founded', 'user_profiles.address', 'user_profiles.about', 'user_profiles.website', 'user_profiles.googlemap', 'user_profiles.facebook', 'user_profiles.phone_number'];


                if (!isset($post)) {
                    return response()->json(['success' => false, 'msg' => 'The post does not exits!.']);
                }
                #map
                $parent_category = null;
                $category = null;
                if (isset($post->parent_institute_category_id)) {
                    $parent_category = InstituteCategory::find($post->parent_institute_category_id);
                }
                $category = InstituteCategory::find($post->institute_category_id);
                $post->category = $category ?? ['id' => '', 'name' => 'No data.'];
                $post->parent_category = $parent_category ?? ['id' => '', 'name' => ''];
                $post->image = "/assets/images/user_profiles/{$post->image}";
                $post->formatted_updated = Helpers::toFormatDateString($post->updated_at, 'd M Y');
                $post->formatted_founded = !empty($post->founded) ? Helpers::toFormatDateString($post->founded, 'd M Y') : 'N/A';
                #map
                $others = User::select(array_merge(['users.image', 'user_profiles.institute_category_id', 'user_profiles.parent_institute_category_id'], $fields))
                    ->join('user_types', 'user_types.user_id', 'users.id')
                    ->join('user_profiles', 'user_profiles.user_id', 'users.id')
                    ->where('user_types.type_user_id', $this->getTypeUserId('institute'))
                    ->where('status', 'approved')
                    ->limit(3)->where('users.id', '!=', $this->id)->inRandomOrder()->get();

                $others->map(function ($d) {
                    $category = $this->getInstituteCategory($d);
                    $d->category = $category ?? ['id' => '', 'name' => 'No data.'];
                    $d->image = "/assets/images/user_profiles/{$d->image}";
                    $d->formatted_founded = !empty($d->founded) ? Helpers::toFormatDateString($d->founded, 'M d Y') : 'N/A';
                    return $d;
                });

                return ['data' => $post, 'others' => $others];
            }
            //@for institutes only

            if (!isset($post)) {
                return response()->json(['success' => false, 'msg' => 'The post does not exits!.']);
            }
            Posts::IncreaseViews($post->id);
            return $this->postsPaginator($request);
        }
        if (empty($type)) {
            return redirect('/');
        }
        if (!isset($post)) {
            return redirect('/');
        }
        Posts::IncreaseViews($post->id);
        return view("{$this->options['rootView']}.single.single-posts",
            array_merge(['post_type_name' => $post_type_name, 'type' => $this->type, 'post' => $post], $this->getDefaultData($request)));
    }

    public function postsPaginator($request): array
    {
        $type = $this->getPostsType($this->type);

        $fields = ['id', 'title', 'type', 'image', 'description', 'status', 'place', 'start_date', 'deadline', 'updated_at', 'user_id',];
        $data = Posts::select($fields)->where('type', $type)->whereIn('status', ['open', 'close'])->where('id', $this->id)->first();

        $this->mapPost($data);
        $others = Posts::select($fields)->where('type', $type)->whereIn('status', ['open', 'close'])
            ->limit(3)->where('id', '!=', $this->id)->inRandomOrder()->get();
        $this->mapPosts($others);
        return ['data' => $data, 'others' => $others];
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
                $d->formatted_start_date = Helpers::toFormatDateString($d->start_date, 'j M Y');
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

    public function mapPost($data): void
    {

        $data->author = $data->user->name . ' ' . $data->user->last_name;
        $data->author_image = $data->user->userInfo['imagePath'] . $data->user->userInfo['preThumb'] . $data->user->image;
        $data->image = Posts::$uploadPath . $data->image;
        $data->post_updated = Helpers::toFormatDateString($data->updated_at, 'H:i A, j M Y');
        $data->isClosed = $data->status === 'close';
        if ($data->type === 'activity') {
            $data->formatted_start_date = Helpers::toFormatDateString($data->start_date, 'j M Y');
        }
        if ($data->type === 'scholarship') {
            $data->formatted_deadline = date('H:i A, M d Y', strtotime($data->deadline));
        }
        //remove relationship
        unset($data->user, $data->type);
        $data->setRelations([]);
        unset($data->user_id);
        //remove relationship
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
