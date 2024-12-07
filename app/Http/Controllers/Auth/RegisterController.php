<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->auth = $auth;
        $this->database = $database;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Add debugging
        Log::info('Registration attempt:', $request->except(['password', 'password_confirmation']));

        try {
            // Validate the request
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'username' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Step 1: Create user in Firebase Authentication
            $userProperties = [
                'email' => $validated['email'],
                'emailVerified' => false,
                'password' => $validated['password'],
                'displayName' => $validated['full_name'],
            ];

            Log::info('Creating user in Firebase Auth');
            $createdUser = $this->auth->createUser($userProperties);
            Log::info('User created in Firebase Auth', ['uid' => $createdUser->uid]);

            // Step 2: Store additional user data in Realtime Database
            $organizerData = [
                'full_name' => $validated['full_name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => 'organizer',
                'status' => 'pending',
                'created_at' => ['.sv' => 'timestamp'],
                'uid' => $createdUser->uid
            ];

            Log::info('Storing user data in Realtime Database');
            $this->database->getReference('organizers/' . $createdUser->uid)
                          ->set($organizerData);

            // Step 3: Generate verification email
            Log::info('Generating verification email');
            $verificationLink = $this->auth->getEmailVerificationLink($validated['email']);

            // Step 4: Store data in session and redirect
            session([
                'registration_pending' => [
                    'email' => $validated['email'],
                    'uid' => $createdUser->uid
                ]
            ]);

            Log::info('Registration successful, redirecting to verification notice');
            return redirect()->route('verify.email.notice');

        } catch (\Exception $e) {
            Log::error('Registration failed:', ['error' => $e->getMessage()]);
            
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function showVerificationNotice()
    {
        if (!session('registration_pending')) {
            return redirect()->route('register')
                ->withErrors(['error' => 'No pending registration found.']);
        }

        $email = session('registration_pending.email');
        return view('auth.verify-email', compact('email'));
    }

    public function resendVerification()
    {
        try {
            $registrationData = session('registration_pending');
            
            if (!$registrationData) {
                throw new \Exception('No pending registration found.');
            }

            $verificationLink = $this->auth->getEmailVerificationLink($registrationData['email']);
            
            return back()->with('success', 'Verification email has been resent.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}