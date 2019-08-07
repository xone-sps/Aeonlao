<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 2/17/2019
 * Time: 2:07 PM
 */

namespace App\Responses;
use App\Http\Controllers\Helpers\Helpers;
use Illuminate\Contracts\Support\Responsable;

class IndexFieldInspectorResponse implements Responsable
{

    protected $options = [];

    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Create an HTTP response that represents the object.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function toResponse($request)
    {
        if (Helpers::isAjax($request)) {
            return response()->json(['success' => true]);
        }
        return view((string)$this->options['rootView'], [
            's' => $this->options['settings'],
        ]);
    }
}

