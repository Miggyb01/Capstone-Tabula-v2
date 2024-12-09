<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JudgeScoreController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function score()
    {
        // Get the judge's assigned event
        $judgeId = session('user.id');
        $eventName = session('user.event_name');
        
        // Get event details
        $event = $this->database->getReference('events')
            ->orderByChild('ename')
            ->equalTo($eventName)
            ->getValue();

        // Example scoring data (to be replaced with real data)
        $scoringData = [
            'contestants_scored' => 8,
            'total_contestants' => 10,
            'average_score' => 88.5
        ];

        return view('firebase.judge.scores', compact('event', 'scoringData'));
    }
}