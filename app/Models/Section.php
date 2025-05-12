<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['Name', 'Status', 'Classroom_id', 'count', 'max_count'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'Classroom_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teacher_sections');
    }
}
