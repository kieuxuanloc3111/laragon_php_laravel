<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'chapter_id',
        'content',
        'explanation',
        'difficulty',
        'image_url',
        'created_by',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function exams()
    {
        return $this->belongsToMany(
            Exam::class,
            'exam_questions'
        );
    }
}