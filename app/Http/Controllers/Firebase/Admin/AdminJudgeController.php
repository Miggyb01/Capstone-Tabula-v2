<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Str;

class AdminJudgeController extends Controller
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

    public function list(Request $request)
    {
        // Get all events first
        $events = $this->database->getReference('events')->getValue() ?? [];
        
        // Get all judges
        $judges = $this->database->getReference($this->tablename)->getValue() ?? [];
        
        // Convert to collection for easier manipulation
        $judges = collect($judges)->map(function($item, $key) use ($events) {
            $judge = array_merge(['id' => $key], $item);
            
            // Get event name from events table
            if (isset($item['event_name'])) {
                $judge['event_display'] = $item['event_name'];
            } else {
                $judge['event_display'] = 'No Event Assigned';
            }
            
            return $judge;
        });

        // Filter by selected event if specified
        if ($request->has('event_filter') && $request->event_filter !== 'all') {
            $judges = $judges->filter(function($judge) use ($request) {
                return $judge['event_display'] === $request->event_filter;
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $judges = $judges->filter(function($judge) use ($searchTerm) {
                $fullName = strtolower(
                    ($judge['jfname'] ?? '') . ' ' . 
                    ($judge['jmname'] ?? '') . ' ' . 
                    ($judge['jlname'] ?? '')
                );
                $username = strtolower($judge['jusername'] ?? '');
                return str_contains($fullName, $searchTerm) || str_contains($username, $searchTerm);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        if ($sortBy === 'newest') {
            $judges = $judges->sortByDesc('id');
        } else {
            $judges = $judges->sortBy('id');
        }

        return view('firebase.admin.judge.judge-list', compact('judges', 'events'));
    }

    public function create()
    {
        $events = $this->database->getReference('events')->getValue();
        return view('firebase.admin.judge.judge-setup', compact('events'));
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required',
            'Judge_firstname' => 'required',
            'Judge_middlename' => 'required',
            'Judge_lastname' => 'required',
            'Judge_achievement' => 'required',
        ]);

        $username = $this->generateUsername($request->Judge_firstname, $request->Judge_lastname);
        $password = $this->generatePassword(8);

        $postData = [
            'event_name' => $request->event_name,  // Store the actual event name
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $username,
            'jpassword' => $password,
        ];

        $postRef = $this->database->getReference($this->tablename)->push($postData);
        return $postRef 
            ? redirect()->route('admin.judge.list')->with('success', 'Judge Added Successfully')
            : redirect()->route('admin.judge.list')->with('error', 'Judge Not added');
    }

    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if ($editdata) {
            // Get all events
            $events = $this->database->getReference('events')->getValue();
            
            // Find the current event name if it exists
            if (isset($editdata['event_name'])) {
                foreach ($events as $eventId => $event) {
                    if ($eventId === $editdata['event_name']) {
                        $editdata['event_name'] = $event['ename'];
                        break;
                    }
                }
            }
    
            return view('firebase.admin.judge.judge-edit', compact('editdata', 'key', 'events'));
        } else {
            return redirect()->route('admin.judge.list')->with('status', 'Judge not found');
        }
    }

    public function update(Request $request, $id)
    {
        // Find the event ID based on the selected event name
        $events = $this->database->getReference('events')->getValue();
        $eventName = null;
        
        foreach ($events as $eventId => $event) {
            if ($eventId === $request->event_name) {
                $eventName = $event['ename'];
                break;
            }
        }
    
        if (!$eventName) {
            return redirect()->back()->with('error', 'Invalid event selection');
        }
    
        $updateData = [
            'event_name' => $eventName, // Store the event name instead of ID
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $request->jusername,
            'jpassword' => $request->jpassword,
        ];
    
        $res_update = $this->database->getReference($this->tablename . '/' . $id)->update($updateData);
        
        return $res_update 
            ? redirect()->route('admin.judge.list')->with('status', 'Judge Updated Successfully')
            : redirect()->route('admin.judge.list')->with('status', 'Judge Not Updated');
    }
    public function resetPassword($id)
    {
        $newPassword = $this->generatePassword(8);
        $res_update = $this->database->getReference($this->tablename . '/' . $id)
            ->update(['jpassword' => $newPassword]);
        
        return $res_update 
            ? redirect()->back()->with('status', 'Password reset successfully. New password: ' . $newPassword)
            : redirect()->back()->with('status', 'Failed to reset password');
    }
    
    public function destroy($id)
    {
        $del_data = $this->database->getReference($this->tablename . '/' . $id)->remove();
        return $del_data 
            ? redirect()->route('admin.judge.list')->with('status', 'Judge Deleted Successfully')
            : redirect()->route('admin.judge.list')->with('status', 'Judge Not Deleted');
    }
}