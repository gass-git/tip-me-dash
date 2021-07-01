<?php

namespace App\Http\Controllers;

use App\User;
use App\Tip;
use App\VisitsRecord;
use DB;

class UserPageController extends Controller
{
    
    function show($username){
        
        $page_owner = User::where('username', $username)->first();   
        
        // Get page owner tips
        $tips = DB::table('tips')
                ->where('recipient_id',$page_owner->id)
                ->where('status','confirmed')
                ->orderBy('id','DESC')
                ->paginate(5);

        // Get total number of tips for page owner
        // Get page owner tips
        $number_of_tips = DB::table('tips')
                ->where('recipient_id',$page_owner->id)
                ->where('status','confirmed')
                ->count();

        // Hall of fame
        $biggest_tip = DB::table('tips')
                ->where('recipient_id',$page_owner->id)
                ->where('status','confirmed')
                ->orderBy('usd_equivalent','DESC')
                ->first();


        // Default USD amount to tip
        $usd_amount = 5;  

        // If user page doesn't exist return an error of page not found
        if(!$page_owner){
            abort(403, 'Sorry, this user page does not exist.');
        }

        // ------- Add a view to the page owner if the visitor ip is new ---------------------------------
        $ip = request()->ip();
        $ip_on_record = VisitsRecord::where('page_owner_id',$page_owner->id)->where('ip',$ip)->first();

        if($ip_on_record){
            // Do nothing
        }else{

            // Add ip to DB
            $data = array();
            $data['page_owner_id'] = $page_owner->id;
            $data['ip'] = $ip;

            VisitsRecord::insert($data);

            // Add a visit to the page owner
            $page_owner->page_views += 1;
            $page_owner->save();
        }
        // -------------------------------------------------------------------------------------------------

        // Push parameters to user page
        return view('user_page', compact('page_owner','usd_amount','tips','biggest_tip','number_of_tips'));
    }



}
