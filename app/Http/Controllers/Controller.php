<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\User;
use App\Tip;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function show_welcome(){
        $newcomers = User::orderBy('id','DESC')->get();
        $ranks = User::orderBy('points','DESC')->get();
        return view('welcome',compact('newcomers','ranks'));
    }

    public function show_recent(){
        
        $tips_30_days = Tip::where('status','confirmed')
                        ->whereDate('created_at', '>', Carbon::now()->subDays(30))
                        ->count();

        $recent_tips = Tip::where('status','confirmed')
                    ->whereDate('created_at', '>', Carbon::now()->subDays(30))
                    ->orderBy('id','DESC')
                    ->paginate(10);

        return view('recent',compact('recent_tips','tips_30_days'));
    }
}
