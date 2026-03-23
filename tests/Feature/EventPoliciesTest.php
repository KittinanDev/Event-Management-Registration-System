<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EventPoliciesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create roles for testing
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'organizer']);
        Role::create(['name' => 'attendee']);
    }

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
                'max_attendees' => 10,
                'status' => 'open',
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Test Event']);
    }

    public function test_admin_can_create_event(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)
            ->post(route('events.store'), [
                'title' => 'Admin Event',
                'description' => 'Admin Description',
                'location' => 'Admin Location',
                'start_datetime' => now()->addDay(),
                'end_datetime' => now()->addDay()->addHours(2),
                'max_attendees' => 10,
                'status' => 'open',
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Admin Event']);
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
                'max_attendees' => 10,
                'status' => 'open',
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
                'max_attendees' => $event->max_attendees ?? $event->max_participants,
                'status' => $event->status,
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Updated Title']);
    }

    public function test_admin_can_update_event(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $event = Event::factory()->create();

        $response = $this->actingAs($admin)
            ->put(route('events.update', $event), [
                'title' => 'Admin Updated',
                'description' => $event->description,
                'location' => $event->location,
                'start_datetime' => $event->start_datetime,
                'end_datetime' => $event->end_datetime,
                'max_attendees' => $event->max_attendees ?? $event->max_participants,
                'status' => $event->status,
            ]);

        $this->assertDatabaseHas('events', ['title' => 'Admin Updated']);
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
                'max_attendees' => $event->max_attendees ?? $event->max_participants,
                'status' => $event->status,
            ]);

        $response->assertForbidden();
    }
}
