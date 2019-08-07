<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CheckAssessment extends Model
{
    protected $fillable = ['status', 'assessment_id', 'user_id'];
    protected $type_cause = 'institute';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function checkAssessmentSections()
    {
        return $this->hasMany(CheckAssessmentSection::class, 'check_assessment_id', 'id');
    }

    public function sections()
    {
        return $this->checkAssessmentSections->filter(function ($item) {
            return $item->type === $this->type_cause;
        });
    }

    public function sectionsDelete()
    {
        CheckAssessmentSection::where('type', $this->type_cause)->whereIn('id', $this->sections()->pluck('id'))->delete();
    }
}
