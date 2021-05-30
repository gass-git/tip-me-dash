<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function show_welcome(){

        $newcomers = DB::table('users')->orderBy('id','DESC')->get();
        $ranking = DB::table('users')->orderBy('reputation_score','DESC')->get();

        return view('welcome',compact('newcomers','ranking'));
    }

}
