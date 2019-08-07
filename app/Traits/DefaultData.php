<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 3/7/2019
 * Time: 4:29 PM
 */

namespace App\Traits;


use App\Models\Banner;
use App\Models\InstituteCategory;
use App\Models\Posts;
use App\Responses\Home\PostsResponse;
use App\Models\Site;
use Illuminate\Http\Request;
use App\Models\File;

trait DefaultData
{
    public function getDefaultData(Request $request): array
    {
        $request->request->set('limit', 2);
        return [
            's' => $this->getSettings(),
            'banners' => json_encode(Banner::getBanners(8)),
            'latest_news' => json_encode(Posts::getPosts('news', 3)),
            'latest_scholarship' => json_encode(Posts::getPosts('scholarship', 3)),
            'latest_activity' => json_encode(Posts::getPosts('activity', 3)),
            'latest_institutes' => json_encode(Posts::getPosts('institute', 3)),

            'institutes' => json_encode((new PostsResponse([], 'institutes'))->postsPaginator($request)),
            'news' => json_encode((new PostsResponse([], 'news'))->postsPaginator($request)),
            'activities' => json_encode((new PostsResponse([], 'activities'))->postsPaginator($request)),
            'scholarships' => json_encode((new PostsResponse([], 'scholarships'))->postsPaginator($request)),
            'instituteCategories' => json_encode(InstituteCategory::select('id', 'name', 'have_parent')->orderBy('id', 'desc')->get()),
            'instituteCategoriesHome' => json_encode(InstituteCategory::select('id', 'name')->where('have_parent', 'no')->orderBy('id', 'desc')->get()),
            'files' =>json_encode(File::select('id','fileName','filePath')->orderBy('id','desc')->limit(20)->get()),
        ];
    }

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
}
