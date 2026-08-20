<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 
        'dpl_user_id', 
        'nilai_dpl',
        'nilai_instansi',
        'kedisiplinan', 
        'teknis', 
        'komunikasi', 
        'inisiatif', 
        'nilai_akhir', 
        'komentar', 
        'published_at'
    ];

    public function magangApplication()
    {
        return $this->belongsTo(MagangApplication::class, 'application_id');
    }

    public function dpl()
    {
        return $this->belongsTo(User::class, 'dpl_user_id');
    }
}