<?php

namespace App\Http\Controllers\Firebase\Tabulation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class JudgeController extends Controller
{
    protected $database, $tablename;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'judges';
    }

    // Helper function to generate username
    private function generateUsername($firstName, $lastName)
    {
        // Remove spaces and special characters
        $firstName = preg_replace('/[^A-Za-z0-9]/', '', $firstName);
        $lastName = preg_replace('/[^A-Za-z0-9]/', '', $lastName);
        
        // Generate a random 4-digit number
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Combine firstname, lastname and random number
        $username = ucfirst($firstName) . ucfirst($lastName) . $randomNumber;
        
        return $username;
    }

    // Helper function to generate password
    private function generatePassword($length = 8)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        // Ensure at least one of each required character type
        $password = [
            $uppercase[rand(0, strlen($uppercase) - 1)],
            $lowercase[rand(0, strlen($lowercase) - 1)],
            $numbers[rand(0, strlen($numbers) - 1)]
        ];
        
        // Fill the rest with random characters
        $allCharacters = $uppercase . $lowercase . $numbers;
        for ($i = count($password); $i < $length; $i++) {
            $password[] = $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }
        
        // Shuffle the password array and convert to string
        shuffle($password);
        return implode('', $password);
    }

    public function list()
    {
        $judges = $this->database->getReference($this->tablename)->getValue();
        $events = $this->database->getReference('events')->getValue();
        
        // Modify the judges array to include event names
        if ($judges) {
            foreach ($judges as $key => $judge) {
                if (isset($judge['event_name']) && isset($events[$judge['event_name']])) {
                    $judges[$key]['event_name'] = $events[$judge['event_name']]['ename'];
                } else {
                    $judges[$key]['event_name'] = 'N/A';
                }
            }
        }
        
        return view('firebase.tabulation.judge.judge-list', compact('judges'));
    }

    public function create()
    {
        $events = $this->database->getReference('events')->getValue();
        return view('firebase.tabulation.judge.judge-setup', compact('events'));
    }

    public function store(Request $request)
    {
        // Generate username and password
        $username = $this->generateUsername($request->Judge_firstname, $request->Judge_lastname);
        $password = $this->generatePassword(8);

        $postData = [
            'event_name' => $request->event_name, // This will store the event ID
            'event_id' => $request->event_name,   // Store event ID separately for better data structure
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $username,
            'jpassword' => $password,
        ];

        $postRef = $this->database->getReference($this->tablename)->push($postData);
        
        if ($postRef) {
            return redirect('judge-list')->with('success', 'Judge Added Successfully.');
        } else {
            return redirect('judge-list')->with('error', 'Judge Not added');
        }
    }

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if ($editdata) {
            $events = $this->database->getReference('events')->getValue();
            // If event_id exists, use it; otherwise, fall back to event_name
            $editdata['event_name'] = $editdata['event_id'] ?? $editdata['event_name'] ?? '';
            return view('firebase.tabulation.judge.judge-edit', compact('editdata', 'key', 'events'));
        } else {
            return redirect('judge-list')->with('status', 'Judge ID not Found');
        }
    }

    public function update(Request $request, $id)
    {
        $key = $id;
        $updateData = [
            'event_name' => $request->event_name, // This will store the event ID
            'event_id' => $request->event_name,   // Store event ID separately
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $request->jusername,
            'jpassword' => $request->jpassword,
        ];

        $res_update = $this->database->getReference($this->tablename . '/' . $key)->update($updateData);
        
        if ($res_update) {
            return redirect('judge-list')->with('status', 'Judge Updated Successfully');
        } else {
            return redirect('judge-list')->with('status', 'Judge Not Updated');
        }
    }
    public function resetPassword($id)
    {
        $key = $id;
        $newPassword = $this->generatePassword(8);
        
        $updateData = [
            'jpassword' => $newPassword
        ];

        $res_update = $this->database->getReference($this->tablename . '/' . $key)->update($updateData);
        
        if ($res_update) {
            return redirect()->back()->with('status', 'Password has been reset successfully. New password: ' );
        } else {
            return redirect()->back()->with('status', 'Failed to reset password');
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