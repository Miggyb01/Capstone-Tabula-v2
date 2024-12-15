<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

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
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Check for existing email in Firebase Auth
            try {
                $existingUser = $this->auth->getUserByEmail($request->email);
                if ($existingUser) {
                    return back()
                        ->withInput()
                        ->withErrors(['email' => 'Email already registered.']);
                }
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                // Email doesn't exist, which is what we want
            }

            // Check for existing username by checking user_info in each organizer record
            $existingUsers = $this->database->getReference('user_organizer')->getValue();
            $usernameExists = false;
            
            if ($existingUsers) {
                foreach ($existingUsers as $user) {
                    if (isset($user['user_info']['username']) && 
                        strtolower($user['user_info']['username']) === strtolower($request->username)) {
                        $usernameExists = true;
                        break;
                    }
                }
            }

            if ($usernameExists) {
                return back()
                    ->withInput()
                    ->withErrors(['username' => 'This username is already taken.']);
            }

            // Create user in Firebase Authentication
            $userProperties = [
                'email' => $request->email,
                'emailVerified' => false,
                'password' => $request->password,
                'displayName' => $request->full_name,
            ];

            $createdUser = $this->auth->createUser($userProperties);

            // Prepare organizer data structure with user_info subfolder
            $organizerData = [
                'user_info' => [
                    'full_name' => $request->full_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'role' => 'organizer',
                    'status' => 'pending',
                    'created_at' => ['.sv' => 'timestamp'],
                    'uid' => $createdUser->uid
                ]
            ];

            // Store data in database under user_organizer/{uid}
            $databaseResponse = $this->database
                ->getReference('user_organizer')
                ->getChild($createdUser->uid)
                ->set($organizerData);

            if ($databaseResponse === null) {
                // Database write failed, cleanup the created auth user
                $this->auth->deleteUser($createdUser->uid);
                throw new \Exception('Failed to create user profile. Please try again.');
            }

            // Send verification email
            $this->auth->sendEmailVerificationLink($request->email);

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please check your email for verification.');

        } catch (\Exception $e) {
            // If we created a user but something else failed, clean up
            if (isset($createdUser)) {
                try {
                    $this->auth->deleteUser($createdUser->uid);
                } catch (\Exception $deleteException) {
                    \Log::error('Failed to delete user after registration error', [
                        'error' => $deleteException->getMessage()
                    ]);
                }
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function verifyEmail($token)
    {
        try {
            // Verify the token and get user info
            $verificationResult = $this->auth->verifyEmailVerificationToken($token);
            $uid = $verificationResult->data()['sub'];

            // Update user status in database under user_info
            $updateResult = $this->database
                ->getReference('user_organizer')
                ->getChild($uid)
                ->getChild('user_info')
                ->update([
                    'status' => 'active',
                    'email_verified_at' => ['.sv' => 'timestamp']
                ]);

            if ($updateResult === null) {
                throw new \Exception('Failed to update user status.');
            }

            return redirect()->route('login')
                ->with('success', 'Email verified successfully! You can now log in.');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Email verification failed: ' . $e->getMessage());
        }
    }
}