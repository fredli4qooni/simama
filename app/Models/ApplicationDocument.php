<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_id', 
        'user_id', 
        'type', 
        'file_path'
    ];

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
