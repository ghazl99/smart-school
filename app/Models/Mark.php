<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = ['student_id', 'quizze_id', 'score'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function quizze()
    {
        return $this->belongsTo(Quizze::class);
    }
}
