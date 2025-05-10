<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable=['user_id','gender_id','nationalitie_id','blood_id','Date_Birth',
    'Classroom_Section_id','parent_id','academic_year'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(MyParent::class, 'parent_id');
    }
}
