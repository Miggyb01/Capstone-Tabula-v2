<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class CheckOrganizerAccess
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function handle(Request $request, Closure $next)
    {
        if (session('user.role') !== 'organizer') {
            return $next($request);
        }

        $organizerId = session('user.id');
        $settings = $this->database->getReference("user_organizer/{$organizerId}/user_data/settings")
            ->getValue();

        if (isset($settings['access_status']) && $settings['access_status'] === 'restricted') {
            // Redirect to dashboard with message if trying to access restricted routes
            if ($this->isRestrictedRoute($request)) {
                return redirect()->route('organizer.dashboard')
                    ->with('error', 'Access restricted: Event is currently ongoing');
            }
        }

        return $next($request);
    }

    protected function isRestrictedRoute(Request $request)
    {
        $restrictedRoutes = [
            'organizer.event.setup',
            'organizer.event.list',
            'organizer.event.edit',
            'organizer.criteria.setup',
            'organizer.criteria.list',
            'organizer.criteria.edit',
            'organizer.contestant.setup',
            'organizer.contestant.list',
            'organizer.contestant.edit',
            'organizer.judge.setup',
            'organizer.judge.list',
            'organizer.judge.edit'
        ];

        return in_array($request->route()->getName(), $restrictedRoutes);
    }
}