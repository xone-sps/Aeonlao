<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\Helpers;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Validator, Image;

class PostImageController extends Controller
{
    protected $typeAction = '';
    protected $uploadPath = '/assets/images/posts/';
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
        $this->middleware('api-mode-user-management:super_admin,admin,user', $this->excepts);
    }

    public function fetch(Request $request)
    {
        return PostImage::Images();
    }


    public function upload(Request $request)
    {
        $passed = 0;
        $files = $request->file('imgs');
        $count = count($files);
        if ($count > 0) {
            $rules = array('img' => 'required|mimes:png,jpg,jpeg,svg,gif|max:5000');
            foreach ($files as $image_file) {
                $validator = Validator::make(array('img' => $image_file), $rules);
                if ($validator->passes()) {
                    $file = $image_file;
                    $fileExt = strtolower($file->getClientOriginalExtension());
                    $imgOriginalName = Helpers::subFileName($file->getClientOriginalName()) . md5('^');
                    $img_filename = $imgOriginalName . md5(date('Y-m-d H:i:s') . microtime()) . time() . '_posts.' . $fileExt;
                    if ($fileExt === 'gif' || $fileExt === 'svg') {
                        $location = public_path($this->uploadPath);
                        $file->move($location, $img_filename);
                    } else {
                        $img = Image::make($file);
                        $location = public_path($this->uploadPath . $img_filename);
                        $img->save($location)->destroy();
                    }
                    //saved all of valid images
                    PostImage::create(['name' => $img_filename]);
                    $passed++;
                }
            }
            if ($passed === $count) {
                return response()->json(['success' => true, 'msg' => 'All images was successfully uploaded.']);
            }

            return response()->json(['success' => false, 'msg' => 'Unable to upload some image.']);
        }
        return response()->json(['success' => false, 'msg' => 'Unable to upload images.']);
    }

    public function delete(Request $request)
    {
        $images = $request->get('imgs');
        $passed = 0;
        $count = count($images);
        foreach ($images as $image_id) {
            $img = PostImage::find($image_id);
            if (isset($img) && !$this->ImageInUses($img->id)) {
                Helpers::removeFile($this->uploadPath . $img->name);
                PostImage::where('id', $img->id)->delete();
                $passed++;
            }
        }
        if ($passed === $count) {
            return response()->json(['success' => true, 'msg' => 'All images was successfully deleted.']);
        }

        return response()->json(['success' => false, 'msg' => 'Unable to delete some image because it\' related to some posts.']);
    }

    public function ImageInUses($image_id)
    {
        $jobs = PostImage::all();
        $ch = false;
        if (count($jobs) <= 0) {
            return $ch;
        }
        foreach ($jobs as $key => $job) {
            $images = self::getImagesFromText($job->content);
            if (count($images) > 0) {
                foreach ($images as $k => $img) {
                    if ($img->id === $image_id) {
                        $ch = true;
                        break;
                    }
                }
            }
            if ($ch) {
                break;
            }
        }
        return $ch;
    }

    public static function getImagesFromText($str = '')
    {
        $regexFindImageTag = '/<img [^>]*>/i';
        $matches = [];
        $imgs = [];
        $img = null;
        preg_match_all($regexFindImageTag, $str, $matches);
        //libxml_use_internal_errors(true);
        $stringHtml = implode(' ', $matches[0]);
        if ($stringHtml === '') {
            return $imgs;
        }
        $doc = new \DOMDocument();
        $doc->loadHTML($stringHtml);
        $imageTags = $doc->getElementsByTagName('img');
        foreach ($imageTags as $tag) {
            $img = $tag->getAttribute('src');
            $img = explode('/', $img)[substr_count($img, '/') + 1 - 1];
            $img = PostImage::where('name', $img)->first();
            if ($img) {
                $imgs[] = $img;
            }
        }
        return $imgs;
    }//end getImages
}
