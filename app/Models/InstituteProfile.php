<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteProfile extends Model
{
    protected $dates = ['founded'];
    protected $fillable = [
        'public_email',
        'institute_name',
        'phone_number',
        'short_institute_name',
        'address',
        'founded',
        'about',
        'facebook',
        'googlemap',
        'website',
        'institute_category_id',
        'parent_institute_category_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
