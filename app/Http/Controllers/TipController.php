<?php

namespace App\Http\Controllers;

use App\User;
use App\Tip;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use DB;
use Carbon\Carbon;

class TipController extends Controller
{
    function process_tip(Request $req){
        
        $req->validate([
            'msg' => ['nullable','max:300'],
            'name' => ['nullable','max:25'],
        ]);
        $page_owner = User::where('username', $req->username)->first();

        if($page_owner->wallet_address == null){
            toast('Not possible, this user has not entered a wallet address yet.','info');
            return redirect()->back()->withInput();
        }

        // --- API - Get dash usd exchange rate ------------------
        $api = 'https://www.dashcentral.org/api/v1/public'; 
        $response = Http::get($api);
        $obj = json_decode($response);
        // -------------------------------------------------------

        $rand_decimal = rand (1, 10) / 100;                                     // Create a random decimal
        $dash_usd = round($obj->exchange_rates->dash_usd, 8) + $rand_decimal;   // Dash usd price plus the random decimal
        $usd_amount = $req->amount_entered;                                     // USD amount entered by supporter
        $dash_toSend = round( $usd_amount/$dash_usd, 8);                        // Dash conversion to send

       

        // The page owner cannot tip himself
        if(Auth::user() == $page_owner){
            toast('Why would you tip yourself?','info');
            return redirect()->back()->withInput();
        }

        // ---- Insert data in Tips table ------------------------
        $data = array();

        if(Auth::user()){
            $data['sender_id'] = Auth::user()->id;
        }elseif($req->name){
            $data['sent_by'] = $req->name;
        }

        if($req->msg){
            $data['message'] = $req->msg;
        }

        $data['recipient_id'] = $page_owner->id;
        $data['usd_equivalent'] = $usd_amount;
        $data['dash_amount'] = $dash_toSend;
        $data['status'] = 'not validated';
        $data['created_at'] = Carbon::now();

        DB::table('tips')->insert($data);
        // -------------------------------------------------------

        // Get page owner tips
        $tips = DB::table('tips')
                ->where('recipient_id',$page_owner->id)
                ->where('status','confirmed')
                ->orderBy('id','DESC')
                ->paginate(5);

        // Get page owner tips sent
        $tips_sent = DB::table('tips')
        ->where('sender_id',$page_owner->id)
        ->where('status','confirmed')
        ->orderBy('id','DESC')
        ->paginate(3);        

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

        $tip_id = Tip::max('id');
        $tip = Tip::where('id', $tip_id)->first();

        $address = $page_owner->wallet_address;
        $QRstring = "dash:" . $address . "?amount=" .$tip->dash_amount;

        return view('process_tip', compact(
            'QRstring',
            'dash_usd',
            'dash_toSend',
            'usd_amount',
            'tip_id', 
            'page_owner',
            'tips',
            'biggest_tip',
            'number_of_tips',
            'tips_sent'
        ));
    }

    function confirm_tip(Request $req){
        
        tip::where('id',$req->tip_id)->update([
            'status' => 'confirmed',
            'stamp' => $req->transaction_id,
            'updated_at' => Carbon::now()
        ]);

        $tip = tip::where('id',$req->tip_id)->first();

        $data = array();

        if($tip->sender_id){
            $data['from_id'] = $tip->sender_id;
        }elseif($tip->sent_by){
            $data['guest_name'] = $tip->sent_by;
        }else{
            $data['guest_name'] = 'Incognito';
        }

        $data['tip_id'] = $req->tip_id;
        $data['to_id'] = $tip->recipient_id;
        $data['event_type'] = 'tip';
        $data['p2p_event'] = 'sent you a tip';
        $data['global_event'] = 'sent a tip';
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        DB::table('logs')->insert($data);

        toast("Tip confirmed!",'success');

    }

    function unconfirmed(Request $req){

        Tip::where('id',$req->tip_id)->update(['status' => 'unconfirmed','updated_at' => Carbon::now()]);
        toast("You runned out of time, the tip was not confirmed",'error');
    }
}