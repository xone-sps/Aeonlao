<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckAssessmentSectionQuestion extends Model
{
    protected $fillable = ['schema', 'section_id', 'status'];// section_id checking id

    public function toJsonDecode()
    {
        $decodeAnswer = json_decode($this->schema);
        $decodeAnswer->id = $this->id;
        $decodeAnswer->status = $this->status;
        $decodeAnswer->status_approved = $this->status === 'success';
        $decodeAnswer->section_id = $this->section_id;
        $decodeAnswer->updated_at = $this->updated_at;
        unset($this->schema);
        return $decodeAnswer;
    }

    public function getAnswer($raw, $lang = 'en')
    {
        $answer = $raw->schema->$lang ?? '';
        if (is_object($answer) || is_array($answer)) {
            $data = [];
            foreach ($answer as $key => $val) {
                if (is_string($key)) {
                    $keyText = ucwords(str_replace('_', ' ', $key));
                    $data[] = "{$keyText}: {$val}";
                } else {
                    $data[] = $val;
                }
            }
            return $data;
        }
        return $answer;
    }
}
