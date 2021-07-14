<nav class="navbar p-0" style="background-color:white">
        
    <!----- Brand ----->
    <a class="navbar-brand ml-5 mr-0 p-0" href="{{ url('/') }}">
        <div class="container pr-0">
                <img class="mr-3" src="{{ asset('images/logo2.png') }}" height="30">
                <div class="brand-text-two">Tip Me Dash</div>
        </div>
    </a>
    <!----------------->

    @guest
    <!-- authentication Links -->
    <form id="auth-links" class="form-inline mr-5 p-0">
        <a class="btn btn-outline-dark ml-0 my-2 my-sm-0 mr-2" href="{{ route('login') }}">Login</a>
        <a class="btn btn-outline-primary" href="{{ route('register') }}">Sign up</a>
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
            @if(Auth::user()->username AND Auth::user()->email_verified_at)
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