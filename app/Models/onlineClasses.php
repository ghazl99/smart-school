<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class onlineClasses extends Model
{
    protected $fillable=['integration','section_id','user_id','meeting_id','topic',
    'start_at','duration','password','start_url','join_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
