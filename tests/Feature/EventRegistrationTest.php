<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_for_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['max_participants' => 10, 'status' => 'published']);

        $response = $this->actingAs($user)
            ->post(route('registrations.store', $event));

        $this->assertDatabaseHas('registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_register_twice(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['max_participants' => 10, 'status' => 'published']);

        Registration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('registrations.store', $event));

        $response->assertSessionHas('error', 'Already registered for this event');
    }

    public function test_registration_fails_when_event_full(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['max_participants' => 1, 'status' => 'published']);

        Registration::create([
            'event_id' => $event->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('registrations.store', $event));

        $response->assertSessionHas('error', 'Event is full');
    }

    public function test_user_can_cancel_registration(): void
    {
        $user = User::factory()->create();
        $registration = Registration::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete(route('registrations.destroy', $registration));

        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'cancelled',
        ]);
    }
}
