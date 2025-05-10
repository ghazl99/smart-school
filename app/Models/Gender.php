<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = ['Name'];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'Gender_id');
    }
}
