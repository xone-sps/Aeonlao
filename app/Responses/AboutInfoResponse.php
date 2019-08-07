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
use App\Models\Site;

class AboutInfoResponse implements Responsable
{

    protected $actionType = 'get';

    public function __construct($actionType)
    {
        $this->actionType = $actionType;
    }

    /**
     * Create an HTTP response that represents the object.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            $data = null;
            if ($this->actionType === 'get') {
                $data = Site::selectRaw('value as description')->where('key', 'description')->first();
            }

            if ($this->actionType === 'manage') {
                $info = Site::where('key', 'description')->first();
                if (!isset($info)) {
                    $info = new Site();
                    $info->key = 'description';
                }
                $data = $info;
                $info->value = $request->get('description');
                $info->save();
            }
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => true, 'data' => null]);
    }

}
