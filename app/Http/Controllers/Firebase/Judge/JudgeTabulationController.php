<?php

// JudgeTabulationController.php

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
        // Get all events
        $events = $this->database->getReference('events')->getValue();
        
        return view('firebase.judge.judge-tabulation', [
            'events' => $events
        ]);
    }

    // Add method to get contestants by event name
    public function getContestantsByEvent($eventName) 
{
    try {
        // Debug log
        \Log::info('Fetching contestants for event: ' . $eventName);
        
        // Get reference to contestants
        $contestantsRef = $this->database->getReference('contestants');
        
        // Debug log the raw data
        \Log::info('All contestants:', ['data' => $contestantsRef->getValue()]);
        
        // Query contestants by event name
        $query = $contestantsRef->orderByChild('ename')
                               ->equalTo($eventName)
                               ->getValue();
                               
        // Debug log
        \Log::info('Query result:', ['data' => $query]);

        if (!$query) {
            return response()->json([]);
        }

        // Transform data
        $contestants = [];
        foreach ($query as $key => $contestant) {
            if (isset($contestant['ename']) && $contestant['ename'] === $eventName) {
                $contestants[] = array_merge($contestant, ['id' => $key]);
            }
        }

        // Debug log
        \Log::info('Processed contestants:', ['data' => $contestants]);
        
        return response()->json($contestants);
        
    } catch (\Exception $e) {
        \Log::error('Error fetching contestants: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch contestants'], 500);
    }
}
}