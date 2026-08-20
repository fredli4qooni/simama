<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'email',
    'avatar',
    'password',
    'nim',
    'nip',
    'role',
    'is_active',
    'prodi_id',
    'total_sks',
    'ipk'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Accessor untuk URL Avatar Pengguna
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return null;
    }

    /**
     * Relasi: Pengajuan magang di mana user bertindak sebagai ketua/pemohon
     */
    public function magangApplications()
    {
        return $this->hasMany(MagangApplication::class);
    }

    /**
     * Relasi: Keanggotaan magang kelompok
     */
    public function magangMembers()
    {
        return $this->hasMany(MagangMember::class);
    }

    /**
     * Helper: Mendapatkan pengajuan magang aktif (baik sebagai ketua maupun anggota kelompok)
     */
    public function activeMagangApplication()
    {
        $asLeader = $this->magangApplications()->whereIn('status', ['active', 'approved'])->latest()->first();
        if ($asLeader) {
            return $asLeader;
        }

        $asMember = MagangApplication::whereHas('members', function ($q) {
            $q->where('user_id', $this->id);
        })->whereIn('status', ['active', 'approved'])->latest()->first();

        return $asMember;
    }

    /**
     * Helper: Mendapatkan pengajuan magang terbaru (apapun statusnya)
     */
    public function latestMagangApplication()
    {
        $asLeader = $this->magangApplications()->latest()->first();
        if ($asLeader) {
            return $asLeader;
        }

        return MagangApplication::whereHas('members', function ($q) {
            $q->where('user_id', $this->id);
        })->latest()->first();
    }

    /**
     * Relasi: Satu user bisa memiliki banyak logbook
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    /**
     * Relasi: Satu user bisa memiliki banyak presensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: Satu user (DPL) bisa memiliki banyak penugasan bimbingan
     */
    public function dplAssignments()
    {
        return $this->hasMany(DplAssignment::class, 'dpl_user_id');
    }

    /**
     * Relasi: Mendapatkan daftar mahasiswa yang dibimbing (melalui DplAssignment)
     */
    public function bimbingan()
    {
        return $this->hasManyThrough(
            MagangApplication::class,
            DplAssignment::class,
            'dpl_user_id',
            'id',
            'id',
            'application_id'
        );
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
