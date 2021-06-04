<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\User;


class EditProfileController extends Controller
{
    

    public function show(){
        return view('edit_profile');
    }
    
    public function reset_password(Request $request){

        // Validate new password
        $request->validate([
            'new_password' => ['required','string','min:5'],
            'new_confirm_password' => ['required','same:new_password'],
        ]);
   
        // Check if the current password entered equals users password
        if(password_verify($request->password,Auth::user()->password)){

            //success
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            toast('password changed','success');
            return redirect()->route('dashboard');
        }

        // Fail: current password does not match
        toast('wrong current password','error');
        return redirect()->back();
    }

    const FIELDS = ['username', 'wallet_address', 'about', 'password', 
    'website', 'location','favorite_crypto','desired_superpower','passionate_about'];

    public function update(Request $request){

        // validate every field independently

        if($request->username){
            $request->validate([
                'username' => ['max:15','unique:users', 'regex:/^[a-zA-Z0-9_-]+$/'],
            ]);
        }

        if($request->email){
            
            $request->validate([
                'email' => ['string', 'email', 'max:50', 'unique:users'],
            ]);

        }

        if($request->wallet_address){
            $request->validate([
                'wallet_address' => ['max:40','regex:/^[a-zA-Z0-9]+$/'],
            ]);
        }
        
        if($request->passionate_about){
            $request->validate([
                'passionate_about' => ['max:30','regex:/^[a-zA-Z0-9, ]+$/'],
            ]);
        }

        if($request->location){
            $request->validate([
                'location' => ['max:30','regex:/^[a-zA-Z0-9., ]+$/'],
            ]);
        }

        if($request->website){
            $request->validate([
                'website' => ['max:30','regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
            ]);
        }

        if($request->favorite_crypto){
            $request->validate([
                'favorite_crypto' => ['max:30','regex:/^[a-zA-Z0-9 ]+$/'],
            ]);
        }

        if($request->desired_superpower){
            $request->validate([
                'desired_superpower' => ['max:30','regex:/^[a-zA-Z0-9 ]+$/'],
            ]);
        }

        if($request->avatar){
            $request->validate([
                'avatar' => ['image','mimes:jpg,png,jpeg,gif','max:200','dimensions:min_width=100,min_height=100,max_width500,max_height=500'],
            ]);
        }

        $user = Auth::user();

        $username = $request->username;
        // PENDING $email = $request->email; 

        if($username){ 
            $user->username = $username; 
            $user->save();
        }

        /** upload avatar section */
        
        $image = $request->file('avatar');

        if($image){

            // note: if avatar_name variable is null it means the user has never uploaded an image..
            // if the user has uploaded an avatar before, delete it before uploading the new one..
            if($user->avatar_name){
                Storage::delete('/public/profile-pics/'.$user->avatar_name);
            }

            $image_new_name = date('dmy_H_s_i').'_'.$user->id.'_'.$image->getClientOriginalName();
            $image->storeAs('profile-pics',$image_new_name,'public');
            $user->avatar_url = 'storage/profile-pics/'.$image_new_name;
            $user->avatar_name = $image_new_name;
            $user->save();
        }

        foreach (self::FIELDS as $field) {
            if ($request->$field) {
                $user->$field = $request->$field;
                $user->save();
            }
        }
        
        toast('changes saved','success');
        return redirect()->route('edit_profile');
    }

    public function change_email(Request $request){

        // Validate email
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:40', 'unique:users'],
            'confirm_new_email' => ['required','same:email'],
        ]);

        // Check if the current password entered equals users password
        if(password_verify($request->password, Auth::user()->password)){
            
            $user = Auth::user();

            $user->email = $request->email;
            $user->email_verified_at = null;

            $user->save();

            /* Send verification email */
            $user->sendEmailVerificationNotification();

            toast('Email changed','success');
            return Auth::logout();
        }

        toast('wrong password','error');
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

        toast('wrong password','error');
            return redirect()->back();
    }

}
