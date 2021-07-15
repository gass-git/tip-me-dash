<?php

namespace App\Http\Controllers;

use App\Notifications\TipReceived;
use App\User;
use App\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use DB;
use Carbon\Carbon;

class TipController extends Controller
{

    function process_tip(Request $req){
        
        $req->validate([
            'msg' => ['nullable','max:300'],
            'name' => ['nullable','max:25'],
        ]);

        /* ---- Global variables ----------------------------------------- */
        $page_owner = User::where('username', $req->username)->first();
        $IP = request()->ip();
        /* ---------------------------------------------------------------- */

        /**@abstract
         * 
         * EXTRA VALIDATIONS
         *  - It's not allowed to make more than two tips a day to the same user.
         *  - It's not allowed to push the tip button more than four times per day - spam protection.
         *  - The user needs to enter a wallet address to receive tips.
         *  - The page owner cannot tip himself
         * 
         */
        $btn_clicks_24h = Tip::where('sender_ip', $IP)
                            ->whereDate('created_at', Carbon::today())
                            ->count();

        if($btn_clicks_24h > 5){
            toast('You cannot send more tips for today','info');
            return back();
        }            

        $tips_to_user_24h = Tip::where('sender_ip', $IP)
                        ->where('recipient_id', $page_owner->id)
                        ->where('status','confirmed')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        if(Auth::user() == $page_owner){
            toast('Why would you tip yourself?','info');
            return back();
        }

        if($tips_to_user_24h >= 2){
            toast("It's not allowed to make more than two tips a day to the same user.",'info');
            return back();
        }

        if($page_owner->wallet_address == null){
            toast('Not possible, the user has not entered a wallet address yet.','info');
            return back();
        }

        /* --- API --- Get dash usd exchange rate ----------------- */
        $api = 'https://www.dashcentral.org/api/v1/public'; 
        $response = Http::get($api);
        $obj = json_decode($response);
        /* -------------------------------------------------------- */   

        $rand_decimal = rand (1, 10) / 100;                                     // Create a random decimal
        $dash_usd = round($obj->exchange_rates->dash_usd, 8) + $rand_decimal;   // Dash usd price plus the random decimal
        $usd_amount = $req->amount_entered;                                     // USD amount entered by supporter
        $dash_toSend = round( $usd_amount/$dash_usd, 8);                        // Dash conversion to send

        /**@abstract
         * 
         * Insert data on tips table
         * 
         */
        $data = array();

        if(Auth::user()){
            $data['sender_id'] = Auth::user()->id;
        }elseif($req->name){
            $data['sent_by'] = $req->name;
        }

        if($req->lock){
            $data['private_msg'] = 'yes';
        }

        $data['message'] = $req->msg;
        $data['sender_ip'] = $IP;
        $data['recipient_id'] = $page_owner->id;
        $data['usd_equivalent'] = $usd_amount;
        $data['dash_amount'] = $dash_toSend;
        $data['dash_usd'] = $dash_usd;
        $data['status'] = 'not validated';
        $data['created_at'] = Carbon::now();

        DB::table('tips')->insert($data);

        /* ------- Extra variables to compact on view ----------------------- */
        $tip_id = Tip::max('id');
        $tip = Tip::where('id', $tip_id)->first();
        $address = $page_owner->wallet_address;
        $QRstring = "dash:" . $address . "?amount=" .$tip->dash_amount;
        /* ------------------------------------------------------------------ */

        return view('process_tip', compact('QRstring','dash_usd','dash_toSend','usd_amount','tip_id','page_owner'));
    }

    function confirm_tip(Request $req){
        
        $tip = Tip::where('id',$req->tip_id)->first();
        $tip_recipient = User::where('id',$tip->recipient_id)->first();

        /* ----- Update tip ------------------------- */
        $tip->update([
            'status' => 'confirmed',
            'stamp' => $req->transaction_id,
            'updated_at' => Carbon::now()
        ]);
        /* ------------------------------------------ */

        /**@abstract
         * 
         * - Create a log of the confirmed tip.
         * - Send email notification.
         *
         */
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
        $data['type'] = 'tip';
        $data['p2p_event'] = 'sent you a tip';
        $data['global_event'] = 'sent a tip';
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        DB::table('logs')->insert($data);

        Notification::route('mail',$tip_recipient->email)
                        ->notify(new TipReceived($tip));

        /**@abstract
         * 
         * If this IP has not tipped the page owner before then add points to: 
         * - To the tipper if he is registered (+40)
         * - To the recipient of the tip (+15)
         * 
         */
        $amount = Tip::where('sender_ip',$tip->sender_ip)
                    ->where('status','confirmed')
                    ->where('recipient_id', $tip->recipient_id)
                    ->count();

        if($amount == 1){

            // Add points to the sender of the tip if he is registered
            if($tip->sender_id){
                $user = User::where('id', $tip->sender_id)->first();
                $user->points = $user->points + 40;
                $user->save();
            }

            // Add points to the recipient of the tip
            $user = User::where('id', $tip->recipient_id)->first();
            $user->points = $user->points + 15;
            $user->save();
        }
        toast("Tip confirmed!",'success');
    }

    function unconfirmed(Request $req){
        Tip::where('id',$req->tip_id)->update(['status' => 'unconfirmed','updated_at' => Carbon::now()]);
        toast("You runned out of time",'error');
    }
}