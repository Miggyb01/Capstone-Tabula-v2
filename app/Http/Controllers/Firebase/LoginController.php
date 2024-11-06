<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\AuthException;

class LoginController extends Controller
{
    protected $firebaseAuth;
    public function login(){
        return view("firebase.login.login");
    }
    public function loginuser(Request $request){
        $request->validate([
            "email"=> "required|email|max:99",
            "password"=> "required|string|max:99",
            
        ]);
        try {
            // Attempt to sign in the user
            $signInResult = $this->firebaseAuth->signInWithEmailAndPassword($request->email, $request->password);

            // Get the user's ID token
            $idToken = $signInResult->idToken();

            // Store token in session or as needed
            Session::put('firebase_user', $signInResult->data());

            // Redirect to a dashboard or home page
            return redirect()->route('home')->with('success', 'Login successful!');
        } catch (AuthException $e) {
            // Handle login failure
            return back()->withErrors(['email' => 'Login failed: ' . $e->getMessage()]);
        }
}
}
