<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPoliciesTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event(): void
    {
        $user = User::factory()->create();
        $user->assignRole('organizer');

        $response = $this->actingAs($user)
            ->post(route('events.store'), [
                'title' => 'Test Event',
                'description' => 'Test Description',
                'location' => 'Test Location',
                'start_datetime' => now()->addDay(),
                'end_datetime' => now()->addDay()->addHours(2),
                'max_participants' => 10,
                'status' => 'published',
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Test Event']);
    }

    public function test_non_organizer_cannot_create_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('events.store'), [
                'title' => 'Test Event',
                'description' => 'Test',
                'location' => 'Test',
                'start_datetime' => now()->addDay(),
                'end_datetime' => now()->addDay()->addHours(2),
                'max_participants' => 10,
                'status' => 'published',
            ]);

        $response->assertForbidden();
    }

    public function test_event_owner_can_update(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->put(route('events.update', $event), [
                'title' => 'Updated Title',
                'description' => $event->description,
                'location' => $event->location,
                'start_datetime' => $event->start_datetime,
                'end_datetime' => $event->end_datetime,
                'max_participants' => $event->max_participants,
                'status' => $event->status,
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Updated Title']);
    }

    public function test_non_owner_cannot_update_event(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($user)
            ->put(route('events.update', $event), [
                'title' => 'Updated',
                'description' => $event->description,
                'location' => $event->location,
                'start_datetime' => $event->start_datetime,
                'end_datetime' => $event->end_datetime,
                'max_participants' => $event->max_participants,
                'status' => $event->status,
            ]);

        $response->assertForbidden();
    }
}
