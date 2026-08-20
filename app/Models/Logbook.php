<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'application_id', 
        'date', 
        'kegiatan', 
        'output', 
        'feedback_dpl'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }

    public function application()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }
}