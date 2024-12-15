<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class OrganizerCalendarController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        // Get events for initial display
        $events = $this->database->getReference('events')->getValue() ?? [];
        return view('firebase.organizer.calendar', compact('events'));
    }

    public function getEvents()
    {
        try {
            $events = $this->database->getReference('events')->getValue() ?? [];
            
            $transformedEvents = [];
            foreach ($events as $key => $event) {
                if (isset($event['ename']) && isset($event['edate'])) {
                    $transformedEvents[] = [
                        'id' => $key,
                        'ename' => $event['ename'],
                        'edate' => $event['edate']
                    ];
                }
            }
            
            return response()->json($transformedEvents);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events'], 500);
        }
    }
}