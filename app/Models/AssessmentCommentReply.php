<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AssessmentCommentReply extends Model
{
    public function comment() {
        return $this->belongsTo(AssessmentComment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
