<?php

namespace App\Http\Controllers;
use App\User;
use App\Log;
use App\Reputations;
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
        $recent_logs = DB::table('log')->where('to_user_id', Auth::user()->id)->orderBy('log_id','desc')->get();
        $users_liked = DB::table('reputations')->where('clicker_id', Auth::user()->id)->orderBy('id','desc')->paginate(3);
        return view('dashboard',compact('recent_logs','users_liked'));
    }
}
