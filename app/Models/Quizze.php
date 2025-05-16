<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizze extends Model
{
    protected $fillable = ['name', 'assign_teacher_id','max_score'];

    public function assignTeacher()
    {
        return $this->belongsTo(AssignTeacher::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
