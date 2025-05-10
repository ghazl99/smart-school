<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable=['user_id','Specialization_id','Gender_id','Joining_Date','Address'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'Specialization_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'Gender_id');
    }
}
