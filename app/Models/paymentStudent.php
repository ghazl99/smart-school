<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paymentStudent extends Model
{
    protected $fillable = ['date', 'student_id', 'amount', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'payment_id');
    }
}
