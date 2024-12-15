<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class OrganizerContestantController extends Controller
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

    public function create()
    {
        $organizerPath = $this->getOrganizerBasePath();
        // Get events only from this organizer's folder
        $events = $this->database->getReference("{$organizerPath}/events")->getValue() ?? [];
        return view('firebase.organizer.contestant.organizer-contestant-setup', compact('events'));
    }

    public function list(Request $request)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Get events only from this organizer's folder
        $events = $this->database->getReference("{$organizerPath}/events")->getValue() ?? [];
        
        // Get contestants only from this organizer's folder
        $contestants = $this->database->getReference("{$organizerPath}/contestants")->getValue() ?? [];
        
        // Convert to collection for easier manipulation
        $contestants = collect($contestants)->map(function($item, $key) use ($events) {
            $contestant = array_merge(['id' => $key], $item);
            
            // Find event name from organizer's events
            $eventName = 'No Event Assigned';
            if (isset($contestant['ename'])) {
                foreach ($events as $event) {
                    if (isset($event['ename']) && $event['ename'] === $contestant['ename']) {
                        $eventName = $event['ename'];
                        break;
                    }
                }
            }
            $contestant['event_name'] = $eventName;
            
            return $contestant;
        });

        // Filter by selected event if specified
        if ($request->has('event_filter') && $request->event_filter !== 'all') {
            $contestants = $contestants->filter(function($contestant) use ($request) {
                return $contestant['event_name'] === $request->event_filter;
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $contestants = $contestants->filter(function($contestant) use ($searchTerm) {
                $fullName = strtolower(
                    ($contestant['cfname'] ?? '') . ' ' . 
                    ($contestant['cmname'] ?? '') . ' ' . 
                    ($contestant['clname'] ?? '')
                );
                return str_contains($fullName, $searchTerm);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        if ($sortBy === 'newest') {
            $contestants = $contestants->sortByDesc('id');
        } else {
            $contestants = $contestants->sortBy('id');
        }

        return view('firebase.organizer.contestant.organizer-contestant-list', compact('contestants', 'events'));
    }
    
    public function store(Request $request)
    {
        $organizerPath = $this->getOrganizerBasePath();

        $postData = [
            'ename' => $request->event_name,
            'cfname' => $request->Contestant_firstname,
            'cmname' => $request->Contestant_middlename,
            'clname' => $request->Contestant_lastname,
            'cage' => $request->Contestant_age,
            'cgender' => $request->Contestant_gender,
            'cbackground' => $request->Contestant_background,
            'created_at' => ['.sv' => 'timestamp'],
            'organizer_id' => Session::get('user.id')
        ];

        // Store in organizer's folder
        $postRef = $this->database->getReference("{$organizerPath}/contestants")->push($postData);

        // Also store in main contestants collection for admin access
        if ($postRef) {
            $contestantId = $postRef->getKey();
            $this->database->getReference("contestants/{$contestantId}")->set(array_merge(
                $postData,
                ['organizer_reference' => "{$organizerPath}/contestants/{$contestantId}"]
            ));
        }

        return $postRef 
            ? redirect()->route('organizer.contestant.list')->with('success', 'Contestant Added Successfully')
            : redirect()->route('organizer.contestant.list')->with('error', 'Contestant Not Added');
    }

    public function edit($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        $editdata = $this->database->getReference("{$organizerPath}/contestants/{$id}")->getValue();
    
        if ($editdata) {
            // Fetch events from organizer's folder
            $events = $this->database->getReference("{$organizerPath}/events")->getValue();
            
            // Store the current event name
            $editdata['event_name'] = $editdata['ename'] ?? null;
            
            return view('firebase.organizer.contestant.organizer-contestant-edit ', compact('editdata', 'id', 'events'));
        } else {
            return redirect()->route('organizer.contestant.list')
                            ->with('status', 'Contestant not found');
        }
    }

    public function update(Request $request, $id)
    {
        $organizerPath = $this->getOrganizerBasePath();

        $updateData = [
            'ename' => $request->event_name,
            'cfname' => $request->Contestant_firstname,
            'cmname' => $request->Contestant_middlename,
            'clname' => $request->Contestant_lastname,
            'cage' => $request->Contestant_age,
            'cgender' => $request->Contestant_gender,
            'cbackground' => $request->Contestant_background,
            'updated_at' => ['.sv' => 'timestamp']
        ];
        
        // Update in organizer's folder
        $organizerUpdate = $this->database->getReference("{$organizerPath}/contestants/{$id}")
            ->update($updateData);

        // Update in main contestants collection
        if ($organizerUpdate) {
            $this->database->getReference("contestants/{$id}")
                ->update($updateData);
        }
        
        return $organizerUpdate
            ? redirect()->route('organizer.contestant.list')
                ->with('status', 'Contestant Updated Successfully')
            : redirect()->route('organizer.contestant.list')
                ->with('status', 'Contestant Not Updated');
    }

    public function destroy($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Delete from organizer's folder
        $deleteFromOrganizer = $this->database->getReference("{$organizerPath}/contestants/{$id}")
            ->remove();

        // Delete from main contestants collection
        if ($deleteFromOrganizer) {
            $this->database->getReference("contestants/{$id}")->remove();
        }

        return $deleteFromOrganizer
            ? redirect()->route('organizer.contestant.list')
                ->with('status', 'Contestant Deleted Successfully')
            : redirect()->route('organizer.contestant.list')
                ->with('status', 'Contestant Not Deleted');
    }
}