<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/11/2019
 * Time: 2:38 PM
 */

namespace App\Responses;

use App\Http\Controllers\Helpers\Helpers;
use Illuminate\Contracts\Support\Responsable;
use App\Models\Banner;
use Image;

class BannerResponse implements Responsable
{

    protected $actionType = 'get';
    protected $options = [];
    protected $uploadPath = '/assets/images/banners/';

    public function __construct($actionType, $options = [])
    {
        $this->options = $options;
        $this->actionType = $actionType;
    }

    /**
     * Create an HTTP response that represents the object.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function get($request)
    {
        $fields = ['id', 'name', 'description', 'order', 'link', 'image', 'created_at', 'updated_at'];
        $request->request->add(['fields' => $fields]);
        $text = $this->options['text'];
        $paginateLimit = $this->options['limit'];
        $data = Banner::select($fields);
        $data->where(function ($query) use ($request, $text) {
            foreach ($request->fields as $k => $f) {
                if ($f === 'created_at' || $f === 'updated_at' || $f === 'start_date') {
                    if (Helpers::isEngText($text)) {
                        $query->orWhere($f, 'LIKE', "%{$text}%");
                    } else {
                        continue;
                    }
                }
                $query->orWhere($f, 'LIKE', "%{$text}%");
            }
        });
        $data = $data->orderBy('order', 'asc')->paginate($paginateLimit);
        $data->map(function ($d) {
            $d->filename = $d->image;
            $d->image = "{$this->uploadPath}{$d->image}";
            return $d;
        });
        return $data;
    }

    public function toResponse($request)
    {
        $data = [];
        if (Helpers::isAjax($request)) {
            if ($this->actionType === 'insert') {
                $file = $request->file('image');
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
                $info = new Banner();
                $info->name = $request->get('name');
                $info->description = $request->get('description');
                $info->order = $request->get('order');
                $info->link = $request->get('link');
                $info->image = $img_filename;
                $info->save();
                $data = $info;
                $data->image = "{$this->uploadPath}{$data->image}";//set image with path
            } else if ($this->actionType === 'update') {
                $info = Banner::find($request->id);
                $img_filename = null;
                if (isset($info)) {
                    if ($request->hasFile('image')) {
                        $file = $request->file('image');
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
                        Helpers::removeFile($this->uploadPath . $info->image);
                    }
                    $info->name = $request->get('name');
                    $info->order = $request->get('order');
                    $info->description = $request->get('description');
                    $info->link = $request->get('link');
                    $info->image = $img_filename ?? $info->image;
                    $info->save();
                    $data = $info;
                    $data->image = "{$this->uploadPath}{$data->image}";//set image with path
                }
            } else if ($this->actionType === 'delete') {
                $info = Banner::find($request->id);
                if (isset($info)) {
                    Helpers::removeFile($this->uploadPath . $info->image);
                    $info->delete();
                    $data = $info;
                }
            }
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false, 'data' => null]);
    }
}
