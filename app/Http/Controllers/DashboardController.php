<?php

namespace App\Http\Controllers;
use App\Log;
use App\Tip;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $IP = request()->ip();

        // keep the user ip updated
        if(Auth::user()->ip != $IP){
            User::find(Auth::user()->id)->update(['ip'=> $IP]);
        }

        $confirmed_tips = Tip::where('recipient_id', Auth::user()->id)
                            ->where('status','confirmed');
        
        $dash_30_days = $confirmed_tips
                        ->whereDate('created_at', '>', Carbon::now()->subDays(30))
                        ->sum('dash_amount');
        
        $usd_30_days = $confirmed_tips
                        ->whereDate('created_at', '>', Carbon::now()->subDays(30))
                        ->sum('usd_equivalent');

        $events = Log::where('to_id', Auth::user()->id)
                    ->orderBy('id','DESC')
                    ->paginate(10);
        
        $dash_all_time = $confirmed_tips->sum('dash_amount');
        $usd_all_time = $confirmed_tips->sum('usd_equivalent');
        $number_of_events = Log::where('to_id', Auth::user()->id)->count();
        
        return view('dashboard',compact(
            'events',
            'number_of_events',
            'confirmed_tips',
            'dash_30_days',
            'usd_30_days',
            'dash_all_time',
            'usd_all_time'
        ));
    }
}
