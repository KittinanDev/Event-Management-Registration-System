<?php

namespace App\Policies;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    public function cancel(User $user, Registration $registration): bool
    {
        return $user->id === $registration->user_id
            || $user->hasRole('organizer')
            || $user->hasRole('admin')
            || $user->role === 'admin';
    }
}
