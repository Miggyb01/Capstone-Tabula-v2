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

    public function index(Request $request)
    {
        // Get the current judge's event from session
        $judgeEventName = session('user.event_name');
        
        if (!$judgeEventName) {
            return response()->json(['error' => 'No event assigned'], 400);
        }

        // Get event details
        $eventDetails = $this->database->getReference('events')
            ->orderByChild('ename')
            ->equalTo($judgeEventName)
            ->getValue();

        if (!$eventDetails) {
            return redirect()->back()->with('error', 'Event not found');
        }
        
        // Get event criteria
        $criteria = $this->getEventCriteria($judgeEventName);
        if (!$criteria) {
            return redirect()->back()->with('error', 'No criteria found for this event');
        }
        
        // Get contestants
        $contestants = $this->getEventContestants($judgeEventName);

        // Get all categories
        $categories = array_keys($criteria);

        // Get current category from request or use first category
        $currentCategory = $request->query('category', reset($categories));
        
        // If category is not valid, default to first category
        if (!in_array($currentCategory, $categories)) {
            $currentCategory = reset($categories);
        }

        return view('firebase.judge.judge-tabulation', [
            'eventName' => $judgeEventName,
            'eventDetails' => $eventDetails,
            'criteria' => $criteria,
            'contestants' => $contestants,
            'currentCategory' => $currentCategory,
            'categories' => $categories
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

        $transformedCriteria = [];
        foreach ($criteria as $criteriaData) {
            if (isset($criteriaData['categories'])) {
                foreach ($criteriaData['categories'] as $category) {
                    $categoryData = [
                        'name' => $category['category_name'],
                        'details' => $category['criteria_details'] ?? '',
                        'main_criteria' => []
                    ];
                    
                    if (isset($category['main_criteria'])) {
                        foreach ($category['main_criteria'] as $main) {
                            $mainData = [
                                'name' => $main['name'],
                                'percentage' => $main['percentage'],
                                'sub_criteria' => []
                            ];
                            
                            if (isset($main['sub_criteria'])) {
                                foreach ($main['sub_criteria'] as $sub) {
                                    $mainData['sub_criteria'][] = [
                                        'name' => $sub['name'],
                                        'percentage' => $sub['percentage']
                                    ];
                                }
                            }
                            
                            $categoryData['main_criteria'][] = $mainData;
                        }
                    }
                    
                    $transformedCriteria[$category['category_name']] = $categoryData;
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

        $transformedContestants = [];
        $counter = 1; // Initialize counter for contestant numbers

        // Convert to array and maintain creation order
        foreach ($contestants as $key => $contestant) {
            // Ensure all required fields exist, use empty string as fallback
            $firstName = $contestant['cfname'] ?? '';
            $middleName = $contestant['cmname'] ?? '';
            $lastName = $contestant['clname'] ?? '';

            // Build full name - trim spaces and handle empty middle name
            $fullName = $firstName;
            if (!empty($middleName)) {
                $fullName .= ' ' . $middleName;
            }
            if (!empty($lastName)) {
                $fullName .= ' ' . $lastName;
            }

            // Create contestant data array
            $transformedContestants[] = [
                'id' => $key,
                'number' => $counter++, // Add sequential number
                'name' => trim($fullName), // Remove any extra spaces
                'fname' => $firstName,
                'mname' => $middleName,
                'lname' => $lastName,
                'unique_code' => '#-' . substr(md5($key), 0, 20),
                'event_name' => $contestant['ename'] ?? '',
            ];
        }

        // Sort by number to ensure consistent order
        usort($transformedContestants, function($a, $b) {
            return $a['number'] <=> $b['number'];
        });

        return $transformedContestants;
    }

    public function saveScore(Request $request)
    {
        $request->validate([
            'contestant_id' => 'required',
            'category' => 'required',
            'scores' => 'required|array'
        ]);

        $judgeId = session('user.id');
        $eventName = session('user.event_name');

        try {
            // First validate if all scores are within range
            foreach ($request->scores as $mainCriteria) {
                foreach ($mainCriteria as $score) {
                    if ($score < 0 || $score > 100) {
                        throw new \Exception('Scores must be between 0 and 100');
                    }
                }
            }

            // Save the scores
            $scoreRef = $this->database->getReference("scores/{$eventName}/{$request->contestant_id}/{$judgeId}/{$request->category}");
            $scoreData = [
                'scores' => $request->scores,
                'timestamp' => ['.sv' => 'timestamp'],
                'judge_id' => $judgeId,
                'event_name' => $eventName,
                'category' => $request->category
            ];

            $scoreRef->set($scoreData);

            return response()->json([
                'message' => 'Score saved successfully',
                'data' => $scoreData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save score',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getContestantScores($contestantId, $category)
    {
        try {
            $eventName = session('user.event_name');
            $judgeId = session('user.id');

            $scoreRef = $this->database->getReference("scores/{$eventName}/{$contestantId}/{$judgeId}/{$category}");
            $scores = $scoreRef->getValue();

            return response()->json([
                'success' => true,
                'data' => $scores
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve scores'
            ], 500);
        }
    }
}