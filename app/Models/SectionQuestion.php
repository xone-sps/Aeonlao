<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionQuestion extends Model
{
    use SoftDeletes;
    protected $fillable = ['schema', 'question_order', 'section_id'];

    public function toJsonDecode()
    {
        $decodeQuestion = json_decode($this->schema);
        $decodeQuestion->id = $this->id;
        $decodeQuestion->updated_at = $this->updated_at;
        unset($this->schema);
        return $decodeQuestion;
    }
}
