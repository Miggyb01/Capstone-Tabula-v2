<?php

namespace App\Http\Controllers\Firebase\Judge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JudgeEventController extends Controller
{
    public function dashboard()
    {
        return view('firebase.judge.judgedashboard');
    }
}
