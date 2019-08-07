<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/11/2019
 * Time: 2:38 PM
 */

namespace App\Responses;


use App\Http\Controllers\Helpers\Helpers;
use App\Models\Site;
use Illuminate\Contracts\Support\Responsable;

class ContactInfoResponse implements Responsable
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
            $keys = ['phone', 'email', 'address', 'facebook', 'twitter'];
            $data = [];
            if ($this->actionType === 'get') {
                $data = Site::whereIn('key', $keys)->get();
                $s = [];
                foreach ($data as $item) {
                    $s[$item->key] = $item->value;
                }
                $data = $s;
            }

            if ($this->actionType === 'manage') {
                foreach ($keys as $item) {
                    $exist = Site::where('key', $item)->first();
                    if (!isset($exist)) {
                        $exist = new Site();
                        $exist->key = $item;
                    }
                    $exist->value = $request->get($item);
                    $exist->save();
                }
            }
            return response()->json(['success' => true, 'data' => $data]);
        }
        return response()->json(['success' => false, 'data' => null]);
    }

}
