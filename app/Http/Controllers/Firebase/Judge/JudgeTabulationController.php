<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class JudgeTabulationController extends Controller
{
    protected $database, $tablename;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'tabulation';
    }

    public function index(Request $request)
    {
        $judgeEventName = session('user.event_name');
        
        if (!$judgeEventName) {
            return response()->json(['error' => 'No event assigned'], 400);
        }

        $eventDetails = $this->database->getReference('events')
            ->orderByChild('ename')
            ->equalTo($judgeEventName)
            ->getValue();

        if (!$eventDetails) {
            return redirect()->back()->with('error', 'Event not found');
        }
        
        $criteria = $this->getEventCriteria($judgeEventName);
        if (!$criteria) {
            return redirect()->back()->with('error', 'No criteria found for this event');
        }
        
        $contestants = $this->getEventContestants($judgeEventName);
        $submittedScores = $this->getSubmittedScores($judgeEventName);
        $categories = array_keys($criteria);
        $currentCategory = $request->query('category', reset($categories));
        
        if (!in_array($currentCategory, $categories)) {
            $currentCategory = reset($categories);
        }

        return view('firebase.judge.judge-tabulation', [
            'eventName' => $judgeEventName,
            'eventDetails' => $eventDetails,
            'criteria' => $criteria,
            'contestants' => $contestants,
            'currentCategory' => $currentCategory,
            'categories' => $categories,
            'submittedScores' => $submittedScores
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
        $counter = 1;

        foreach ($contestants as $key => $contestant) {
            $firstName = $contestant['cfname'] ?? '';
            $middleName = $contestant['cmname'] ?? '';
            $lastName = $contestant['clname'] ?? '';

            $fullName = $firstName;
            if (!empty($middleName)) {
                $fullName .= ' ' . $middleName;
            }
            if (!empty($lastName)) {
                $fullName .= ' ' . $lastName;
            }

            $transformedContestants[] = [
                'id' => $key,
                'number' => $counter++,
                'name' => trim($fullName),
                'fname' => $firstName,
                'mname' => $middleName,
                'lname' => $lastName,
                'unique_code' => '#-' . substr(md5($key), 0, 20),
                'event_name' => $contestant['ename'] ?? '',
            ];
        }

        usort($transformedContestants, function($a, $b) {
            return $a['number'] <=> $b['number'];
        });

        return $transformedContestants;
    }

    protected function getSubmittedScores($eventName)
    {
        $judgeName = session('user.name');
        $judgeId = session('user.id');
        
        $scoresRef = $this->database->getReference(sprintf(
            "%s/%s/%s/judge_name/%s/tabulation_scores",
            $this->tablename,
            $judgeId,
            $eventName,
            $judgeName
        ));
        
        return $scoresRef->getValue() ?? [];
    }

    public function saveScore(Request $request)
    {
        try {
            $request->validate([
                'contestant_id' => 'required',
                'contestant_name' => 'required',
                'category' => 'required',
                'scores' => 'required|array',
                'event_name' => 'required'
            ]);

            $judgeId = session('user.id');
            $judgeName = session('user.name');
            $eventName = $request->event_name;

            \Log::info('Starting score submission', [
                'event' => $eventName,
                'judge' => $judgeName,
                'contestant' => $request->contestant_name
            ]);

            $scorePath = sprintf(
                "%s/%s/%s/judge_name/%s/tabulation_scores/contestant_name/%s/category/%s/scores",
                $this->tablename,
                $judgeId,
                $eventName,
                $judgeName,
                $request->contestant_name,
                $request->category
            );

            $scoreRef = $this->database->getReference($scorePath);
            
            // Check for existing scores
            $existingScores = $scoreRef->getValue();
            if ($existingScores) {
                \Log::warning('Duplicate score submission attempt', [
                    'path' => $scorePath,
                    'existing_scores' => $existingScores
                ]);
                return response()->json([
                    'error' => 'Scores already submitted for this contestant'
                ], 400);
            }

            // Prepare score data
            $scoreData = [
                'scores' => $request->scores,
                'category_name' => $request->category,
                'contestant_id' => $request->contestant_id,
                'contestant_name' => $request->contestant_name,
                'date_submitted' => date('Y-m-d H:i:s'),
                'event_name' => $eventName,
                'judge_id' => $judgeId,
                'judge_name' => $judgeName,
                'timestamp' => ['.sv' => 'timestamp']
            ];

            // Save the scores
            $result = $scoreRef->set($scoreData);

            if (!$result) {
                throw new \Exception('Failed to save scores to database');
            }

            // Verify the save
            $savedData = $scoreRef->getValue();
            if (!$savedData) {
                throw new \Exception('Data verification failed after save');
            }

            \Log::info('Scores saved successfully', [
                'path' => $scorePath,
                'data' => $scoreData
            ]);

            return response()->json([
                'message' => 'Score saved successfully',
                'data' => $scoreData
            ]);

        } catch (\Exception $e) {
            \Log::error('Score submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to save score',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function getSubCriteriaPercentage($criteria, $category, $mainCriteriaName, $subCriteriaName)
    {
        if (!isset($criteria[$category])) return 0;
        
        foreach ($criteria[$category]['main_criteria'] as $mainCriteria) {
            if ($mainCriteria['name'] === $mainCriteriaName) {
                foreach ($mainCriteria['sub_criteria'] as $subCriteria) {
                    if ($subCriteria['name'] === $subCriteriaName) {
                        return $subCriteria['percentage'];
                    }
                }
            }
        }
        return 0;
    }
}