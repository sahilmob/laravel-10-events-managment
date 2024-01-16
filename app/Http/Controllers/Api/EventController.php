<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\canLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    use canLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = $this->loadRelationships(Event::query());

        return EventResource::collection($query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $event = Event::create([...$data, 'user_id' => 1]);

        // $event->user()->associate(1);

        return new EventResource($this->loadRelationships($event, ['user']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {

        return new EventResource($this->loadRelationships($event));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {

        if (!$event) {
            return response()->json(['error' => 'Event not found.'], 404);
        }

        if ($request->user()->id !== $event->user_id) {
            return response()->json(['error' => 'You can only edit your own events.'], 403);
        }



        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
        ]);

        $event->update($data);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(null, 204);
    }
}
