<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckAssessmentFieldInspector extends Model
{
    protected $type_cause = 'field_inspector';
    protected $fillable = ['status', 'check_assessment_id', 'check_user_id', 'field_inspector_id'];

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

    public function sectionsDelete(): void
    {
        CheckAssessmentSection::where('type', $this->type_cause)->whereIn('id', $this->sections()->pluck('id'))->delete();
    }
}
