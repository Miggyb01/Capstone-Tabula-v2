<?php

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

        
        return view('firebase.judge.judge-tabulation'); 
        

        try {
            
            // Get the logged-in judge's event
            $judgeId = session('user.uid');
            $judge = $this->database->getReference('judges/' . $judgeId)->getValue();
            $eventId = $judge['event_id'] ?? null;

            if (!$eventId) {
                return redirect()->back()->with('error', 'No event assigned');
            }

            // Get event details
            $event = $this->database->getReference('events/' . $eventId)->getValue();

            // Get contestants for this event
            $contestants = $this->database->getReference('contestants')
                ->orderByChild('event_name')
                ->equalTo($event['ename'])
                ->getValue();

            dd($event, $contestants, $criteria);
            // Get criteria for this event
            $criteria = $this->database->getReference('criterias')
                ->orderByChild('ename')
                ->equalTo($event['ename'])
                ->getValue();

            return view('firebase.judge.judge-tabulation', [
                'event' => $event,
                'contestants' => $contestants,
                'criteria' => $criteria ? reset($criteria) : null // Get first criteria set
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function saveScore(Request $request)
    {
        try {
            $judgeId = session('user.uid');
            
            $scoreData = [
                'event_id' => $request->event_id,
                'contestant_id' => $request->contestant_id,
                'judge_id' => $judgeId,
                'scores' => $request->scores,
                'total_score' => $request->total_score,
                'timestamp' => ['.sv' => 'timestamp']
            ];

            $this->database->getReference('scores')->push($scoreData);
            
            return response()->json(['success' => true, 'message' => 'Score saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}