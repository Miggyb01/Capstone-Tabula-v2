<?php

namespace App\Http\Controllers\Firebase\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class AdminTabulationPermissionController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        // Fetch all events with their details
        $events = $this->database->getReference('events')->getValue() ?? [];
        
        $eventDetails = [];
        foreach ($events as $eventId => $event) {
            // Get judges count
            $judges = $this->database->getReference('judges')
                ->orderByChild('event_name')
                ->equalTo($event['ename'])
                ->getValue() ?? [];

            // Get contestants count
            $contestants = $this->database->getReference('contestants')
                ->orderByChild('ename')
                ->equalTo($event['ename'])
                ->getValue() ?? [];

            // Get criteria categories
            $criteria = $this->database->getReference('criterias')
                ->orderByChild('ename')
                ->equalTo($event['ename'])
                ->getValue() ?? [];

            $categories = [];
            if ($criteria) {
                foreach (current($criteria)['categories'] as $category) {
                    $categories[] = $category['category_name'];
                }
            }

            $eventDetails[$eventId] = [
                'id' => $eventId,
                'name' => $event['ename'],
                'type' => $event['etype'],
                'date' => $event['edate'],
                'venue' => $event['evenue'],
                'status' => $event['status'] ?? 'upcoming',
                'current_category' => $event['current_category'] ?? null,
                'judges_count' => count($judges),
                'contestants_count' => count($contestants),
                'categories' => $categories,
                'organizer_id' => $event['organizer_id'] ?? null
            ];
        }

        return view('firebase.admin.tabulation.permission', compact('eventDetails'));
    }

    public function updateEventStatus(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:upcoming,ongoing,completed',
            'category' => 'required_if:status,ongoing'
        ]);

        $status = $request->status;
        $category = $request->category;

        // If changing to ongoing, verify all components exist
        if ($status === 'ongoing') {
            $event = $this->database->getReference("events/{$eventId}")->getValue();
            
            // Verify judges exist
            $judges = $this->database->getReference('judges')
                ->orderByChild('event_name')
                ->equalTo($event['ename'])
                ->getValue();
                
            if (!$judges) {
                return response()->json([
                    'error' => 'Cannot activate event: No judges assigned'
                ], 400);
            }

            // Verify contestants exist
            $contestants = $this->database->getReference('contestants')
                ->orderByChild('ename')
                ->equalTo($event['ename'])
                ->getValue();

            if (!$contestants) {
                return response()->json([
                    'error' => 'Cannot activate event: No contestants registered'
                ], 400);
            }

            // Verify criteria exists
            $criteria = $this->database->getReference('criterias')
                ->orderByChild('ename')
                ->equalTo($event['ename'])
                ->getValue();

            if (!$criteria) {
                return response()->json([
                    'error' => 'Cannot activate event: No criteria defined'
                ], 400);
            }

            // If changing categories, verify all judges have submitted scores
            if ($event['status'] === 'ongoing' && $event['current_category'] !== $category) {
                $allScoresSubmitted = $this->verifyAllScoresSubmitted($eventId, $event['current_category']);
                if (!$allScoresSubmitted) {
                    return response()->json([
                        'error' => 'Cannot change category: Not all judges have submitted complete scores'
                    ], 400);
                }
            }

            // Disable organizer access if event is going ongoing
            if ($event['organizer_id']) {
                $this->disableOrganizerAccess($event['organizer_id']);
            }
        }

        // Update event status and category
        $updates = [
            'status' => $status,
            'current_category' => $category,
            'updated_at' => ['.sv' => 'timestamp']
        ];

        $this->database->getReference("events/{$eventId}")->update($updates);

        return response()->json([
            'message' => 'Event status updated successfully',
            'status' => $status,
            'category' => $category
        ]);
    }

    protected function verifyAllScoresSubmitted($eventId, $category)
    {
        $event = $this->database->getReference("events/{$eventId}")->getValue();
        $judges = $this->database->getReference('judges')
            ->orderByChild('event_name')
            ->equalTo($event['ename'])
            ->getValue();
        
        $contestants = $this->database->getReference('contestants')
            ->orderByChild('ename')
            ->equalTo($event['ename'])
            ->getValue();

        foreach ($judges as $judgeId => $judge) {
            $scores = $this->database->getReference("tabulation/{$judgeId}/{$event['ename']}/judge_name/{$judge['jfname']} {$judge['jlname']}/tabulation_scores")
                ->getValue() ?? [];

            foreach ($contestants as $contestant) {
                $contestantScores = $scores['contestant_name'][$contestant['cfname'] . ' ' . $contestant['clname']]['category'][$category]['scores'] ?? [];
                if (empty($contestantScores)) {
                    return false;
                }

                // Verify all sub-criteria have scores
                foreach ($contestantScores as $mainCriteria) {
                    foreach ($mainCriteria as $score) {
                        if (!is_numeric($score)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    protected function disableOrganizerAccess($organizerId)
    {
        $updates = [
            'access_status' => 'restricted',
            'updated_at' => ['.sv' => 'timestamp']
        ];

        $this->database->getReference("user_organizer/{$organizerId}/user_data/settings")
            ->update($updates);
    }
}