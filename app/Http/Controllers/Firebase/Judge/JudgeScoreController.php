<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
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
        $judgeId = session('user.id');
        $judgeName = session('user.name');
        $eventName = session('user.event_name');

        if (!$eventName) {
            return redirect()->back()->with('error', 'No event assigned');
        }

        // Get all scores for this judge
        $scoresRef = $this->database->getReference('tabulation')
            ->getChild($judgeId)
            ->getChild($eventName)
            ->getChild('judge_name')
            ->getChild($judgeName)
            ->getChild('tabulation_scores')
            ->getValue();

        if (!$scoresRef) {
            return view('firebase.judge.judge-score', [
                'eventName' => $eventName,
                'scores' => [],
                'message' => 'No scores submitted yet'
            ]);
        }

        // Get criteria structure for this event
        $criteriaRef = $this->database->getReference('criterias')
            ->orderByChild('ename')
            ->equalTo($eventName)
            ->getValue();

        $criteriaStructure = [];
        if ($criteriaRef) {
            foreach ($criteriaRef as $criteria) {
                foreach ($criteria['categories'] as $category) {
                    $categoryName = $category['category_name'];
                    $criteriaStructure[$categoryName] = [
                        'name' => $categoryName,
                        'main_criteria' => []
                    ];

                    foreach ($category['main_criteria'] as $main) {
                        $criteriaStructure[$categoryName]['main_criteria'][$main['name']] = [
                            'percentage' => $main['percentage'],
                            'sub_criteria' => []
                        ];

                        foreach ($main['sub_criteria'] as $sub) {
                            $criteriaStructure[$categoryName]['main_criteria'][$main['name']]['sub_criteria'][$sub['name']] = [
                                'percentage' => $sub['percentage']
                            ];
                        }
                    }
                }
            }
        }

        // Process scores for display
        $processedScores = [];
        foreach ($scoresRef['contestant_name'] as $contestantName => $contestantData) {
            $contestantScores = [
                'name' => $contestantName,
                'categories' => []
            ];

            $totalScore = 0;
            $categoryCount = 0;

            foreach ($contestantData['category'] as $categoryName => $categoryData) {
                $categoryScores = [
                    'name' => $categoryName,
                    'main_criteria' => [],
                    'total' => 0
                ];

                foreach ($categoryData['scores']['scores'] as $mainName => $mainScores) {
                    $mainTotal = 0;
                    $subScores = [];

                    foreach ($mainScores as $subName => $score) {
                        $maxScore = $criteriaStructure[$categoryName]['main_criteria'][$mainName]['sub_criteria'][$subName]['percentage'];
                        $subScores[$subName] = [
                            'score' => $score,
                            'max_score' => $maxScore,
                            'percentage' => ($score / $maxScore) * 100
                        ];
                        $mainTotal += $score;
                    }

                    $maxMainScore = $criteriaStructure[$categoryName]['main_criteria'][$mainName]['percentage'];
                    $categoryScores['main_criteria'][$mainName] = [
                        'sub_scores' => $subScores,
                        'total' => $mainTotal,
                        'max_score' => $maxMainScore,
                        'percentage' => ($mainTotal / $maxMainScore) * 100
                    ];

                    $categoryScores['total'] += $mainTotal;
                }

                $contestantScores['categories'][$categoryName] = $categoryScores;
                $totalScore += $categoryScores['total'];
                $categoryCount++;
            }

            $contestantScores['total_score'] = $totalScore;
            $contestantScores['average_score'] = $categoryCount > 0 ? $totalScore / $categoryCount : 0;

            $processedScores[] = $contestantScores;
        }

        return view('firebase.judge.judge-score', [
            'eventName' => $eventName,
            'scores' => $processedScores,
            'criteriaStructure' => $criteriaStructure
        ]);
    }
}