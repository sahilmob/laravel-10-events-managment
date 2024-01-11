<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = \App\Models\Event::all();
        $users = \App\Models\User::all();

        foreach ($events as $event) {
            $eventsToAttend = $events->random(rand(1, 3));
            $user = $users->random();

            foreach ($eventsToAttend as $eventToAttend) {
                \App\Models\Attendee::create([
                    'user_id' => $user->id,
                    'event_id' => $eventToAttend->id,
                ]);
            }
        }
    }
}
