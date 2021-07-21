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
            'name' => ['nullable','max:20'],
        ]);

        /* ---- Global variables ----------------------------------------- */
        $page_owner = User::where('username', $req->username)->first();
        $IP = request()->ip();
        /* ---------------------------------------------------------------- */

        /** @abstract
         * 
         * EXTRA VALIDATIONS
         *  1) It's not allowed to push the tip button more than 5 times per day - spam protection by IP.
         *  2) It's not allowed to make more than two tips a day to the same user, this is validated 
         *     by ID & IP if the user is logged in and by IP if it's a guest (protect the user from been spammed).   
         *  3) The user needs to enter a wallet address to receive tips.
         *  4) The page owner cannot tip himself.
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
            $by_ID = Tip::where('sender_id', auth::user()->id)
                        ->where('recipient_id', $page_owner->id)
                        ->where('status','confirmed')
                        ->whereDate('created_at', Carbon::today())
                        ->count();   
                          
            $tips_to_user_24h = $by_ID;

            $by_IP = Tip::where('sender_ip', $IP)
                        ->where('recipient_id', $page_owner->id)
                        ->where('status','confirmed')
                        ->whereDate('created_at', Carbon::today())
                        ->count();                    

            if($by_ID < $by_IP){
                $tips_to_user_24h = $by_IP;
            }

            if($tips_to_user_24h >= 2){
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
         * to fill up the sender info on the tip boxes.
         * 
         */
        $data = array();

        if($req->lock){
            $data['private_msg'] = 'yes';
        }

        if(Auth::user()){
            $data['sender_id'] = Auth::user()->id;
            $data['sender_email'] = Auth::user()->email;
        }

        $data['sent_by'] = $req->name;
        $data['sender_ip'] = $IP;
        $data['message'] = $req->msg;
        $data['recipient_id'] = $page_owner->id;
        $data['recipient_email'] = $page_owner->email;
        $data['recipient_ip'] = $page_owner->ip;
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

        /* --- Create a log of the confirmed tip --- */
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
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        DB::table('logs')->insert($data);
        /* ------------------------------------------ */

        /** @abstract
         * 
         * Point rewards based on links between registered users.
         * 
         * A USER IS REWARDED WHEN:
         * - Receives a tip from a new supporter (+10)
         * - Tips a recipient he has never tipped before (+$P)
         * 
         * POINT REWARDS VALIDATION MECHANICS:
         * The amount of tips from the sender ID to the recipient ID has to be one. There is a case where
         * a user deletes and creates an account multiple times with different ID's. It could be the 
         * tipper or the recipient. Both can have an incentive to cheat and make points by sending and 
         * receiving the same amount of Dash multiple times. To prevent this from happening if the count 
         * of tips sent using the sender and recipient ID is smaller than the count by sender and recipient
         * email then the number of tips to use for validation will be done with the email field.
         * 
         * NOTE ONE: $P is a dynamic variable to avoid users tip low amounts just to get points. It's a way
         * of preventing a bad incentive.
         * 
         * NOTE TWO: it's more common for people to wait for support than to give.
         * Taking this into consideration, incentivizing to support more than to receive support
         * makes sense.
         * 
         */
        $amount = round($tip->usd_equivalent, 2);
        
        switch ($amount){
            case ($amount < 0.5): $p = 0; 
            break;
            case (1 > $amount and $amount >= 0.5): $p = rand(1, 2); 
            break;
            case (3 > $amount and $amount >= 1): $p = rand(4, 6); 
            break;
            case (6 > $amount and $amount >= 3): $p = rand(18, 25); 
            break;
            case ($amount >= 6): $p = rand(50, 60); 
            break;
        }

        $regd_tipper = User::where('id',$tip->sender_id)->first();     

        if($regd_tipper){
            
            $tips_by_ID = Tip::where('sender_id',$tip->sender_id)
                                ->where('status','confirmed')
                                ->where('recipient_id', $tip->recipient_id)
                                ->count();   

            $number_of_tips = $tips_by_ID;

            $tips_by_email = Tip::where('sender_email',$tip->sender_email)
                                ->where('status','confirmed')
                                ->where('recipient_email', $tip->recipient_email)
                                ->count();

            if($tips_by_ID < $tips_by_email){
                $number_of_tips = $tips_by_email;
            }

            $tips_by_ip = Tip::where('sender_ip',$tip->sender_ip)
                                ->where('status','confirmed')
                                ->where('recipient_ip', $tip->recipient_ip)
                                ->count();

            if($number_of_tips < $tips_by_ip){
                $number_of_tips = $tips_by_ip;
            }

            if($number_of_tips == 1){

                if($regd_tipper){  
                    $regd_tipper->points += $p;
                    $regd_tipper->save();

                    $recipient->points += 10;
                    $recipient->save();
                } 
            }        
        }


        /** @abstract
         * 
         * IMPORTANT: Disable notifications when testing on localhost, if not, the controller
         * will crash.
         *
         * - If the recipient changed email and it has not verified it, then skip this step.
         * - If the tipper is registered and has a location send a notification
         * with this data.
         * 
         */
        if($recipient->email_verified_at){
            
            if($regd_tipper){
                if($regd_tipper->location){
                    $tipper_location = $regd_tipper->location;
                }else{
                    $tipper_location = null; // If the registered tipper has no location
                }
            }else{
                $tipper_location = null;  // If the tipper is not logged in
            }

           // Notification::route('mail',$recipient->email)->notify(new TipReceived($recipient, $tipper_location));
        }

        toast("Tip confirmed!",'success');
    }

    function unconfirmed(Request $req){
        Tip::where('id',$req->tip_id)->update(['status' => 'unconfirmed','updated_at' => Carbon::now()]);
        toast("You runned out of time",'error');
    }
}