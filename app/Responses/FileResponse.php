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
use App\Models\File;
use Image;

class FileResponse implements Responsable
{

    protected $actionType = 'get';
    protected $options = [];
    protected $uploadPath = '/assets/files/';

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
        $fields = ['id', 'fileName', 'filePath', 'created_at', 'updated_at'];
        $request->request->add(['fields' => $fields]);
        $text = $this->options['text'];
        $paginateLimit = $this->options['limit'];
        $data = File::select($fields);
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
        $data = $data->orderBy('id', 'desc')->paginate($paginateLimit);
        $data->map(function ($d) {
            $fileExt = pathinfo($d->filePath, PATHINFO_EXTENSION);
            $d->realfilePath = $d->filePath;
            $d->filePath = explode(md5('^'), $d->filePath)[0] . '.' . $fileExt;
            $d->folderPath = '/assets/files/';
            return $d;
        });
        return $data;
    }

    public function toResponse($request)
    {
        $data = [];
        if (Helpers::isAjax($request)) {
            if ($this->actionType === 'insert') {
                $file = $request->file('file');
                $fileExt = strtolower($file->getClientOriginalExtension());
                $fileOriginalName = Helpers::subFileName($file->getClientOriginalName()) . md5('^');
                $file_filename = $fileOriginalName . md5(date('Y-m-d H:i:s') . microtime()) . time() . '_posts.' . $fileExt;
                $location = public_path($this->uploadPath);
                $file->move($location, $file_filename);
                $info = new File();
                $info->fileName = $request->get('fileName');
                $info->filePath = $file_filename;
                $info->save();
                $data = $info;
            } else if ($this->actionType === 'update') {
                $info = File::find($request->id);
                $file_filename = null;
                if (isset($info)) {
                    if ($request->hasFile('file')) {
                        $filePath = $request->file('file');
                        $fileExt = strtolower($filePath->getClientOriginalExtension());
                        $fileOriginalName = Helpers::subFileName($filePath->getClientOriginalName()) . md5('^');
                        $file_filename = $fileOriginalName . md5(date('Y-m-d H:i:s') . microtime()) . time() . '_upload.' . $fileExt;
                        $location = public_path($this->uploadPath);
                        $filePath->move($location, $file_filename);
                        Helpers::removeFile($this->uploadPath . $info->filePath);
                    }
                    $info->fileName = $request->get('fileName');
                    $info->filePath = $file_filename ?? $info->filePath;
                    $info->save();
                    $data = $info;
                }
            } else if ($this->actionType === 'delete') {
                $info = File::find($request->id);
                if (isset($info)) {
                    Helpers::removeFile($this->uploadPath . $info->filePath);
                    $info->delete();
                    $data = $info;
                }
            }
            return response()->json(['success' => true, "data" => $data]);
        }
    }
}
