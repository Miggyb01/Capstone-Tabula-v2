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
        // Get the current judge's event from session
        $judgeEventName = session('user.event_name');
        
        if (!$judgeEventName) {
            return response()->json(['error' => 'No event assigned'], 400);
        }

        // Get event criteria
        $criteria = $this->getEventCriteria($judgeEventName);
        
        // Get contestants for the event
        $contestants = $this->getEventContestants($judgeEventName);

        return view('firebase.judge.judge-tabulation', [
            'eventName' => $judgeEventName,
            'criteria' => $criteria,
            'contestants' => $contestants
        ]);
    }

    protected function getEventCriteria($eventName)
    {
        $criteriaRef = $this->database->getReference('criterias');
        $criteria = $criteriaRef->orderByChild('ename')
                              ->equalTo($eventName)
                              ->getValue();

        if (!$criteria) {
            return null;
        }

        // Transform criteria data for easier use in the view
        $transformedCriteria = [];
        foreach ($criteria as $key => $criteriaData) {
            if (isset($criteriaData['categories'])) {
                foreach ($criteriaData['categories'] as $category) {
                    $mainCriteria = [];
                    if (isset($category['main_criteria'])) {
                        foreach ($category['main_criteria'] as $main) {
                            $subCriteria = [];
                            if (isset($main['sub_criteria'])) {
                                foreach ($main['sub_criteria'] as $sub) {
                                    $subCriteria[] = [
                                        'name' => $sub['name'],
                                        'percentage' => $sub['percentage']
                                    ];
                                }
                            }
                            $mainCriteria[] = [
                                'name' => $main['name'],
                                'percentage' => $main['percentage'],
                                'sub_criteria' => $subCriteria
                            ];
                        }
                    }
                    $transformedCriteria[$category['category_name']] = $mainCriteria;
                }
            }
        }

        return $transformedCriteria;
    }

    protected function getEventContestants($eventName)
    {
        $contestantsRef = $this->database->getReference('contestants');
        $contestants = $contestantsRef->orderByChild('ename')
                                    ->equalTo($eventName)
                                    ->getValue();

        if (!$contestants) {
            return [];
        }

        // Transform contestants data
        $transformedContestants = [];
        foreach ($contestants as $key => $contestant) {
            $transformedContestants[] = [
                'id' => $key,
                'number' => $key, // You might want to add a specific number field in your database
                'name' => $contestant['cfname'] . ' ' . $contestant['cmname'] . ' ' . $contestant['clname'],
                'category' => $contestant['category'] ?? 'Default Category'
            ];
        }

        return $transformedContestants;
    }

    public function saveScore(Request $request)
    {
        $request->validate([
            'contestant_id' => 'required',
            'scores' => 'required|array'
        ]);

        $judgeId = session('user.id');
        $eventName = session('user.event_name');

        try {
            $scoreRef = $this->database->getReference("scores/{$eventName}/{$request->contestant_id}/{$judgeId}");
            $scoreRef->set([
                'scores' => $request->scores,
                'timestamp' => ['.sv' => 'timestamp'],
                'judge_id' => $judgeId
            ]);

            return response()->json(['message' => 'Score saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save score'], 500);
        }
    }
}