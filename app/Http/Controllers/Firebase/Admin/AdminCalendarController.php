<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class AdminCalendarController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $events = $this->database->getReference('events')->getValue();
        
        // Transform events for calendar
        $calendarEvents = [];
        if ($events) {
            foreach ($events as $key => $event) {
                $calendarEvents[] = [
                    'title' => $event['ename'],
                    'start' => $event['edate'],
                    'status' => 'confirmed' // or any default status
                ];
            }
        }

        return view('firebase.admin.calendar', compact('calendarEvents'));
    }
    
}