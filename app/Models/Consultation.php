<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'magang_application_id',
        'sender_id',
        'message',
        'is_read',
    ];

    public function application()
    {
        return $this->belongsTo(MagangApplication::class, 'magang_application_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}