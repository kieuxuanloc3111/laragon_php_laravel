<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
