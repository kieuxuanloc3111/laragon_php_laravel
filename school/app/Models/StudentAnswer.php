<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'student_exam_id',
        'question_id',
        'answer_id',
        'is_correct',
    ];

    public function studentExam()
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}