<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'gender_id',
        'nationalitie_id',
        'blood_id',
        'Date_Birth',
        'Section_id',
        'parent_id',
        'academic_year'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(MyParent::class, 'parent_id');
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationalitie_id');
    }
    public function blood()
    {
        return $this->belongsTo(TypeBlood::class, 'blood_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'Section_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
