<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\AuthException;

class RegisterController extends Controller
{
    protected $firebaseAuth;
    public function registration(){
        return view("auth.register");
    }

    // app/Http/Controllers/Auth/RegisterController.php
}
