<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckAssessmentSection extends Model
{
    protected $fillable = ['score', 'status', 'check_assessment_id', 'type'];

    public function checkAssessmentSectionQuestions()
    {
        return $this->hasMany(CheckAssessmentSectionQuestion::class, 'section_id');
    }

    public function questionsJson()
    {
        $answersJson = [];
        $answers = $this->checkAssessmentSectionQuestions;
        unset($this->checkAssessmentSectionQuestions);
        foreach ($answers as $answer) {
            $decodeAnswer = json_decode($answer->schema);
            $decodeAnswer->id = $answer->id;
            $decodeAnswer->status = $answer->status;
            $decodeAnswer->status_approved = $answer->status === 'success';
            $decodeAnswer->section_id = $answer->section_id;
            $decodeAnswer->updated_at = $answer->updated_at;
            $answersJson[] = $decodeAnswer;
            unset($answer->schema);
        }
        $this->answers = $answersJson;
    }

}
