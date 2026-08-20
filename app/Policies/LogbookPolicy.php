<?php

namespace App\Policies;

use App\Models\Logbook;
use App\Models\User;

class LogbookPolicy
{
    /**
     * Tentukan apakah user (DPL) bisa memberikan feedback ke logbook ini.
     */
    public function updateFeedback(User $user, Logbook $logbook): bool
    {
        if ($user->role !== 'dpl') {
            return false;
        }

        $assignment = $logbook->application->dplAssignment;
        
        return $assignment && $assignment->dpl_user_id === $user->id;
    }
}