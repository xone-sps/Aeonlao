<?php

namespace App\Models;

use App\Traits\UserRoleTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Helpers\Helpers;

class Posts extends Model
{
    use UserRoleTrait;
    protected $dates = ['start_date', 'deadline'];
    public static $uploadPath = '/assets/images/posts/';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getPosts($type, $limit)
    {
        $mLimit = Helpers::isNumber($limit) ? $limit : 3;

        if ($type === 'institute') {
            $fields = ['users.id', 'user_profiles.institute_name', 'user_profiles.short_institute_name', 'user_profiles.public_email', 'user_profiles.updated_at', 'user_profiles.founded', 'user_profiles.phone_number'];

            $data = User::select(array_merge(['users.image', 'user_profiles.institute_category_id', 'user_profiles.parent_institute_category_id'], $fields))->join('user_types', 'user_types.user_id', 'users.id')
                ->join('user_profiles', 'user_profiles.user_id', 'users.id')
                ->where('user_types.type_user_id', (new self)->getTypeUserId('institute'))
                ->where('status', 'approved')
                ->limit($mLimit)->orderBy('id', 'desc')->get();

            $data->map(function ($d) {
                $category = (new self())->getInstituteCategory($d);
                $d->category = $category ?? ['id' => '', 'name' => 'No data.'];
                $d->image = "/assets/images/user_profiles/{$d->image}";
                $d->formatted_founded = !empty($d->founded) ? Helpers::toFormatDateString($d->founded, 'M d Y') : 'N/A';
                return $d;
            });

            return $data;
        }

        $posts = self::where('type', $type)->limit($mLimit)->orderBy('id', 'desc')->where('status', 'open')->get();
        $posts->map(function ($post) {
            $post->author = $post->user->name;
            $post->formatted_updated_at = Helpers::toFormatDateString($post->updated_at, 'M d, Y');
            $post->formatted_start_date = Helpers::toFormatDateString($post->start_date, 'M d Y');
            $post->formatted_end_date = Helpers::toFormatDateString($post->deadline, 'M d Y');
            $post->formatted_deadline = Helpers::toFormatDateString($post->deadline, 'H:i A, M d Y');
            $post->formatted_start_time = Helpers::toFormatDateString($post->start_date, 'H:i A');
            $post->formatted_end_time = Helpers::toFormatDateString($post->deadline, 'H:i A');

            $post->image = url('/assets/images/posts') . '/' . $post->image;
            unset($post->user);
            $post->setRelations([]);
            return $post;
        });
        return $posts;
    }

    public static function IncreaseViews($id): void
    {
        $c = self::find($id);
        if (isset($c)) {
            $c->timestamps = false;
            ++$c->view;
            $c->save();
        }
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
