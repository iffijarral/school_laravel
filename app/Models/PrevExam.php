<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrevExam extends Model
{
    use HasFactory;
    protected $table = 'PrevExams';
    protected $primaryKey = 'id';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'PrevExamQuestion', 'PrevExamId', 'QuestionId');
    }
}
