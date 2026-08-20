<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'is_leader',
    ];

    public function application()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
