<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

use function PHPUnit\Framework\returnValueMap;

class OrganizerEventController extends Controller
{
    protected $database, $tablename;
    public function __construct(Database $database)
    {
        $this->database = $database; 
        $this->tablename = 'events';
    }
    public function dashboard()
    {
        $total_events = $this->database->getReference('events')->getSnapshot()->numChildren();
        $total_contestants = $this->database->getReference('contestants')->getSnapshot()->numChildren();
        $total_judges = $this->database->getReference('judges')->getSnapshot()->numChildren();

        return view('firebase.organizer.organizerdashboard', compact('total_events', 'total_contestants', 'total_judges'));
    }
    public function list()
    {   
        $request = request();
        $query = $this->database->getReference($this->tablename);
        $events = $query->getValue() ?? [];
    
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
    
        return view('firebase.organizer.event.event-list', compact('events'));
    }

    public function create()
    {
        return view('firebase.organizer.event.event-setup');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Event_name' => 'required|string|max:99',
        ]);
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
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if($postRef)
        {
            return redirect('organizer/event/list')->with('success','Event Added Successfully ');
        }
        else
        {
            return redirect('organizer/event/list')->with('error','Event Not added');
        }
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
       ];
    
       $updateRef = $this->database->getReference($this->tablename . '/' . $id)->update($postData);
    
       if ($updateRef) {
           return redirect()->route('organizer.event.list')->with('success', 'Event updated successfully');
       } else {
           return redirect()->route('organizer.event.list')->with('error', 'Event update failed');
       }
    }

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        
        if ($editdata) {
            return view('firebase.organizer.event.event-edit', compact('editdata', 'key'));
        } else {
            return redirect()->route('organizer.event.list')->with('status', 'Event not found');
        }
    }

   

   public function destroy($id)
   {
        $key = $id;
        $del_data = $this->database->getReference($this->tablename. '/' .$key)->remove();
        if($del_data)
        {
            return redirect('event-list')->with('status','Event Deleted Successfully');
        }    
        else
        {
            return redirect('event-list')->with('status','Event Not Deleted');
        }
   }


   
}

