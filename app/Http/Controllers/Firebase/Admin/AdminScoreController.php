<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // Get all events for dropdown
        $events = $this->database->getReference('events')->getValue() ?? [];
        
        // Get example data (to be replaced with real data later)
        $sampleData = [
            'total_contestants' => 10,
            'total_judges' => 5,
            'average_score' => 88.5
        ];

        return view('firebase.admin.scores', compact('events', 'sampleData'));
    }
}