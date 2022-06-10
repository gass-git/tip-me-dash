<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
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

    public function showLoginForm()
    {

        if (!session()->has('url.intended')) {

            $prev = url()->previous();
            $url_one = 'https://tipmedash.com/';
            $url_two = 'https://tipmedash.test/';

            if ($prev == $url_one or $prev == $url_two) {
                session(['url.intended' => 'dashboard']);
            } else {
                session(['url.intended' => url()->previous()]);
            }
        }
        return view('auth.login');
    }

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

        // If the user denise permission on google to provide login information, redirect to login page.
        if ($request->get('error')) {
            return redirect()->route('login');
        }

        $google_data = Socialite::driver('google')->user();  // Request data from Google

        $registered_user = User::where('google_id', $google_data->getId())  // Search for user with Google id or email.
            ->orwhere('email', $google_data->email)
            ->first();

        /* ---- Create user with requested data if it's not registered ---- */
        if (!$registered_user) {

            User::create([
                'ip' => request()->ip(),
                'google_id' => $google_data->getId(),
                'email' => $google_data->getEmail(),
                'avatar_url' => "https://tipmedash.com/images/avatar-default-1.jpg",
                'points' => 10,
                'username_color' => "rgb(255,255,255)",
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]);

            $registered_user = User::where('google_id', $google_data->getId())->first();  // Find user after registering.
        }
        /* ---------------------------------------------------------------- */

        /**@abtract
         * 
         * Conditions in case the user changes email and logs in with Google:
         * 1) The user is registered with his google email but without the google_id assigned.
         * 2) The user is registered with google email or id, but his email is not verified.
         * 
         */
        if ($registered_user->google_id == null) {
            $registered_user->google_id = $google_data->getId();
            $registered_user->save();
        }

        if ($registered_user->email_verified_at == null) {
            $registered_user->email_verified_at = Carbon::now();
            $registered_user->save();
        }

        auth()->login($registered_user);
        return redirect()->route('dashboard');
    }
}
