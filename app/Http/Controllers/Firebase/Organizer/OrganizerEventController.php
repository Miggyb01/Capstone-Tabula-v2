<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class OrganizerEventController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    protected function getOrganizerBasePath()
    {
        $organizerId = Session::get('user.id');
        return "user_organizer/{$organizerId}/user_data";
    }

    public function dashboard()
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Count only this organizer's data
        $total_events = $this->database->getReference("{$organizerPath}/events")->getSnapshot()->numChildren();
        $total_contestants = $this->database->getReference("{$organizerPath}/contestants")->getSnapshot()->numChildren();
        $total_judges = $this->database->getReference("{$organizerPath}/judges")->getSnapshot()->numChildren();

        return view('firebase.organizer.organizerdashboard', compact('total_events', 'total_contestants', 'total_judges'));
    }

    public function list()
    {   
        $request = request();
        $organizerPath = $this->getOrganizerBasePath();
        
        // Get events only for this organizer
        $events = $this->database->getReference("{$organizerPath}/events")->getValue() ?? [];
    
        // Convert to collection for easier manipulation
        $events = collect($events)->map(function($item, $key) {
            return array_merge(['id' => $key], $item);
        });
    
        // Search functionality
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $events = $events->filter(function($item) use ($searchTerm) {
                return str_contains(strtolower($item['ename'] ?? ''), $searchTerm);
            });
        }
    
        // Sorting
        $sortBy = $request->get('sort', 'newest');
        if ($sortBy === 'newest') {
            $events = $events->sortByDesc('id');
        } else {
            $events = $events->sortBy('id');
        }
    
        return view('firebase.organizer.event.organizer-event-list', compact('events'));
    }

    public function create()
    {
        return view('firebase.organizer.event.organizer-event-setup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Event_name' => 'required|string|max:99',
            'Event_type' => 'required|string',
            'Event_description' => 'required|string',
            'Event_venue' => 'required|string',
            'Event_organizer' => 'required|string',
            'Event_date' => 'required|date',
            'Event_start' => 'required',
            'Event_end' => 'required',
        ]);

        $organizerPath = $this->getOrganizerBasePath();
        
        $postData = [
            'ename' => $request->Event_name,
            'etype' => $request->Event_type,
            'ebanner' => $request->Event_banner,
            'edescription' => $request->Event_description,
            'evenue' => $request->Event_venue,
            'eorganizer' => $request->Event_organizer,
            'edate' => $request->Event_date,
            'estart' => $request->Event_start,
            'eend' => $request->Event_end,
            'created_at' => ['.sv' => 'timestamp'],
            'organizer_id' => Session::get('user.id'),
        ];

        // Store in organizer's specific events folder
        $postRef = $this->database->getReference("{$organizerPath}/events")->push($postData);

        // Also store in main events collection for admin access
        if ($postRef) {
            $eventId = $postRef->getKey();
            $this->database->getReference("events/{$eventId}")->set(array_merge(
                $postData,
                ['organizer_reference' => "{$organizerPath}/events/{$eventId}"]
            ));
        }

        return $postRef 
            ? redirect()->route('organizer.event.list')->with('success', 'Event Added Successfully')
            : redirect()->route('organizer.event.list')->with('error', 'Event Not Added');
    }

    public function edit($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        $editdata = $this->database->getReference("{$organizerPath}/events/{$id}")->getValue();
        
        if ($editdata) {
            return view('firebase.organizer.event.organizer-event-edit', [
                'editdata' => $editdata,
                'key' => $id
            ]);
        }
        
        return redirect()->route('organizer.event.list')
            ->with('error', 'Event not found');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Event_name' => 'required|string|max:99',
            'Event_type' => 'required|string',
            'Event_description' => 'required|string',
            'Event_venue' => 'required|string',
            'Event_organizer' => 'required|string',
            'Event_date' => 'required|date',
            'Event_start' => 'required',
            'Event_end' => 'required',
        ]);

        $organizerPath = $this->getOrganizerBasePath();
        
        $updateData = [
            'ename' => $request->Event_name,
            'etype' => $request->Event_type,
            'ebanner' => $request->Event_banner,
            'edescription' => $request->Event_description,
            'evenue' => $request->Event_venue,
            'eorganizer' => $request->Event_organizer,
            'edate' => $request->Event_date,
            'estart' => $request->Event_start,
            'eend' => $request->Event_end,
            'updated_at' => ['.sv' => 'timestamp']
        ];

        // Update in organizer's folder
        $organizerUpdate = $this->database->getReference("{$organizerPath}/events/{$id}")
            ->update($updateData);

        // Update in main events collection
        if ($organizerUpdate) {
            $this->database->getReference("events/{$id}")
                ->update($updateData);
        }

        return $organizerUpdate
            ? redirect()->route('organizer.event.list')->with('success', 'Event updated successfully')
            : redirect()->route('organizer.event.list')->with('error', 'Event update failed');
    }

    public function destroy($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Delete from organizer's folder
        $deleteFromOrganizer = $this->database->getReference("{$organizerPath}/events/{$id}")
            ->remove();

        // Delete from main events collection
        if ($deleteFromOrganizer) {
            $this->database->getReference("events/{$id}")->remove();
        }

        return $deleteFromOrganizer
            ? redirect()->route('organizer.event.list')->with('success', 'Event deleted successfully')
            : redirect()->route('organizer.event.list')->with('error', 'Failed to delete event');
    }
}