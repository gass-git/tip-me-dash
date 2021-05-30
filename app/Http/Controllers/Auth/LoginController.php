<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use DB;

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

        /* This variable will be null if the user is not registered */
        $registered_user = User::where('google_id', $google_data->getId())->first();

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

        }

        /* Find the user with this Google id and login */
        $registered_user = User::where('google_id', $google_data->getId())->first();
        auth()->login($registered_user);

        /* Return to Dashboard */
        return redirect()->route('dashboard');
    }
}
