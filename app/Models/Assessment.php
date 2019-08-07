<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = ['title', 'description', 'status'];

    public function sections(): HasMany
    {
        return $this->hasMany(AssessmentSection::class, 'assessment_id');
    }
}
