<?php

namespace App\Http\Controllers;

use App\User;
use App\Messages;
use App\Reputations;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;


class UserPageController extends Controller
{
    public function show($username){
        
        $visitor = Auth::user();
        $page_owner = User::where('username', $username)->first();

        // if user page doesn't exist return an error of page not found
        if(!$page_owner){
            abort(403, 'Sorry, this user page does not exist.');
        }

        // count page view for the page owner if the user is not on the visits record DB
        $ip = request()->ip();
        $ip_on_record = DB::table('visits_record')->where('page_owner_id',$page_owner->id)->where('ip',$ip)->first();

        if($ip_on_record){
            // do nothing
        }else{

            // add ip to DB
            $data = array();
            $data['page_owner_id'] = $page_owner->id;
            $data['ip'] = $ip;

            DB::table('visits_record')->insert($data);

            // add a visit to the page owner
            $page_owner->page_views += 1;
            $page_owner->save();
        }
        
        // messages sent to the specific page
        $posts = Messages::where('recipient_id', $page_owner->id)->orderBy('message_id','desc')->paginate(5);
        
        // the variable reputation_click will be define in a different manner depending if the visitor is a user or a guest
        if($visitor){
            $reputation_click = Reputations::where('clicker_id', $visitor->id)->where('recipient_id', $page_owner->id)->first();
        }else{
            $reputation_click = false;
        }

        // push parameters to user page
        return view('user_page', compact('page_owner','posts','reputation_click'));
    }

    public function post_message(Request $request, $username){
            
            $page_owner = User::where('username', $username)->first();

            // user needs to login to post a message
            if(!Auth::user()){ 
                toast('You need to login for this.','info');
                return redirect()->back()->withInput();
            }

            // there must be content on the msg to post
            if(!$request->message){
                toast('Why post a message without content?','warning');
                return redirect()->back()->withInput();
            }

            // the page owner cannot post a message on his own page 
            if(Auth::user() == $page_owner){
                toast('Posting on your page is not allowed.','info');
                return redirect()->back()->withInput();
            }

            // PENDING the user can write a max of 1 post per day per page
            $day_post_check = DB::table('messages')->where([
                                    ['author_id', Auth::user()->id],
                                    ['recipient_id', $page_owner->id],
                                    ])->whereDate('created_at', Carbon::today())->value('message');

            if($day_post_check){
                toast("Sorry, you can only write one message per day on a user's page.",'info');
                return redirect()->back()->withInput();
            }                        


            // insert post in db 
            $data = array();
            $data['recipient_id'] = User::where('username', $username)->first()->id;
            $data['author_id'] = Auth::user()->id;
            $data['message'] = $request->message;
            $data['likes_it'] = 0;
            $data['loves_it'] = 0;
            $data['brilliant'] = 0;
            $data['created_at'] = Carbon::now();

            DB::table('messages')->insert($data);

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $page_owner->id;
            $data['log'] = "wrote on your page.";
            $data['rep_change'] = +1;

            DB::table('log')->insert($data);

            // insert into community log table
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $page_owner->id;
            $data['is_msg'] = true;
            $data['msg'] = $request->message;
            $data['msg_id'] = Messages::orderBy('message_id', 'DESC')->first()->message_id;
            $data['is_boost'] = false;

            DB::table('community_log')->insert($data);

            // add reputation
            $page_owner->reputation_score += 1;
            $page_owner->save();

            toast('Message successfully posted.','success');
            return redirect()->back();
    }

    public function boost_reputation(Request $request){
    
        $page_owner = User::where('id', $request->page_owner_id)->first();

        // note: validation PENDING

        // user needs to login to boost reputation
        if(!Auth::user()){
            toast('Sorry, you need to login first.','info');
            abort();
        }

        // page owner cannot boost his own reputation
        if(Auth::user() == $page_owner){
            toast("Sorry, you can't boost your own reputation.",'info');
            abort();
        }

        // get the data of the visitor
        $reputation_click = Reputations::where('clicker_id', Auth::user()->id)->where('recipient_id', $page_owner->id)->first();

        // has the visitor clicked this btn before?
        if($reputation_click){
                
            // delete
            $reputation_click->delete();
        
            // substract reputation from the recipient
            $page_owner->reputation_score -= 5;
            $page_owner->save();

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $page_owner->id;
            $data['log'] = "unboosted your reputation.";
            $data['rep_change'] = -5;

            DB::table('log')->insert($data);
        }else{

            Reputations::create([
                'clicker_id' => Auth::user()->id,
                'recipient_id' => $page_owner->id,
                'status' => true,
            ]);

            $page_owner->reputation_score += 5;
            $page_owner->save();

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $page_owner->id;
            $data['log'] = "boosted your reputation.";
            $data['rep_change'] = 5;
            
            DB::table('log')->insert($data);

            // insert into community log table
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $page_owner->id;
            $data['is_msg'] = false;
            $data['msg'] = null;
            $data['is_boost'] = true;

            DB::table('community_log')->insert($data);
        }
    }

