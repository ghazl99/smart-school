<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class feeInvoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'student_id',
        'fee_id',
        'amount',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
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
