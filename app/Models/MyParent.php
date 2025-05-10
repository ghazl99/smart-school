<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyParent extends Model
{
    protected $fillable=['user_id','Name_Father','National_ID_Father','Passport_ID_Father','Phone_Father','Job_Father',
    'Nationality_Father_id','Blood_Type_Father_id','Religion_Father_id','Address_Father','Name_Mother',
    'National_ID_Mother','Passport_ID_Mother','Phone_Mother','Job_Mother','Nationality_Mother_id','Blood_Type_Mother_id',
    'Religion_Mother_id','Address_Mother'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
