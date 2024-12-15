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
            if ($credentials['email'] === 'admin@admin.com' && $credentials['password'] === '!admin123') {
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

            // Organizer login
            try {
                $signInResult = $this->auth->signInWithEmailAndPassword(
                    $credentials['email'],
                    $credentials['password']
                );

                $uid = $signInResult->data()['localId'];
                
                // Get Firebase user
                $firebaseUser = $this->auth->getUser($uid);
                
                // Get user data from database
                $organizerRef = $this->database->getReference('user_organizer')->getChild($uid);
                $organizer = $organizerRef->getValue();

                if (!$organizer || !isset($organizer['user_info'])) {
                    throw new \Exception('User account not found.');
                }

                $userInfo = $organizer['user_info'];

                // If email is verified in Firebase but status is still pending in database,
                // update the status to active
                if ($firebaseUser->emailVerified && $userInfo['status'] === 'pending') {
                    $organizerRef->getChild('user_info')->update([
                        'status' => 'active',
                        'email_verified_at' => ['.sv' => 'timestamp']
                    ]);
                    $userInfo['status'] = 'active'; // Update local variable
                }

                // Now check status
                if ($userInfo['status'] === 'pending') {
                    if (!$firebaseUser->emailVerified) {
                        // Resend verification email if needed
                        $this->auth->sendEmailVerificationLink($credentials['email']);
                        throw new \Exception('Please verify your email first. A new verification link has been sent.');
                    }
                }

                if ($userInfo['status'] !== 'active') {
                    throw new \Exception('Your account is not active. Please verify your email.');
                }

                // Set session data
                Session::put('user', [
                    'role' => 'organizer',
                    'id' => $uid,
                    'name' => $userInfo['full_name'],
                    'email' => $userInfo['email'],
                    'username' => $userInfo['username']
                ]);

                return redirect()->route('organizer.dashboard');

            } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
                return back()->withInput()->withErrors(['login' => 'Invalid password.']);
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                return back()->withInput()->withErrors(['login' => 'User not found.']);
            } catch (\Exception $e) {
                return back()->withInput()->withErrors(['login' => $e->getMessage()]);
            }

        } catch (\Exception $e) {
            \Log::error('Login error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['login' => 'Login failed. Please check your credentials.']);
        }
    }

    public function completeVerification(Request $request)
    {
        try {
            $tempLogin = Session::get('temp_login');
            if (!$tempLogin) {
                return redirect()->route('login')
                    ->withErrors(['login' => 'Login session expired. Please try again.']);
            }

            // Get latest user data
            $user = $this->auth->getUser($tempLogin['uid']);
            
            if (!$user->emailVerified) {
                return redirect()->route('login')
                    ->withErrors(['login' => 'Please verify your email before continuing.']);
            }

            // Set full session data
            Session::put('user', [
                'role' => 'organizer',
                'id' => $tempLogin['uid'],
                'name' => $tempLogin['full_name'],
                'email' => $tempLogin['email'],
                'username' => $tempLogin['username']
            ]);

            Session::forget('temp_login');

            return redirect()->route('organizer.dashboard')
                ->with('success', 'Login successful!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['login' => 'Verification failed: ' . $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            $userRole = Session::get('user.role');
            Session::flush();

            return redirect()->route('login')
                ->with('success', 'Successfully logged out.');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'An error occurred during logout.');
        }
    }
}