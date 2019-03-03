<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use Auth;

class ScheduleSettingsController extends Controller
{
    private $schedules = [
        'CRON-rating-reminder',
        'CRON-sameip',
        'CRON-remind-user-flagged',
        'CRON-remind-hunter-hourly',
        'CRON-grab-new-complaints'
    ];
    public function index()
    {
        $schedules = $this->schedules;
        return view('moderator.schedule')->with('schedules', $schedules);
    }

    public function toggle($name)
    {
        info("User toggled " . $name . Auth::user());
        Cache::set($name, 
            !Cache::get($name, true), 60
        );
        
        return back();
    }
}