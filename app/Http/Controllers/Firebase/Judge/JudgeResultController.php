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

    public function result()
    {
        \Log::info('Judge Result method called');
        
        try {
            // Get current judge's event and details
            $judgeId = session('user.id');
            $judgeName = session('user.name');
            $eventName = session('user.event_name');

            \Log::info('Session data:', [
                'judgeId' => $judgeId,
                'judgeName' => $judgeName,
                'eventName' => $eventName
            ]);

            if (!$eventName) {
                return view('firebase.judge.judge-result', [
                    'eventDetails' => null,
                    'categories' => [],
                    'rankings' => [],
                    'judgeName' => $judgeName,
                    'error' => 'No event assigned to your account'
                ]);
            }

            // Get event details
            $eventRef = $this->database->getReference('events')
                ->orderByChild('ename')
                ->equalTo($eventName)
                ->getValue();

            if (!$eventRef) {
                return view('firebase.judge.judge-result', [
                    'eventDetails' => null,
                    'categories' => [],
                    'rankings' => [],
                    'judgeName' => $judgeName,
                    'error' => 'Event not found'
                ]);
            }

            $eventDetails = current($eventRef);

            // Get categories from criteria
            $criteriaRef = $this->database->getReference('criterias')
                ->orderByChild('ename')
                ->equalTo($eventName)
                ->getValue();

            $categories = [];
            if ($criteriaRef) {
                $criteria = current($criteriaRef);
                if (isset($criteria['categories'])) {
                    foreach ($criteria['categories'] as $category) {
                        if (isset($category['category_name'])) {
                            $categories[] = $category['category_name'];
                        }
                    }
                }
            }

            // Get contestants
            $contestantsRef = $this->database->getReference('contestants')
                ->orderByChild('ename')
                ->equalTo($eventName)
                ->getValue() ?? [];

            // Initialize contestant scores array
            $contestantScores = [];
            foreach ($contestantsRef as $cKey => $contestant) {
                $fullName = trim(
                    ($contestant['cfname'] ?? '') . ' ' . 
                    ($contestant['cmname'] ?? '') . ' ' . 
                    ($contestant['clname'] ?? '')
                );
                
                $contestantScores[$fullName] = [
                    'id' => $cKey,
                    'name' => $fullName,
                    'number' => $contestant['number'] ?? 'N/A',
                    'category_scores' => array_fill_keys($categories, 0),
                    'total_score' => 0
                ];
            }

            // Get scores for this judge and event
            $scoresRef = sprintf(
                'tabulation/%s/%s/judge_name/%s/tabulation_scores/contestant_name',
                $judgeId,
                $eventName,
                $judgeName
            );
            
            $scores = $this->database->getReference($scoresRef)->getValue() ?? [];

            // Calculate scores
            foreach ($scores as $contestantName => $categoryScores) {
                if (isset($contestantScores[$contestantName]) && isset($categoryScores['category'])) {
                    foreach ($categoryScores['category'] as $categoryName => $scoreData) {
                        if (isset($scoreData['scores'])) {
                            $categoryTotal = 0;
                            foreach ($scoreData['scores'] as $mainCriteria) {
                                foreach ($mainCriteria as $score) {
                                    $categoryTotal += floatval($score);
                                }
                            }
                            $contestantScores[$contestantName]['category_scores'][$categoryName] = $categoryTotal;
                            $contestantScores[$contestantName]['total_score'] += $categoryTotal;
                        }
                    }
                }
            }

            // Sort by total score (highest to lowest)
            uasort($contestantScores, function($a, $b) {
                return $b['total_score'] <=> $a['total_score'];
            });

            // Remove contestants with no scores
            $contestantScores = array_filter($contestantScores, function($contestant) {
                return $contestant['total_score'] > 0;
            });

            return view('firebase.judge.judge-result', [
                'eventDetails' => $eventDetails,
                'categories' => $categories,
                'rankings' => $contestantScores,
                'judgeName' => $judgeName
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in judge results:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('firebase.judge.judge-result', [
                'eventDetails' => null,
                'categories' => [],
                'rankings' => [],
                'judgeName' => $judgeName ?? null,
                'error' => 'Error retrieving results: ' . $e->getMessage()
            ]);
        }
    }
}