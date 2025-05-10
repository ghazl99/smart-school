<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = ['Name'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'Specialization_id');
    }
}
