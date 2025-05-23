<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class studentAccount extends Model
{
  protected $fillable = ['date', 'type', 'fee_invoice_id', 'receipt_id', 'processing_id', 'payment_id', 'student_id', 'Debit', 'credit', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeInvoice()
    {
        return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id');
    }

    public function receipt()
    {
        return $this->belongsTo(ReceiptStudent::class, 'receipt_id');
    }

    public function processing()
    {
        return $this->belongsTo(ProcessingFee::class, 'processing_id');
    }

    public function payment()
    {
        return $this->belongsTo(PaymentStudent::class, 'payment_id');
    }
}
