<?php

namespace App\Jobs;

use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendRegistrationConfirmationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Registration $registration)
    {
    }

    public function handle(): void
    {
        Mail::send(new RegistrationConfirmation($this->registration));
    }
}
