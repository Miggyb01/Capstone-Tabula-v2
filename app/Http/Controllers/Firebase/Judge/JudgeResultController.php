<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JudgeResultController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        // Get the judge's assigned event
        $eventName = session('user.event_name');
        
        // Get event details
        $event = $this->database->getReference('events')
            ->orderByChild('ename')
            ->equalTo($eventName)
            ->getValue();

        // Example results data (to be replaced with real data)
        $eventResults = [
            'status' => 'Completed',
            'total_contestants' => 10,
            'winners' => [
                ['name' => 'Contestant 1', 'score' => 92.8],
                ['name' => 'Contestant 2', 'score' => 89.5],
                ['name' => 'Contestant 3', 'score' => 87.3]
            ]
        ];

        return view('firebase.judge.results', compact('event', 'eventResults'));
    }
}