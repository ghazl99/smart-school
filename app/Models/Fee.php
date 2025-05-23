<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable=['title','amount','Classroom_id','description','year','Fee_type'];
    
    public function ClassRoom()
    {
        return $this->belongsTo(Classroom::class, 'Classroom_id');
    }

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
