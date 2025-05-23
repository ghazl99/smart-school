<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class receiptStudent extends Model
{
   protected $fillable = ['date', 'student_id', 'Debit', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'receipt_id');
    }
}
