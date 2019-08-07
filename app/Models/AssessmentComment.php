<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AssessmentComment extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(AssessmentCommentReply::class, 'assessment_comment_id');
    }
}
