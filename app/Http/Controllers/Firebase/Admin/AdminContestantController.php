<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class AdminContestantController extends Controller
{
    protected $database, $tablename;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'contestants';
    }

    public function create()
    {
        // Get all events for the dropdown
        $events = $this->database->getReference('events')->getValue() ?? [];
        return view('firebase.admin.contestant.contestant-setup', compact('events'));
    }

    public function list(Request $request)
    {
        // Get all events
        $events = $this->database->getReference('events')->getValue() ?? [];
        
        // Get all contestants
        $contestants = $this->database->getReference($this->tablename)->getValue() ?? [];
        
        // Convert to collection for easier manipulation
        $contestants = collect($contestants)->map(function($item, $key) use ($events) {
            $contestant = array_merge(['id' => $key], $item);
            
            // Find event name from events reference
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

        return view('firebase.admin.contestant.contestant-list', compact('contestants', 'events'));
    }


    
    
    public function store(Request $request)
    {
        $postData = [
            'ename' => $request->event_name,
            'cfname' => $request->Contestant_firstname,
            'cmname' => $request->Contestant_middlename,
            'clname' => $request->Contestant_lastname,
            'cage' => $request->Contestant_age,
            'cgender' => $request->Contestant_gender,
            'cbackground' => $request->Contestant_background,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        return $postRef 
            ? redirect()->route('admin.contestant.list')->with('success', 'Judge Added Successfully')
            : redirect()->route('admin.contestant.list')->with('error', 'Judge Not added');
    }
   

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
    
        if ($editdata) {
            // Fetch all events
            $events = $this->database->getReference('events')->getValue();
            
            // Store the current event name from editdata
            $editdata['event_name'] = $editdata['ename'] ?? null;
            
            return view('firebase.admin.contestant.contestant-edit', compact('editdata', 'key', 'events'));
        } else {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant not found');
        }
    }

    public function update(Request $request, $id)
    {
        $key = $id;
        $updateData = [
            'ename' => $request->event_name,  // Store as ename
            'cfname' => $request->Contestant_firstname,
            'cmname' => $request->Contestant_middlename,
            'clname' => $request->Contestant_lastname,
            'cage' => $request->Contestant_age,
            'cgender' => $request->Contestant_gender,
            'cbackground' => $request->Contestant_background,
        ];
        
        $res_update = $this->database->getReference($this->tablename . '/' . $key)->update($updateData);
        
        if ($res_update) {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant Updated Successfully');
        } else {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant Not Updated');
        }
    }
    public function destroy($id)
    {
        $key = $id;
        $del_data = $this->database->getReference($this->tablename . '/' . $key)->remove();
        if ($del_data) {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant Deleted Successfully');
        } else {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant Not Deleted');
        }
    }
}
