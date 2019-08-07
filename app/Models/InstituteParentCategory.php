<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteParentCategory extends Model
{
    protected $fillable = ['child_id', 'parent_id'];

    public function institute_category(){
        return $this->belongsTo(InstituteCategory::class, 'parent_id');
    }
}
