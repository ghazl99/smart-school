<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fundAccount extends Model
{
    protected $fillable = [
        'date',
        'receipt_id',
        'Debit',
        'credit',
        'description',
    ];

    public function receipt()
    {
        return $this->belongsTo(ReceiptStudent::class, 'receipt_id');
    }
}
