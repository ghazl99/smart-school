<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable=['Name'];

    public function assignTeachers()
    {
        return $this->hasMany(AssignTeacher::class);
    }
}
