<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public static function getBanners($limit = 0)
    {
        return self::select(['id', 'name', 'description', 'order', 'link', 'image', 'created_at', 'updated_at'])->limit($limit)->orderBy('order', 'asc')->get();
    }
}
