<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;

class AdminScoreController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function score()
    {
        try {
            // Get all events for the dropdown filter
            $events = $this->database->getReference('events')->getValue() ?? [];
            $eventsList = [];
            foreach ($events as $key => $event) {
                if (isset($event['ename'])) {
                    $eventsList[] = [
                        'id' => $event['ename'],
                        'name' => $event['ename']
                    ];
                }
            }

            // Get all tabulation scores
            $processedScores = [];
            $tabulationRef = $this->database->getReference('tabulation')->getValue();

            if ($tabulationRef) {
                foreach ($tabulationRef as $judgeId => $judgeData) {
                    foreach ($judgeData as $eventName => $eventData) {
                        if (!isset($eventData['judge_name'])) continue;

                        foreach ($eventData['judge_name'] as $judgeName => $judgeScores) {
                            if (!isset($judgeScores['tabulation_scores']['contestant_name'])) continue;

                            foreach ($judgeScores['tabulation_scores']['contestant_name'] as $contestantName => $contestantData) {
                                foreach ($contestantData['category'] as $categoryName => $categoryData) {
                                    if (!isset($categoryData['scores'])) continue;

                                    $totalScore = 0;
                                    foreach ($categoryData['scores']['scores'] as $mainScores) {
                                        foreach ($mainScores as $score) {
                                            $totalScore += floatval($score);
                                        }
                                    }

                                    $processedScores[] = [
                                        'judge_id' => $judgeId,
                                        'judge_name' => $judgeName,
                                        'event_name' => $eventName,
                                        'contestant_name' => $contestantName,
                                        'category' => $categoryName,
                                        'scores' => $categoryData['scores'],
                                        'total_score' => $totalScore,
                                        'submission_date' => $categoryData['scores']['date_submitted'] ?? 'N/A'
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            // Handle event filtering
            if (request('event_filter') && request('event_filter') !== 'all') {
                $filterEvent = request('event_filter');
                $processedScores = array_filter($processedScores, function($score) use ($filterEvent) {
                    return $score['event_name'] === $filterEvent;
                });
            }

            // Handle search
            if (request('search')) {
                $search = strtolower(request('search'));
                $processedScores = array_filter($processedScores, function($score) use ($search) {
                    return str_contains(strtolower($score['contestant_name']), $search) ||
                           str_contains(strtolower($score['judge_name']), $search) ||
                           str_contains(strtolower($score['event_name']), $search);
                });
            }

            // Sort scores
            $sortBy = request('sort', 'newest');
            usort($processedScores, function($a, $b) use ($sortBy) {
                if ($sortBy === 'newest') {
                    return strtotime($b['submission_date']) - strtotime($a['submission_date']);
                }
                return strtotime($a['submission_date']) - strtotime($b['submission_date']);
            });

            // Convert to collection for the view
            $scores = collect($processedScores);

            return view('firebase.admin.scores', [
                'scores' => $scores,
                'eventsList' => collect($eventsList)
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in AdminScoreController: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Return view with error message
            return view('firebase.admin.scores', [
                'scores' => collect([]),
                'eventsList' => collect([]),
                'error' => 'An error occurred while loading the scores.'
            ]);
        }
    }
}