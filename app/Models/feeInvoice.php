<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class feeInvoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'student_id',
        'Grade_id',
        'Classroom_id',
        'fee_id',
        'amount',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'Grade_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'Classroom_id');
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'fee_invoice_id');
    }
}
