<?php

namespace App\Models;

use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class AssignTeacher extends Model
{
    protected $table='assign_teachers';
    protected $fillable=['teacher_id','section_id','subject_id'];

     public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
