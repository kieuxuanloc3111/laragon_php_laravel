<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'duration_minutes',
        'total_questions',
        'status',
        'created_by',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'exam_questions'
        )->withPivot('order_index');
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class);
    }
}