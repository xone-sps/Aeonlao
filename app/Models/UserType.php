<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $fillable = [
        'user_id', 'type_user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function typeUser(){
        return $this->belongsTo(TypeUser::class, 'type_user_id');
    }
}
