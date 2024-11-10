<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

use function PHPUnit\Framework\returnValueMap;

class EventController extends Controller
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
    
        return view('dashboard', compact('total_events', 'total_contestants','total_judges'));
    }

    public function list()
    {   

        $events= $this->database->getReference($this->tablename)->getValue();
        #$total_events = $reference = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();
        return view('firebase.event.event-list',compact('events'));

    }
    public function create()
    {
        return view('firebase.event.event-setup');
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
            'etabYes' => $request->Event_tabulation_Yes,
            'etabNo' => $request->Event_tabulation_No,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if($postRef)
        {
            return redirect('event-list')->with('success','Event Added Successfully ');
        }
        else
        {
            return redirect('event-list')->with('error','Event Not added');
        }
   } 

   public function edit($id)
   {    
        $key = $id; 
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if($editdata)
        {
            return view('firebase.event.event-edit', compact('editdata','key'));
        }
        else
        {
            return redirect('firebase.event.event-edit')->with('status','Contact ID not Found');
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

