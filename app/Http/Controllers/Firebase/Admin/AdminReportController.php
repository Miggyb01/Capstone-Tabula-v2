<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        try {
            // Fetch summary data
            $summaryData = $this->getSummaryData();
            
            // Fetch activity statistics
            $monthlyData = $this->getMonthlyData();
            $weeklyData = $this->getWeeklyData();
            
            // Fetch analytics
            $organizerStats = $this->getOrganizerStats();
            $eventTypeDistribution = $this->getEventTypeDistribution();
            $judgeAssignmentStats = $this->getJudgeAssignmentStats();
            $contestantStats = $this->getContestantStats();

            // Debug logging
            \Log::info('Report Data:', [
                'monthly' => $monthlyData,
                'weekly' => $weeklyData
            ]);

            return view('firebase.admin.reports', [
                'summaryData' => $summaryData,
                'monthlyData' => $monthlyData,
                'weeklyData' => $weeklyData,
                'organizerStats' => $organizerStats,
                'eventTypeDistribution' => $eventTypeDistribution,
                'judgeAssignmentStats' => $judgeAssignmentStats,
                'contestantStats' => $contestantStats
            ]);
        } catch (\Exception $e) {
            \Log::error('Report generation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error generating reports: ' . $e->getMessage());
        }
    }

    protected function getSummaryData()
    {
        $events = $this->database->getReference('events')->getValue() ?? [];
        $judges = $this->database->getReference('judges')->getValue() ?? [];
        $contestants = $this->database->getReference('contestants')->getValue() ?? [];
        $criteria = $this->database->getReference('criterias')->getValue() ?? [];
        $organizers = $this->database->getReference('user_organizer')->getValue() ?? [];

        $activeEvents = array_filter($events, function($event) {
            return isset($event['edate']) && Carbon::parse($event['edate'])->isFuture();
        });

        return [
            'totalEvents' => count($events),
            'activeEvents' => count($activeEvents),
            'totalJudges' => count($judges),
            'totalContestants' => count($contestants),
            'totalCriteria' => count($criteria),
            'totalOrganizers' => count($organizers)
        ];
    }

    protected function getMonthlyData()
    {
        $events = $this->database->getReference('events')->getValue() ?? [];
        $judges = $this->database->getReference('judges')->getValue() ?? [];
        $contestants = $this->database->getReference('contestants')->getValue() ?? [];

        $monthlyStats = [];
        
        // Initialize last 12 months with zero values
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('M Y');
            $monthlyStats[$monthKey] = [
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'events' => 0,
                'judges' => 0,
                'contestants' => 0
            ];
        }

        // Process events
        foreach ($events as $eventId => $event) {
            if (isset($event['edate'])) {
                try {
                    $date = Carbon::parse($event['edate']);
                    $monthKey = $date->format('M Y');
                    if (isset($monthlyStats[$monthKey])) {
                        $monthlyStats[$monthKey]['events']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing event date for event ID {$eventId}: " . $e->getMessage());
                }
            }
        }

        // Process judges
        foreach ($judges as $judgeId => $judge) {
            $timestamp = null;
            if (isset($judge['created_at'])) {
                $timestamp = $judge['created_at'];
            } elseif (isset($judge['timestamp'])) {
                $timestamp = $judge['timestamp'];
            } elseif (isset($judge['date_added'])) {
                $timestamp = $judge['date_added'];
            }

            if ($timestamp) {
                try {
                    $date = is_numeric($timestamp) ? 
                        Carbon::createFromTimestamp($timestamp) : 
                        Carbon::parse($timestamp);
                    
                    $monthKey = $date->format('M Y');
                    if (isset($monthlyStats[$monthKey])) {
                        $monthlyStats[$monthKey]['judges']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing judge timestamp for judge ID {$judgeId}: " . $e->getMessage());
                }
            }
        }

        // Process contestants
        foreach ($contestants as $contestantId => $contestant) {
            $timestamp = null;
            if (isset($contestant['created_at'])) {
                $timestamp = $contestant['created_at'];
            } elseif (isset($contestant['timestamp'])) {
                $timestamp = $contestant['timestamp'];
            } elseif (isset($contestant['date_added'])) {
                $timestamp = $contestant['date_added'];
            }

            if ($timestamp) {
                try {
                    $date = is_numeric($timestamp) ? 
                        Carbon::createFromTimestamp($timestamp) : 
                        Carbon::parse($timestamp);
                    
                    $monthKey = $date->format('M Y');
                    if (isset($monthlyStats[$monthKey])) {
                        $monthlyStats[$monthKey]['contestants']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing contestant timestamp for contestant ID {$contestantId}: " . $e->getMessage());
                }
            }
        }

        return array_values($monthlyStats);
    }

    protected function getWeeklyData()
    {
        $events = $this->database->getReference('events')->getValue() ?? [];
        $judges = $this->database->getReference('judges')->getValue() ?? [];
        $contestants = $this->database->getReference('contestants')->getValue() ?? [];

        $weeklyStats = [];
        
        // Initialize last 8 weeks
        for ($i = 7; $i >= 0; $i--) {
            $date = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekKey = $date->format('M d');
            $weeklyStats[$weekKey] = [
                'week' => $date->format('M d'),
                'events' => 0,
                'judges' => 0,
                'contestants' => 0
            ];
        }

        // Process weekly events
        foreach ($events as $eventId => $event) {
            if (isset($event['edate'])) {
                try {
                    $date = Carbon::parse($event['edate'])->startOfWeek();
                    $weekKey = $date->format('M d');
                    if (isset($weeklyStats[$weekKey])) {
                        $weeklyStats[$weekKey]['events']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing weekly event date for event ID {$eventId}: " . $e->getMessage());
                }
            }
        }

        // Process weekly judges
        foreach ($judges as $judgeId => $judge) {
            $timestamp = null;
            if (isset($judge['created_at'])) {
                $timestamp = $judge['created_at'];
            } elseif (isset($judge['timestamp'])) {
                $timestamp = $judge['timestamp'];
            } elseif (isset($judge['date_added'])) {
                $timestamp = $judge['date_added'];
            }

            if ($timestamp) {
                try {
                    $date = is_numeric($timestamp) ? 
                        Carbon::createFromTimestamp($timestamp) : 
                        Carbon::parse($timestamp);
                    
                    $weekKey = $date->startOfWeek()->format('M d');
                    if (isset($weeklyStats[$weekKey])) {
                        $weeklyStats[$weekKey]['judges']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing weekly judge timestamp for judge ID {$judgeId}: " . $e->getMessage());
                }
            }
        }

        // Process weekly contestants
        foreach ($contestants as $contestantId => $contestant) {
            $timestamp = null;
            if (isset($contestant['created_at'])) {
                $timestamp = $contestant['created_at'];
            } elseif (isset($contestant['timestamp'])) {
                $timestamp = $contestant['timestamp'];
            } elseif (isset($contestant['date_added'])) {
                $timestamp = $contestant['date_added'];
            }

            if ($timestamp) {
                try {
                    $date = is_numeric($timestamp) ? 
                        Carbon::createFromTimestamp($timestamp) : 
                        Carbon::parse($timestamp);
                    
                    $weekKey = $date->startOfWeek()->format('M d');
                    if (isset($weeklyStats[$weekKey])) {
                        $weeklyStats[$weekKey]['contestants']++;
                    }
                } catch (\Exception $e) {
                    \Log::error("Error processing weekly contestant timestamp for contestant ID {$contestantId}: " . $e->getMessage());
                }
            }
        }

        return array_values($weeklyStats);
    }

    protected function getOrganizerStats()
    {
        $organizers = $this->database->getReference('user_organizer')->getValue() ?? [];
        $organizerStats = [];

        foreach ($organizers as $organizerId => $organizer) {
            if (isset($organizer['user_data'])) {
                $userData = $organizer['user_data'];
                $stats = [
                    'name' => $organizer['user_info']['full_name'] ?? 'Unknown',
                    'events' => count($userData['events'] ?? []),
                    'judges' => count($userData['judges'] ?? []),
                    'contestants' => count($userData['contestants'] ?? []),
                    'criteria' => count($userData['criterias'] ?? [])
                ];
                $organizerStats[] = $stats;
            }
        }

        return $organizerStats;
    }

    protected function getEventTypeDistribution()
    {
        $events = $this->database->getReference('events')->getValue() ?? [];
        $distribution = [];

        foreach ($events as $event) {
            $type = $event['etype'] ?? 'Uncategorized';
            if (!isset($distribution[$type])) {
                $distribution[$type] = 0;
            }
            $distribution[$type]++;
        }

        arsort($distribution);
        return $distribution;
    }

    protected function getJudgeAssignmentStats()
    {
        $judges = $this->database->getReference('judges')->getValue() ?? [];
        $stats = [
            'assigned' => 0,
            'unassigned' => 0,
            'assignments' => []
        ];

        foreach ($judges as $judge) {
            if (isset($judge['event_name']) && !empty($judge['event_name'])) {
                $stats['assigned']++;
                $eventName = $judge['event_name'];
                if (!isset($stats['assignments'][$eventName])) {
                    $stats['assignments'][$eventName] = 0;
                }
                $stats['assignments'][$eventName]++;
            } else {
                $stats['unassigned']++;
            }
        }

        arsort($stats['assignments']);
        return $stats;
    }

    protected function getContestantStats()
    {
        $contestants = $this->database->getReference('contestants')->getValue() ?? [];
        $stats = [
            'byAge' => [
                '18-25' => 0,
                '26-35' => 0,
                '36-45' => 0,
                '46+' => 0
            ],
            'byGender' => [
                'Male' => 0,
                'Female' => 0,
                'Other' => 0
            ]
        ];

        foreach ($contestants as $contestant) {
            // Count by age group
            $age = intval($contestant['cage'] ?? 0);
            if ($age >= 18 && $age <= 25) $stats['byAge']['18-25']++;
            elseif ($age <= 35) $stats['byAge']['26-35']++;
            elseif ($age <= 45) $stats['byAge']['36-45']++;
            else $stats['byAge']['46+']++;

            // Count by gender
            $gender = $contestant['cgender'] ?? 'Other';
            if (!isset($stats['byGender'][$gender])) {
                $gender = 'Other';
            }
            $stats['byGender'][$gender]++;
        }

        return $stats;
    }
}