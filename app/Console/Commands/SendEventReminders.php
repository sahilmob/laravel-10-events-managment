<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event reminders to attendees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = \App\Models\Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()])->get();
        foreach ($events as $event) {
            foreach ($event->attendees as $attendee) {
                $attendee->user->notify(new \App\Notifications\EventReminderNotification($event));

            }
        }
    }
}
