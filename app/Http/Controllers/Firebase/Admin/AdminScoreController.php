<?php
// app/Http/Controllers/Firebase/Tabulation/ScoreController.php
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

    public function index()
    {
        // Sample data - replace with your Firebase data
        $scores = [
            [
                'id' => 1,
                'contestant_name' => 'Contestant 1',
                'total' => 92.5,
                'average' => 88.4
            ],
            [
                'id' => 2,
                'contestant_name' => 'Contestant 2',
                'total' => 90.2,
                'average' => 87.1
            ]
        ];

        $judgeScores = [
            [
                'name' => 'Judge 1',
                'submitted_count' => 12,
                'average' => 88.5,
                'last_updated' => '2024-02-24 10:30 AM'
            ]
        ];

        $criteriaScores = [
            [
                'name' => 'Production Number',
                'weight' => 30,
                'average' => 89.2,
                'highest' => 95.0,
                'lowest' => 82.5
            ]
        ];

        return view('firebase.tabulation.scores', compact('scores', 'judgeScores', 'criteriaScores'));
    }
}
