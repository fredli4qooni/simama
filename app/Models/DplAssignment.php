<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DplAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function application()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }

    public function dpl()
    {
        return $this->belongsTo(User::class, 'dpl_user_id');
    }
}