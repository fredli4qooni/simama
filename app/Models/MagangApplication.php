<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instansi',
        'alamat',
        'alamat_instansi',
        'start_date',
        'end_date',
        'status',
        'catatan_admin',
        'prodi_id',
        'is_group',
        'jenis_magang',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->hasMany(MagangMember::class, 'application_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'application_id');
    }

    public function dplAssignment()
    {
        return $this->hasOne(DplAssignment::class, 'application_id');
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class, 'application_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'magang_application_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'application_id');
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'application_id');
    }
}