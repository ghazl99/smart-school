<?php

namespace App\Models;

use App\Models\AssignTeacher;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['Name', 'Status', 'Classroom_id', 'count', 'max_count'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'Classroom_id');
    }

    public function assignTeachers()
    {
        return $this->hasMany(AssignTeacher::class);
    }
}
