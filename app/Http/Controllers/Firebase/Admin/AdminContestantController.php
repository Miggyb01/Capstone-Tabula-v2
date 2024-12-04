<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use function PHPUnit\Framework\returnValueMap;

class AdminContestantController extends Controller
{
    protected $database, $tablename;
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'contestants';
    }
    public function list()
    {
        $contestants = $this->database->getReference($this->tablename)->getValue();
        #$total_events = $reference = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();
        return view('firebase.admin.contestant.contestant-list', compact('contestants'));
    }
    public function create()
    {
        $events = $this->database->getReference('events')->getValue();

        return view('firebase.admin.contestant.contestant-setup', compact('events'));
    }

    public function store(Request $request)
    {
        $postData = [
            'event_name' => $request->event_name,
            'cfname' => $request->Contestant_firstname,
            'cmname' => $request->Contestant_middlename,
            'clname' => $request->Contestant_lastname,
            'cage' => $request->Contestant_age,
            'cgender' => $request->Contestant_gender,
            'cbackground' => $request->Contestant_background,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if ($postRef) {
            return redirect()->route('admin.contestant.list')
                            ->with('success', 'Contestant Added Successfully');
        } else {
            return redirect()->route('admin.contestant.list')
                            ->with('error', 'Contestant Not Added');
        }
    }
    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();

        if ($editdata && isset($editdata['event_name'])) {
            // Fetch all events
            $events = $this->database->getReference('events')->getValue();

            return view('firebase.admin.contestant.contestant-edit', compact('editdata', 'key', 'events'));
        } else {
            return redirect()->route('admin.contestant.list')
                            ->with('status', 'Contestant ID or Event Name not Found');
        }
    }


    public function update(Request $request, $id)
    {
        $key = $id;
        $updateData = [
            'event_name' => $request->event_name,
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
