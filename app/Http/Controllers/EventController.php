<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::paginate(10);
        if (count($events) > 0) {
            return response()->json([
                'status' => 200,
                'events' => $events
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Record Found."
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventTitle' => 'required|string|max:255|unique:events',
            'date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        } else {
            return response()->json([
                'status' => 200,
                'event' => $event
            ], 200);
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'eventTitle' => 'required|string|max:255|unique:events,eventTitle,' . $id,
            'date' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        } else {
            $event->update($request->all());
            return response()->json($event, 200);
        }
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        $event->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Event deleted successfully'
        ], 200);
    }
}
