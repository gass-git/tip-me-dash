<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\User;

use DB;

class SettingsController extends Controller
{
    
    public function show(){
        $user = Auth::user();
        return view('edit_profile',compact('user'));
    }

    public function save_one(Request $req){

        $user = Auth::user();

        $req->validate([
            'username' => ['required','max:15',Rule::unique('users')->ignore($user->id, 'id'), 'regex:/^[a-zA-Z0-9_-]+$/'],
            'wallet_address' => ['nullable','regex:/^[1-9A-HJ-NP-Za-km-z]+$/'],
            'location' => ['nullable','max:30','regex:/^[a-zA-Z0-9., ]+$/'],
            'avatar' => ['nullable','image','mimes:jpg,png,jpeg,gif','max:500','dimensions:min_width=100,min_height=100'],
        ]);

        /**@abstract
         * 
         * UPLOAD AVATAR
         * - If avatar_name variable is null it means the user is uploading an img for the first time.
         * - If the user has uploaded an avatar before, delete it before uploading the new one.
         * 
         */

        $img = $req->file('avatar');

        if($img){
            if($user->avatar_name){
                Storage::delete('/public/profile-pics/'.$user->avatar_name);
            }

            $img_new_name = date('dmy_H_s_i').'_'.$user->id.'_'.$img->getClientOriginalName();
            $img->storeAs('profile-pics',$img_new_name,'public');
            $user->avatar_url = 'http://tipmedash.com/storage/profile-pics/'.$img_new_name;
            $user->avatar_name = $img_new_name;
        }

        $user->username = $req->username;
        $user->location = $req->location;
        $user->wallet_address = $req->wallet_address;
        
        $user->save();

        // Message to activate links 'back to dashboard' & 'view my page'
        $req->session()->flash('message', 'null');    

        toast('Changes saved!','success');
        return redirect()->back();
    }

    const FIELDS = ['about','website','passionate_about','twitter','youtube','github'];

    public function save_two(Request $req){
        
        $user = Auth::user();

        $req->validate([
            'about' => ['nullable','max:300'],
            'passionate_about' => ['nullable','max:30','regex:/^[a-zA-Z0-9,& ]+$/'],
            'website' => ['nullable','max:40','regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
            'twitter' => ['nullable','max:15', 'regex:/^[a-zA-Z0-9_]+$/'],
            'youtube' => ['nullable','max:50', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'github' => ['nullable','max:25', 'regex:/^[a-zA-Z0-9_-]+$/'],
        ]);

        foreach (self::FIELDS as $field) {
                $user->$field = $req->$field;
                $user->save();
        }

        // Message to activate links 'back to dashboard' & 'view my page'
        $req->session()->flash('message', 'null');    

        toast('Changes saved','success');
        return redirect()->route('edit_profile');
    }

    public function reset_password(Request $request){

        $user = Auth::user();

        // Validate new password
        $request->validate([
            'new_password' => ['required','string','min:5'],
            'new_confirm_password' => ['required','same:new_password'],
        ]);
   
        // Check if the current password entered equals users password or user doesn't have password
        if(password_verify($request->password, $user->password) OR $user->password == null){

            if($user->password == null){
                toast('Password created','success');
            }else{
                toast('Password changed','success');
            }

            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            return redirect()->route('dashboard');
        }

        // Fail: current password does not match
        toast('Current password is invalid','error');
        return redirect()->back();
    }

    public function change_email(Request $request){

        // Validate email
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:40', 'unique:users'],
            'confirm_new_email' => ['required','same:email'],
        ]);

        // User needs to enter password to change email
        if(password_verify($request->password, Auth::user()->password)){
            
            $user = Auth::user();

            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->google_id = null;

            $user->save();

            /* Send verification email */
            $user->sendEmailVerificationNotification();

            toast('Email changed','success');
            return view('auth/verify');
        }

        toast('Wrong password','error');
        return redirect()->back();

    }

    public function delete_acc(Request $request){
        
        if (password_verify($request->password, Auth::user()->password)) {
            
            // Success

            /* find user to delete */
            $user = user::findOrFail(Auth::user()->id);

            /* logout the user */
            Auth::logout();

            /* delete account */
            $user->delete();

            /* redirect the user to the welcome page and inform of success */
            toast('Your account has been deleted','success');
            return redirect('/');
        }

        toast('Wrong password','error');
            return redirect()->back();
    }

}
