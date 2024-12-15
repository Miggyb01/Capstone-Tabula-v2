<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;

class JudgeEventController extends Controller
{
    protected $database;
    protected $storage;
    
    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }
    
    public function dashboard()
    {
        $judgeName = session('user.name');
        $eventName = session('user.event_name');
        
        if (!$eventName) {
            return view('firebase.judge.judgedashboard')->with('error', 'No event assigned');
        }

        // Get event details
        $eventDetails = $this->database->getReference('events')
            ->orderByChild('ename')
            ->equalTo($eventName)
            ->getValue();

        if (!$eventDetails) {
            return view('firebase.judge.judgedashboard')->with('error', 'Event not found');
        }

        // Get the first (and should be only) event
        $eventData = current($eventDetails);
        
        // Get image URL if banner exists
        if (isset($eventData['ebanner'])) {
            try {
                $bucket = $this->storage->getBucket();
                $bannerRef = $bucket->object('events/banners/' . $eventData['ebanner']);
                
                if ($bannerRef->exists()) {
                    $expiresAt = new \DateTime('tomorrow');
                    $eventData['banner_url'] = $bannerRef->signedUrl($expiresAt);
                }
            } catch (\Exception $e) {
                // Log error but continue without the image
                \Log::error('Failed to get banner URL: ' . $e->getMessage());
            }
        }
        
        return view('firebase.judge.judgedashboard', compact('eventData'));
    }
}