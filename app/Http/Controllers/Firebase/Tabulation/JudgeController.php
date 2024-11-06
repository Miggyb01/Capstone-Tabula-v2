<?php

namespace App\Http\Controllers\Firebase\Tabulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

use function PHPUnit\Framework\returnValueMap;

class JudgeController extends Controller
{
    protected $database, $tablename;
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'judges';
    }
    public function list()
    {
        $judges = $this->database->getReference($this->tablename)->getValue();
        #$total_events = $reference = $this->database->getReference($this->tablename)->getSnapshot()->numChildren();
        return view('firebase.tabulation.judge.judge-list', compact('judges'));
    }
    public function create()
    {
        $events = $this->database->getReference('events')->getValue();
        return view('firebase.tabulation.judge.judge-setup', compact('events'));
    }
    public function store(Request $request)
    {
        $postData = [
            'event_name' => $request->event_name,
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $request->Judge_username,
            'jpassword' => $request->Judge_password,
            // 'jassign' => $request->Judge_assign,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if ($postRef) {
            return redirect('judge-list')->with('success', 'Judge Added Successfully ');
        } else {
            return redirect('judge-list')->with('error', 'Judge Not added');
        }
    }

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if ($editdata) {
            return view('firebase.tabulation.judge.judge-edit', compact('editdata', 'key'));
        } else {
            return redirect('firebase.tabulation.judge.judge-edit')->with('status', 'Judge ID not Found');
        }
    }

    public function update(Request $request, $id)
    {
        $key = $id;
        $updateData = [
            'event_name' => $request->event_name,
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $request->Judge_username,
            'jpassword' => $request->Judge_password,
            // 'jassign' => $request->Judge_assign,
        ];
        $res_update = $this->database->getReference($this->tablename . '/' . $key)->update($updateData);
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();

        if ($editdata && isset($editdata['event_name'])) {
            // Fetch all events
            $events = $this->database->getReference('events')->getValue();

            return view('firebase.tabulation.judge.judge-edit', compact('editdata', 'key', 'events'));
        } else {
            return redirect('contestant-judge-list')->with('status', 'Contestant ID or Event Name not Found');
        }
    }
    public function destroy($id)
    {
        $key = $id;
        $del_data = $this->database->getReference($this->tablename . '/' . $key)->remove();
        if ($del_data) {
            return redirect('judge-list')->with('status', 'Judge Deleted Successfully');
        } else {
            return redirect('judge-list')->with('status', 'Judge Not Deleted');
        }
    }
}
