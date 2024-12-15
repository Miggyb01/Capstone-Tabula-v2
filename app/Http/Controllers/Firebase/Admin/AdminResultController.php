<?php
// AdminResultController.php
namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class AdminResultController extends Controller 
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function result(Request $request)
    {
        // Get all events
        $events = $this->database->getReference('events')->getValue() ?? [];

        // Get tabulation data
        $tabulation = $this->database->getReference('tabulation')->getValue() ?? [];
        
        $rankings = [];
        $selectedEvent = $request->get('event_filter');

        foreach ($tabulation as $judgeId => $judgeData) {
            if (isset($judgeData['judge_name'])) {
                foreach ($judgeData['judge_name'] as $judgeName => $judgeScores) {
                    if (isset($judgeScores['tabulation_scores']['contestant_name'])) {
                        foreach ($judgeScores['tabulation_scores']['contestant_name'] as $contestantName => $contestantData) {
                            if (isset($contestantData['category'])) {
                                foreach ($contestantData['category'] as $categoryData) {
                                    $eventName = $categoryData['scores']['event_name'] ?? '';
                                    
                                    // Filter by selected event if specified
                                    if ($selectedEvent && $selectedEvent !== 'all' && $eventName !== $selectedEvent) {
                                        continue;
                                    }

                                    $totalScore = 0;
                                    if (isset($categoryData['scores']['scores'])) {
                                        foreach ($categoryData['scores']['scores'] as $criteria) {
                                            foreach ($criteria as $score) {
                                                $totalScore += floatval($score);
                                            }
                                        }
                                    }

                                    $key = $contestantName . '-' . $eventName;
                                    if (!isset($rankings[$key])) {
                                        $rankings[$key] = [
                                            'contestant_name' => $contestantName,
                                            'event_name' => $eventName,
                                            'total_score' => $totalScore,
                                            'timestamp' => $categoryData['scores']['timestamp'] ?? time()
                                        ];
                                    } else {
                                        $rankings[$key]['total_score'] += $totalScore;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Convert to array
        $rankings = array_values($rankings);

        // Apply search filter
        if ($request->has('search')) {
            $searchTerm = strtolower($request->search);
            $rankings = array_filter($rankings, function($ranking) use ($searchTerm) {
                return str_contains(strtolower($ranking['contestant_name']), $searchTerm);
            });
        }

        // Sort by total score (highest to lowest)
        usort($rankings, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        // Apply top N filter
        $topFilter = $request->get('top_filter');
        if ($topFilter) {
            $limit = intval(str_replace('top', '', $topFilter));
            if (count($rankings) >= $limit) {
                $rankings = array_slice($rankings, 0, $limit);
            }
        }

        return view('firebase.admin.results', [
            'rankings' => $rankings,
            'events' => $events,
        ]);
    }
}