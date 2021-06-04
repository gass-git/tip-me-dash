<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Auth;

use App\User;
use App\SocialProfile;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the external authentication website.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectToProvider()
    {   
       return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from external website.
     *
     * @return \Illuminate\Http\Response
     */

    public function handleProviderCallback(Request $request)
    {

        /* If the user denise permission on google to provide login information, redirect to login page. */
        if($request->get('error')){
            return redirect()->route('login');
        }

        /* Request data from Google */
        $google_data = Socialite::driver('google')->user();

        /* Search for user with Google id or email */
        $registered_user = User::where('google_id', $google_data->getId())->orwhere('email',$google_data->email)->first();

        /* Register user with google data if it's not registered */
        if(!$registered_user){

            $data = array();
            $data['google_id'] = $google_data->getId();
            $data['email'] = $google_data->getEmail();
            $data['avatar_url'] = 'http://localhost/tipmedash/public/images/default-profile-pic1.jpg';
            $data['reputation_score'] = 10;
            $data['email_verified_at'] = Carbon::now();
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            DB::table('users')->insert($data);

            /* Find user after registering */
            $registered_user = User::where('google_id', $google_data->getId())->first();
        }

        /** Conditions in case the user changes email and signs in with Google */

            // If the user is registered with his google email but without the google_id assigned
            if($registered_user->google_id == null){
                $registered_user->google_id = $google_data->getId();
            }

            // if the user is registered with google email or id, but his email is not verified
            if($registered_user->email_verified_at == null){
                $registered_user->email_verified_at = Carbon::now();
            }

        /** ------------------------------------------------------------------- */


        auth()->login($registered_user);

        /* Redirect to Dashboard */
        return redirect()->route('dashboard');
    }
}
