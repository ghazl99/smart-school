<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable=['quizze_id','title','answers','right_answer','score'];
    protected $casts = [
        'answers' => 'array',
    ];

    public function quizze()
    {
        return $this->belongsTo(Quizze::class);
    }
}
