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
            'email' => 'required',    // This will accept username
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

            // If no match found
            throw new \Exception('Invalid credentials');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['login' => 'Invalid credentials. Please try again.']);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}