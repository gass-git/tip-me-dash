<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class NewComersController extends Controller
{
    public function show(){

        $newcomers = DB::table('users')->orderBy('id','desc')->get();

        return view('newcomers',compact('newcomers'));

    }
}
