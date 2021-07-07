<?php

namespace App\Http\Controllers;
use App\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = log::where('to_id',Auth::user()->id)->paginate(10);
        $number_of_events = log::where('to_id',Auth::user()->id)->count();
        return view('dashboard',compact('events','number_of_events'));
    }
}
