<?php

namespace App\Http\Controllers;

use App\Job;
use App\Level;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index() {
        $jobs = Job::with(['competences'])->get()->toArray();
        $levels = Level::all();

        return view('index', [
            'jobs' => $jobs,
            'levels' => $levels
        ]);
    }
}
