<?php

namespace App\Models;

use App\Http\Controllers\Helpers\Helpers;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed user
 */
class UserProfile extends Model
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
