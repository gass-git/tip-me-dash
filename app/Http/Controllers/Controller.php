<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function show_welcome(){
        $newcomers = User::orderBy('id','DESC')->get();
        $ranks = User::orderBy('points','DESC')->get();
        return view('welcome',compact('newcomers','ranks'));
    }

}
