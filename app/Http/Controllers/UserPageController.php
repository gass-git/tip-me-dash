<?php

namespace App\Http\Controllers;

use App\User;
use App\Tip;
use App\VisitsRecord;
use App\Log;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserPageController extends Controller
{
    
    function show($username){
        
        $page_owner = User::where('username', $username)->first();   
        $usd_amount = 5;                                             // Default USD amount to tip

        /* ---- If user page doesn't exist return an error of page not found ----- */
        if(!$page_owner){
            abort(403, 'Sorry, this user page does not exist.');
        }
        /* ----------------------------------------------------------------------- */


        /* ------- Add a page view to the page owner if the visitor ip is new ---- */
        $IP = request()->ip();
        $ip_on_record = VisitsRecord::where('page_owner_id',$page_owner->id)->where('ip',$IP)->first();

        if($ip_on_record == null){
            
             $data = array();
             $data['page_owner_id'] = $page_owner->id;
             $data['ip'] = $IP;
 
             VisitsRecord::insert($data);
 
             // Add a visit to the page owner
             $page_owner->page_views += 1;
             $page_owner->save();
        }
        // -------------------------------------------------------------------------- */

        return view('user_page', compact('page_owner','usd_amount'));
    }

    function praise(Request $req){

        $tip =  Tip::where('id',$req->id)->first();
        $current = $tip->praise;

        if($current == $req->praise){
            
            $tip->update(['praise' => null]);
            
            $event = Log::where('tip_id',$tip->id)->where('type','praise')->first();
            
            $event->delete();

        }else{

            $tip->update(['praise' => $req->praise]);

            $event = Log::where('tip_id', $tip->id)->where('type','praise')->first();
        
            $praise = $req->praise;

            if($praise == "like"){
                $p2p_detail = "liked your tip";
                $g_detail = "liked";
            }

            if($praise == "love"){
                $p2p_detail = "loved your tip";
                $g_detail = "loved";
            }

            if($praise == "brilliant"){
                $p2p_detail ="thinks it's brilliant";
                $g_detail = "thinks it's brilliant";
            }

            if($praise == "cheers"){
                $p2p_detail = "toasted";
                $g_detail = "toasted";
            }

            // If event already exists update it if not create one
            if($event){
                $event->update(['p2p_event' => $p2p_detail]);
                $event->update(['global_event' => $g_detail]);
            }else{
                $data = array();
                $data['tip_id'] = $req->id;
                $data['from_id'] = Auth::user()->id;
                $data['to_id'] = $tip->sender_id;
                $data['type'] = 'praise';
                $data['p2p_event'] = $p2p_detail;
                $data['global_event'] = $g_detail;
                $data['created_at'] = Carbon::now();
                DB::table('logs')->insert($data);
            }
        }
    }

    function upload_header_img(Request $req){

        $user = Auth::user();

        $img = $req->file('input');

        // If the user already has uploaded a header image delete it before uploading the new one
        if($user->header_img_name){
            Storage::delete('/public/header-pics/'.$user->header_img_name);
        }

        $img_new_name = date('dmy_H_s_i').'_'.$user->id.'_'.$img->getClientOriginalName();
        $img->storeAs('header-pics',$img_new_name,'public');
        $user->header_img_url = 'http://tipmedash.com/storage/header-pics/'.$img_new_name;
        $user->header_img_name = $img_new_name;
        $user->save();
        
        toast('Changes saved!','success');
        return redirect()->back();
    }

}
