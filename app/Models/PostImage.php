<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $fillable = [
        'name'
    ];

    public static function Images()
    {
        $images = self::orderBy('id', 'desc')->get();
        $images->map(function ($img){
            $img->src = $img->name;
            $img->real_name = explode(md5('^'), $img->name)[0];
            $img->status = false;
            return $img;
        });
        return $images;
    }
}
