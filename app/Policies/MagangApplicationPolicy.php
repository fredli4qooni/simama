<?php

namespace App\Policies;

use App\Models\MagangApplication;
use App\Models\User;

class MagangApplicationPolicy
{
    /**
     * Tentukan apakah user bisa melihat detail pengajuan.
     */
    public function view(User $user, MagangApplication $magangApplication): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'mahasiswa') {
            return $user->id === $magangApplication->user_id || 
                   $magangApplication->members()->where('user_id', $user->id)->exists();
        }

        if ($user->role === 'dpl') {
            $assignment = $magangApplication->dplAssignment;
            return $assignment && $assignment->dpl_user_id === $user->id;
        }

        return false;
    }
}