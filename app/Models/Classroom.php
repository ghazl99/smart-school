<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable=['Name','Grade_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'Grade_id');
    }

    public function sections(){
        return $this->hasMany(Section::class,'Classroom_id');
    }

    public function fees(){
        return $this->hasMany(Fee::class, 'Classroom_id');
    }
}
