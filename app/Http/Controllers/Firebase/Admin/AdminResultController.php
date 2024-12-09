<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class AdminResultController extends Controller
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
        
        // Get example winners data (to be replaced with real data later)
        $winners = [
            ['name' => 'Contestant 1', 'score' => 92.8],
            ['name' => 'Contestant 2', 'score' => 89.5],
            ['name' => 'Contestant 3', 'score' => 87.3]
        ];

        return view('firebase.admin.results', compact('events', 'winners'));
    }
}