    public function likes_it(Request $request){
        
        $msg = Messages::where('message_id', $request->message_id);
        $sent_by = User::where('id', $msg->first()->author_id)->first();

        // ----- update status of other buttons and the author reputation --------
        if($msg->first()->loves_it == 1){
            
            $msg->update(['loves_it' => 0]);
            $sent_by->reputation_score -= 3;
            
             // log info
             $data = array();
             $data['from_user_id'] = Auth::user()->id;
             $data['to_user_id'] = $sent_by->id;
             $data['log'] = "unloved your post.";
             $data['rep_change'] = -3;

             DB::table('log')->insert($data);
        }

        if($msg->first()->brilliant == 1){

            $msg->update(['brilliant' => 0]);
            $sent_by->reputation_score -= 2;


            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "doesn't think your post is brilliant anymore.";
            $data['rep_change'] = -2;

            DB::table('log')->insert($data);
        }
        // -----------------------------------------------------------------------
        
        // update like btn status and author reputation score (likes are +1)
        if($msg->first()->likes_it == 0){

            $msg->update(['likes_it' => 1]);
            $sent_by->reputation_score += 1;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "likes your post.";
            $data['rep_change'] = +1;

            DB::table('log')->insert($data);

        }else{

            $msg->update(['likes_it' => 0]);
            $sent_by->reputation_score -= 1;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "unliked your post.";
            $data['rep_change'] = -1;

            DB::table('log')->insert($data);
        }
        $sent_by->save();
    }

    public function loves_it(Request $request){
        
        $msg = Messages::where('message_id', $request->message_id);
        $sent_by = User::where('id', $msg->first()->author_id)->first();

        // if the logged visitor hasn't created a username yet, set it up to 'Someone'
        if($username = Auth::user()->username){
        }else{
            $username = "Someone";
        }

         // ----- update status of other buttons and the author reputation --------
        if($msg->first()->likes_it == 1){

            $msg->update(['likes_it' => 0]);
            $sent_by->reputation_score -= 1;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "unliked your post.";
            $data['rep_change'] = -1;

            DB::table('log')->insert($data);

        }

        if($msg->first()->brilliant == 1){

            $msg->update(['brilliant' => 0]);
            $sent_by->reputation_score -= 2;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "doesn't think your post is brilliant anymore.";
            $data['rep_change'] = -2;

            DB::table('log')->insert($data);
        }
        // ------------------------------------------------------------------------

        // update loves it btn status and author reputation score (loves are +3)
        if($msg->first()->loves_it == 0){

            $msg->update(['loves_it' => 1]);
            $sent_by->reputation_score += 3;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "loves your post.";
            $data['rep_change'] = +3;

            DB::table('log')->insert($data);
        }else{

            $msg->update(['loves_it' => 0]);
            $sent_by->reputation_score -= 3;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "unloved your post.";
            $data['rep_change'] = -3;

            DB::table('log')->insert($data);

        }
        $sent_by->save();
    }

    public function brilliant(Request $request){
        
        $msg = Messages::where('message_id', $request->message_id);
        $sent_by = User::where('id', $msg->first()->author_id)->first();

         // ----- update status of other buttons and the author reputation --------
        if($msg->first()->likes_it == 1){

            $msg->update(['likes_it' => 0]);
            $sent_by->reputation_score -= 1;

           // log info
           $data = array();
           $data['from_user_id'] = Auth::user()->id;
           $data['to_user_id'] = $sent_by->id;
           $data['log'] = "unliked your post.";
           $data['rep_change'] = -1;

           DB::table('log')->insert($data);
        }

        if($msg->first()->loves_it == 1){
            $msg->update(['loves_it' => 0]);
            $sent_by->reputation_score -= 3;

            // log info
            $data = array();
            $data['from_user_id'] = Auth::user()->id;
            $data['to_user_id'] = $sent_by->id;
            $data['log'] = "unloved your post.";
            $data['rep_change'] = -3;

            DB::table('log')->insert($data);

        }
        // ------------------------------------------------------------------------

        // update brilliant btn status and author reputation score (brilliant are +2)
        if($msg->first()->brilliant == 0){
            $msg->update(['brilliant' => 1]);
            $sent_by->reputation_score += 2;

           // log info
           $data = array();
           $data['from_user_id'] = Auth::user()->id;
           $data['to_user_id'] = $sent_by->id;
           $data['log'] = "thinks your post is brilliant.";
           $data['rep_change'] = +2;

           DB::table('log')->insert($data);

        }else{
            $msg->update(['brilliant' => 0]);
            $sent_by->reputation_score -= 2;

           // log info
           $data = array();
           $data['from_user_id'] = Auth::user()->id;
           $data['to_user_id'] = $sent_by->id;
           $data['log'] = "doesn't think your post is brilliant anymore.";
           $data['rep_change'] = -2;

           DB::table('log')->insert($data);

        }
        $sent_by->save();
    }

    public function delete_post(Request $request){
        
        // from: user that deletes the post
        $from = Auth::user();

        // to: user that wrote the post
        $to = Messages::where('message_id',$request->msg_id)->first();

        // delete message in db --------
        DB::table('messages')->where('message_id', $request->msg_id)->delete();

        // delete community log
        DB::table('community_log')->where('msg_id', $request->msg_id)->delete();

        // subtract one reputation point from post author
        User::where('id',$to->author_id)->first()->reputation_score -= 1;

        // log info ----------------------
        $data = array();
        $data['from_user_id'] = $from->id;
        $data['to_user_id'] = $to->author_id;
        $data['log'] = 'deleted your post';
        $data['rep_change'] = '-1';

        DB::table('Log')->insert($data);

        // alert --------------------
        toast("Post deleted.",'success');
    }
}
