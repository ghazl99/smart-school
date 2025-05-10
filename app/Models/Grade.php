<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable=['Name'];

    public function classrooms(){
        return $this->hasMany(Classroom::class, 'Grade_id');
    }
}
