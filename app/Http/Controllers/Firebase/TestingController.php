<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Exception\AuthException;

class TestingController extends Controller
{
    protected $firebaseAuth;
    public function test(){
        return view("firebase.Registration.test");
    }

}
