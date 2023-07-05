<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'Questions';
    protected $primaryKey = 'id';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    public function test()
    {
        return $this->belongsToMany(Test::class, 'TestQuestion', 'QuestionId', 'TestId');
    }

    public function prevExam()
    {
        return $this->belongsToMany(PrevExam::class, 'PrevExamQuestion', 'QuestionId', 'PrevExamId');
    }
}
 