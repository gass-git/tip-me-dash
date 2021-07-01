<?php

namespace App\Http\Controllers;
use App\User;
use App\Tip;
use Illuminate\Support\Facades\Auth;
use DB;

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
        $events = tip::where('recipient_id',Auth::user()->id)->where('status','confirmed')->paginate(5);
        return view('dashboard',compact('events'));
    }
}
