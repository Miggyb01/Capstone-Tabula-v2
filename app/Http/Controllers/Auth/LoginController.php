<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Database;

class LoginController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    
}