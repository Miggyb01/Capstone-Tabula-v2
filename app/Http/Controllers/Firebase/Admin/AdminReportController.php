<?php
// app/Http/Controllers/Firebase/Tabulation/ReportController.php
namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;

class AdminReportController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $summary = [
            'total_contestants' => 12,
            'average_score' => 88.5,
            'total_judges' => 5
        ];

        return view('firebase.tabulation.reports', compact('summary'));
    }
}