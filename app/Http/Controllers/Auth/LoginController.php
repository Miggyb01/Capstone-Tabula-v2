<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    private $auth;
    private $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = $auth;
        $this->database = $database;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            // Admin login check
            if ($credentials['email'] === 'admin@admin.com' && $credentials['password'] === 'admin123') {
                Session::put('user', [
                    'role' => 'admin',
                    'email' => $credentials['email']
                ]);
                return redirect()->route('admin.dashboard');
            }

            // Judge login check
            $judges = $this->database->getReference('judges')->getValue();
            
            if ($judges) {
                foreach ($judges as $judgeId => $judge) {
                    if ($judge['jusername'] === $credentials['email'] && 
                        $judge['jpassword'] === $credentials['password']) {
                        
                        Session::put('user', [
                            'role' => 'judge',
                            'id' => $judgeId,
                            'name' => $judge['jfname'] . ' ' . $judge['jlname'],
                            'event_name' => $judge['event_name'] ?? null,
                            'username' => $judge['jusername']
                        ]);

                        return redirect()->route('judge.dashboard');
                    }
                }
            }

            // Organizer login check
            try {
                $signInResult = $this->auth->signInWithEmailAndPassword(
                    $credentials['email'],
                    $credentials['password']
                );

                $uid = $signInResult->data()['localId'];
                $organizer = $this->database->getReference('organizers/' . $uid)->getValue();

                if ($organizer) {
                    // Check email verification
                    $user = $this->auth->getUser($uid);
                    if (!$user->emailVerified) {
                        throw new \Exception('Please verify your email before logging in.');
                    }

                    // Check account status
                    if ($organizer['status'] !== 'active') {
                        throw new \Exception('Your account is pending activation.');
                    }

                    Session::put('user', [
                        'role' => 'organizer',
                        'id' => $uid,
                        'name' => $organizer['full_name'],
                        'email' => $organizer['email'],
                        'username' => $organizer['username']
                    ]);

                    return redirect()->route('organizer.dashboard');
                }
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'verify your email')) {
                    throw new \Exception('Please verify your email before logging in.');
                } elseif (str_contains($e->getMessage(), 'pending activation')) {
                    throw new \Exception('Your account is pending activation.');
                } else {
                    // Log the actual error for debugging but show a generic message to the user
                    \Log::error('Firebase Auth Error: ' . $e->getMessage());
                }
            }

            // If no successful login was found
            throw new \Exception('Invalid username or password.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['login' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            // Get current user role before clearing session
            $userRole = Session::get('user.role');

            // Clear the session
            Session::flush();

            // Firebase sign out for organizers
            if ($userRole === 'organizer') {
                $this->auth->signOut();
            }

            return redirect()->route('login')
                ->with('success', 'Successfully logged out.');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'An error occurred during logout.');
        }
    }

    protected function checkUserStatus($uid)
    {
        try {
            $user = $this->auth->getUser($uid);
            if (!$user->emailVerified) {
                return [
                    'status' => false,
                    'message' => 'Please verify your email before logging in.'
                ];
            }
            return ['status' => true];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Error checking user status.'
            ];
        }
    }
}