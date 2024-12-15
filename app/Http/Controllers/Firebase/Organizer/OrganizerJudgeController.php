<?php

namespace App\Http\Controllers\Firebase\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class OrganizerJudgeController extends Controller
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

    // Helper function to generate username (same as admin)
    private function generateUsername($firstName, $lastName)
    {
        $firstName = preg_replace('/[^A-Za-z0-9]/', '', $firstName);
        $lastName = preg_replace('/[^A-Za-z0-9]/', '', $lastName);
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        return ucfirst($firstName) . ucfirst($lastName) . $randomNumber;
    }

    // Helper function to generate password (same as admin)
    private function generatePassword($length = 8)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        
        $password = [
            $uppercase[rand(0, strlen($uppercase) - 1)],
            $lowercase[rand(0, strlen($lowercase) - 1)],
            $numbers[rand(0, strlen($numbers) - 1)]
        ];
        
        $allCharacters = $uppercase . $lowercase . $numbers;
        for ($i = count($password); $i < $length; $i++) {
            $password[] = $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }
        
        shuffle($password);
        return implode('', $password);
    }

    public function list(Request $request)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Get only this organizer's events
        $events = $this->database->getReference("{$organizerPath}/events")->getValue() ?? [];
        
        // Get only this organizer's judges
        $judges = $this->database->getReference("{$organizerPath}/judges")->getValue() ?? [];
        
        // Convert to collection for easier manipulation
        $judges = collect($judges)->map(function($item, $key) use ($events) {
            $judge = array_merge(['id' => $key], $item);
            
            if (isset($item['event_name'])) {
                $judge['event_display'] = $item['event_name'];
            } else {
                $judge['event_display'] = 'No Event Assigned';
            }
            
            return $judge;
        });

        // Filter by selected event
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

        return view('firebase.organizer.judge.organizer-judge-list', compact('judges', 'events'));
    }

    public function create()
    {
        $organizerPath = $this->getOrganizerBasePath();
        $events = $this->database->getReference("{$organizerPath}/events")->getValue();
        return view('firebase.organizer.judge.organizer-judge-setup', compact('events'));
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

        $organizerPath = $this->getOrganizerBasePath();
        $username = $this->generateUsername($request->Judge_firstname, $request->Judge_lastname);
        $password = $this->generatePassword(8);

        $postData = [
            'event_name' => $request->event_name,
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $username,
            'jpassword' => $password,
            'created_at' => ['.sv' => 'timestamp'],
            'organizer_id' => Session::get('user.id')
        ];

        // Store in organizer's folder
        $postRef = $this->database->getReference("{$organizerPath}/judges")->push($postData);

        // Also store in main judges collection for admin access
        if ($postRef) {
            $judgeId = $postRef->getKey();
            $this->database->getReference("judges/{$judgeId}")->set(array_merge(
                $postData,
                ['organizer_reference' => "{$organizerPath}/judges/{$judgeId}"]
            ));
        }

        return $postRef 
            ? redirect()->route('organizer.judge.list')->with('success', 'Judge Added Successfully')
            : redirect()->route('organizer.judge.list')->with('error', 'Judge Not Added');
    }

    public function edit($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        $editdata = $this->database->getReference("{$organizerPath}/judges/{$id}")->getValue();
        
        if ($editdata) {
            $events = $this->database->getReference("{$organizerPath}/events")->getValue();
            return view('firebase.organizer.judge.organizer-judge-edit', compact('editdata', 'id', 'events'));
        }
        
        return redirect()->route('organizer.judge.list')->with('status', 'Judge not found');
    }

    public function update(Request $request, $id)
    {
        $organizerPath = $this->getOrganizerBasePath();

        $updateData = [
            'event_name' => $request->event_name,
            'jfname' => $request->Judge_firstname,
            'jmname' => $request->Judge_middlename,
            'jlname' => $request->Judge_lastname,
            'jachievement' => $request->Judge_achievement,
            'jusername' => $request->jusername,
            'jpassword' => $request->jpassword,
            'updated_at' => ['.sv' => 'timestamp']
        ];

        // Update in organizer's folder
        $organizerUpdate = $this->database->getReference("{$organizerPath}/judges/{$id}")
            ->update($updateData);

        // Update in main judges collection
        if ($organizerUpdate) {
            $this->database->getReference("judges/{$id}")
                ->update($updateData);
        }

        return $organizerUpdate
            ? redirect()->route('organizer.judge.list')->with('status', 'Judge Updated Successfully')
            : redirect()->route('organizer.judge.list')->with('status', 'Judge Not Updated');
    }

    public function resetPassword($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        $newPassword = $this->generatePassword(8);
        
        // Update in organizer's folder
        $organizerUpdate = $this->database->getReference("{$organizerPath}/judges/{$id}")
            ->update(['jpassword' => $newPassword]);

        // Update in main judges collection
        if ($organizerUpdate) {
            $this->database->getReference("judges/{$id}")
                ->update(['jpassword' => $newPassword]);
        }

        return $organizerUpdate
            ? redirect()->back()->with('status', 'Password reset successfully. New password: ' . $newPassword)
            : redirect()->back()->with('status', 'Failed to reset password');
    }

    public function destroy($id)
    {
        $organizerPath = $this->getOrganizerBasePath();
        
        // Delete from organizer's folder
        $deleteFromOrganizer = $this->database->getReference("{$organizerPath}/judges/{$id}")
            ->remove();

        // Delete from main judges collection
        if ($deleteFromOrganizer) {
            $this->database->getReference("judges/{$id}")->remove();
        }

        return $deleteFromOrganizer
            ? redirect()->route('organizer.judge.list')->with('status', 'Judge Deleted Successfully')
            : redirect()->route('organizer.judge.list')->with('status', 'Judge Not Deleted');
    }
}