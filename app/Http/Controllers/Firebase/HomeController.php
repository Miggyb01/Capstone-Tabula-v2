<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

use function PHPUnit\Framework\returnValueMap;

class HomeController extends Controller
{
    protected $firebaseAuth;
    public function index(){
        return view("firebase.homeindex");
    }

}