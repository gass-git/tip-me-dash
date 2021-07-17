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

        /** @abstract
         * 
         * EXTRA VALIDATIONS
         *  - It's not allowed to push the tip button more than 5 times per day - spam protection by IP.
         *  - It's not allowed to make more than two tips a day to the same user, 
         *    this is validated by ID if the user is registered and by IP if it's a guest.
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

        if(Auth::user()){       
            $tips_to_user_24h_ID = Tip::where('sender_id', auth::user()->id)
                                ->where('recipient_id', $page_owner->id)
                                ->where('status','confirmed')
                                ->whereDate('created_at', Carbon::today())
                                ->count();   
                                
            if($tips_to_user_24h_ID >= 2){
                toast("It's not allowed to make more than two tips a day to the same user.",'info');
                return back();
            }

        }else{

            $tips_to_user_24h_IP = Tip::where('sender_ip', $IP)
                                ->where('recipient_id', $page_owner->id)
                                ->where('status','confirmed')
                                ->whereDate('created_at', Carbon::today())
                                ->count();

            if($tips_to_user_24h_IP >= 2){
                toast("It's not allowed to make more than two tips a day to the same user.",'info');
                return back();
            }
        }
       
        if(Auth::user() == $page_owner){
            toast('Why would you tip yourself?','info');
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

        /** @abstract
         * 
         * INSERT DATA ON TIPS TABLE
         * 
         * Note: save sent_by name for registered tippers in case
         * in the future they delete their acc and the tip needs this name
         * to fill up the sender info.
         * 
         */
        $data = array();

        if($req->lock){
            $data['private_msg'] = 'yes';
        }

        if(Auth::user()){
            $data['sender_id'] = Auth::user()->id;
        }

        $data['sent_by'] = $req->name;
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
        
        /* ---- Global variables ----- */
        $tip = Tip::where('id',$req->tip_id)->first();
        $recipient = User::where('id',$tip->recipient_id)->first();
        $data = array();
        /* --------------------------- */

        /* ----- Update tip ------------------------- */
        $tip->update([
            'status' => 'confirmed',
            'stamp' => $req->transaction_id,
            'updated_at' => Carbon::now()
        ]);
        /* ------------------------------------------ */

        /** @abstract
         * 
         * - Create a log of the confirmed tip.
         * - Send email notification.
         *
         */
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


        /** @abstract
         * 
         * Point rewards for registered tippers
         * 
         * x: points for a regd. tipper if he is supporting a recipient for the first time.
         * y: points for a regd. tipper if he is supporting a recipient he has supported before.
         * 
         */
        $amount = $tip->usd_equivalent;
        
        switch ($amount){
            case ($amount < 0.05): $x = 0;
            break;
            case ($amount >= 0.05 and $amount < 0.50): $x = 1;
            break;
            case ($amount >= 0.50 and $amount < 1): $x = 6;
            break;
            case ($amount >= 1 and $amount < 4): $x = 14;
            break;
            case($amount >= 4): $x = 60;
            break;
        }
   
        switch ($amount){
            case ($amount < 1): $y = 0;
            break;
            case ($amount >= 1 and $amount < 4): $y = 7;
            break;
            case ($amount >= 4 and $amount < 10): $y = 37;
            break;
            case($amount >= 10): $y = 60;
            break;
        }

        /** @abstract
         * 
         * If the tipper with this IP has not tipped the page owner before then add points to: 
         * - The tipper if he is registered (+x)
         * - The recipient of the tip (+15)
         * 
         * ELSE:
         * - The tipper if he is registered (+y)
         * - The recipient of the tip (+5)
         * 
         */

        $regd_tipper = User::where('id',$tip->sender_id)->first();     

        /* ---- Check the number of tips by IP only if the tipper is not logged in ---- */
        if($regd_tipper){
            $number_of_tips = Tip::where('sender_id',$tip->sender_id)
                    ->where('status','confirmed')
                    ->where('recipient_id', $tip->recipient_id)
                    ->count();
        }else{
            $number_of_tips = Tip::where('sender_ip',$tip->sender_ip)
                    ->where('status','confirmed')
                    ->where('recipient_id', $tip->recipient_id)
                    ->count();
        }
        /* ----------------------------------------------------------------------------- */
        
        if($number_of_tips == 1){
            
            if($regd_tipper){  
                $regd_tipper->points += $x;
                $regd_tipper->save();
            } 
            $recipient->points += 15;  
            $recipient->save();
        }else{

            if($regd_tipper){  
                $regd_tipper->points += $y; 
                $regd_tipper->save();
            }
            $recipient->points += 5;    
            $recipient->save();
        }

        /** @abstract
         * 
         * - Add one sent to the tipper if he is registered.
         * - Add one received to the recipient.
         * 
         */
        if($regd_tipper){ 
            $regd_tipper->sent += 1;
            $regd_tipper->save();
        }
        $recipient->received += 1;
        $recipient->save();

        /** @abstract
         * 
         * IMPORTANT!
         * Disable notifications when testing on localhost, if not, the controller
         * will crash.
         * 
         * If the tipper is registered and has a location send a notification
         * with this data.
         * 
         */
        if($regd_tipper){
            if($regd_tipper->location){
                $tipper_location = $regd_tipper->location;
            }
        }else{
            $tipper_location = null;
        }

        Notification::route('mail',$recipient->email)->notify(new TipReceived($recipient, $tipper_location));

        toast("Tip confirmed!",'success');
    }

    function unconfirmed(Request $req){
        Tip::where('id',$req->tip_id)->update(['status' => 'unconfirmed','updated_at' => Carbon::now()]);
        toast("You runned out of time",'error');
    }
}