<?php

namespace App\Models;

use App\Responses\Admin\Schema\QuestionContentSchema;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentSection extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'section_order', 'assessment_id'];

    public function questions(): HasMany
    {
        return $this->hasMany(SectionQuestion::class, 'section_id');
    }

    public function questionsJson()
    {
        $questionsJson = [];
        $questions = $this->questions;
        $sorted = $questions->sortBy('question_order')->values()->all();
        unset($this->questions);
        foreach ($sorted as $question) {
            $decodeQuestion = json_decode($question->schema);
            $decodeQuestion->id = $question->id;
            $decodeQuestion->updated_at = $question->updated_at;
            $questionsJson[] = $decodeQuestion;
            unset($question->schema);
        }
        $this->questions = $questionsJson;
    }
}
