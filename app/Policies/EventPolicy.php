<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function view(User $user, Event $event): bool
    {
        return true; // Anyone can view
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin') || $user->hasRole('organizer');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin') || $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin') || $user->id === $event->user_id;
    }

    public function checkIn(User $user, Event $event): bool
    {
        return $user->role === 'admin' || $user->hasRole('admin') || $user->id === $event->user_id;
    }
}
