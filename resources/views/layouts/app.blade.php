<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Receive tips instantly from followers directly to your pocket, without third parties involved. Give fans a new way to show appreciation for your work.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pin-32.png') }}">

    <!-- cookies -->
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <!-- app js -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- popper js -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- emoji js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
    <!-- sweet alert 2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- emoji css  -->    
    <link rel="stylesheet" href="{{asset('css/emojionearea.min.css')}}">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('css/main.css?64') }}">
    <!-- font awesome css -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- nav bar -->
    <nav class="navbar p-0">
        
        <!----- Brand ----->
        <a class="navbar-brand ml-5 mr-0 p-0" href="{{ url('/') }}">
            <div class="container pr-0">
                    <img class="mr-3" src="{{ asset('images/logo9.png') }}" height="30">
                    <div class="brand-text">Tip Me Dash</div>
            </div>
        </a>
        <!----------------->

        @guest
        <!-- authentication Links -->
        <form id="auth-links" class="form-inline mr-5 p-0">
            <a class="btn btn-outline-light ml-0 my-2 my-sm-0 mr-2" href="{{ route('login') }}">Login</a>
            <a class="btn btn-primary" href="{{ route('register') }}">Sign up</a>
        </form> 
        <!-------------------------->
        
        @else
        <!-- navbar dropdown right section -->
        <ul id="logged-in" class="form-inline p-0 my-2 my-lg-0 mr-5">
            
            <!-- navbar avatar dropdown button -->
            <a id="navbarDropdown" class="nav-link dropdown-toggle p-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                
                <!-- avatar -->
                @auth 
                <img class="user-avatar mr-2" src="{{Auth::user()->avatar_url }}">
                @endauth
                <!------------>

            </a>
            
            <!-- dropdown navigation -->
            <div class="dropdown-menu dropdown-menu-right mr-5" aria-labelledby="navbarDropdown">
                
                <!----- edit profile link --->
                <a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>

                <!-- if the user has a username show his TMD page link -->
                @if(Auth::user()->username)
                <a class="dropdown-item" href="{{ auth()->user()->username }}">{{ __('View my page') }}</a>
                @endif
                <!------------------------------------------------------->

                <!----- edit profile link --->
                <a class="dropdown-item" href="{{ route('edit_profile') }}">{{ __('Edit profile') }}</a>

                <!------- logout link ------->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

                <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <!---------------------------->

            </div>

        </ul> 
         <!---- END of navbar dropdown right section ---->

        @endguest
    </nav>
</head>

    @yield('content')
    @include('sweetalert::alert')

</html>
