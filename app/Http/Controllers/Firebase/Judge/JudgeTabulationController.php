<?php

// app/Http/Controllers/Firebase/Judge/JudgeTabulationController.php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JudgeTabulationController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        // Get events, contestants, and criteria from Firebase
        $events = $this->database->getReference('events')->getValue();
        $contestants = $this->database->getReference('contestants')->getValue();
        $criterias = $this->database->getReference('criterias')->getValue();

        // Process the data for the view
        $eventData = [];
        
        if($events) {
            foreach($events as $eventId => $event) {
                $eventName = $event['ename'];
                $eventData[$eventId] = [
                    'event_details' => $event,
                    'contestants' => [],
                    'criteria' => null
                ];

                // Get contestants for this event
                if($contestants) {
                    foreach($contestants as $contestantId => $contestant) {
                        if($contestant['event_name'] === $eventName) {
                            $eventData[$eventId]['contestants'][$contestantId] = $contestant;
                        }
                    }
                }

                // Get criteria for this event
                if($criterias) {
                    foreach($criterias as $criteriaId => $criteria) {
                        if($criteria['ename'] === $eventName) {
                            $eventData[$eventId]['criteria'] = $criteria;
                            break; // Assuming one criteria set per event
                        }
                    }
                }
            }
        }

        return view('firebase.judge.judge-tabulation', compact('eventData'));
    }

    public function saveScore(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required',
            'contestant_id' => 'required',
            'category_id' => 'required',
            'main_criteria_id' => 'required',
            'sub_criteria_id' => 'required',
            'score' => 'required|numeric|min:0|max:100'
        ]);

        $scoreData = [
            'event_id' => $validated['event_id'],
            'contestant_id' => $validated['contestant_id'],
            'category_id' => $validated['category_id'],
            'main_criteria_id' => $validated['main_criteria_id'],
            'sub_criteria_id' => $validated['sub_criteria_id'],
            'score' => $validated['score'],
            'judge_id' => auth()->id(),
            'timestamp' => ['.sv' => 'timestamp']
        ];

        $scoreRef = $this->database->getReference('scores')->push($scoreData);

        return response()->json([
            'success' => true,
            'score_id' => $scoreRef->getKey()
        ]);
    }
}