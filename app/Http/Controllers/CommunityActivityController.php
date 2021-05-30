<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CommunityActivityController extends Controller
{
    public function show(){

        $community_activity = DB::table('community_log')->orderBy('id','desc')->paginate(20);

        return view('community_activity',compact('community_activity'));
    }
}
