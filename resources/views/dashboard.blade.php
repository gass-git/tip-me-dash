@extends('layouts.app')
@section('content')
<body>
    <section class="dashboard container">
        
        <div class="row">
            <div id="wrapper" class="container mx-auto">
                <div class="row">
                
                    @if (session('status'))
                        <p class="alert alert-success" style="width: 100%;">{{ session('status') }}</p>
                    @endif

                    <!-- welcome to the dashboard section -->
                    <div id="welcome" class="ml-0 pt-3">
           
                        <!-- if it has a username -->
                        @if($username = Auth::user()->username)
                            
                            <a id="username" href="{{ route('user_page',$username) }}">{{ $username }}</a>, welcome to your dashboard
                        
                        <!-- if it doesnt have a username -->
                        @else
                            Welcome to your dashboard 
                        @endif

                    </div>
                    <!-- end of welcome to the dashboard section -->

                </div>
            </div>
        </div> 

        <!-- pending card -->
        @if(!Auth::user()->wallet_address OR !Auth::user()->username)
                
            <div class="row">
                <div id="wrapper" class="container mx-auto">
                    <div class="row">

                            <div class="alert alert-light border" role="alert" style="width:100%;">
                                <!-- username pending -->
                                @if(!Auth::user()->username)
                                    <p>
                                    <i class="far fa-bell mr-2" style="font-size:20px;"></i>It seems you haven't created a username. In order
                                        to activate a TMD page you need to create one,  
                                        <a href="{{ route('edit_profile') }}"> you can do so by clicking here</a>.
                                    </p>
                                @endif
                                <!---------------------->
                                
                                <!-- wallet address pending -->
                                @if(!Auth::user()->wallet_address)
                                <i class="far fa-bell mr-2" style="font-size:20px;"></i>Set up your Dash wallet 
                                        <a href="{{ route('edit_profile') }}">here</a> to start receiving tips!
                                @endif
                                <!----------------------------->
                            </div>

                    </div>
                </div>
            </div>
        @endif
        <!------ pending card end ------->

        <!-- summary -->   
        <div class="row">
            <div id="wrapper" class="container border pl-3 pr-3 mx-auto">
                <div class="row">

                    <!-- reputation -->
                    <div id="rep-col" class="col-md-3 p-3">
                        <div id="rep-card" class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Reputation</h5>
                                <p class="card-text">{{ Auth::user()->reputation_score }}</p>
                            </div>
                        </div>
                    </div>
                    <!---------------->

                    <!-- page link -->
                    <div class="col-md-9 p-3">
                        <div id="page-link-card" class="card text-white bg-primary">
                            <div class="card-body pr-1">
                                <h5 class="card-title">Share your page link</h5>
                                
                                <!-- does the user have a username? -->
                                @if($username = Auth::user()->username)
                                    <form class="form-inline mr-0">
                                        <input id="share-link" type="text" class="form-control w-75" value="http://tipmedash.com/{{ $username }}" id="user_page">
                                        <button class="ml-2 btn btn-info" onclick="copy_url()" id="btn">Copy</button>
                                    </form>
                                @else
                                    <p>
                                    <i class="fas fa-exclamation mr-2"></i>Not available. You need to <a href="{{ route('edit_profile') }}" style="color:#FEDD00;">create a username</a> to have your own page link.
                                    </p>
                                @endif

                                <!-- script for copy button -->
                                <script>
                                    function copy_url() {
                                    var url = document.getElementById("user_page");
                                    url.select();
                                    url.setSelectionRange(0, 200)
                                    document.execCommand("copy");

                                    var btn = document.getElementById("btn");
                                    btn.innerHTML = "Copied";
                                    setTimeout(() => {document.getElementById("btn").innerHTML = "Copy"}, 2000);
                                    }
                                </script>     
                                <!--------------------------->
                            </div>
                        </div>
                    </div>
                    <!-- end of page link --->
                </div>
            </div>
        </div>
        <!------ end of summary ------->                               

        <!-- new commers -->
        <div class="row">
            <div id="wrapper" class="container mt-3 pl-3 pb-0 pr-3 mx-auto">
                <div class="row">
                    <div id="newcomers" class="alert border m-0" role="alert">
                    <i class="fas fa-child mr-2"></i><b> Discover</b> newcomers
                        <a href="newcomers"> here</a>
                    </div>
                </div>
            </div>
        </div>
        <!----------------->

        <div class="title-1">RECENT ACTIVITY</div>

        <!-- recent -->
        <div class="row">
            <div id="wrapper" class="mt-0 mx-auto">
                <ul class="list-group" style="width:100%;">

                    @if(count($recent_logs) < 1)
                        <div id="no-activity" class="alert border" role="alert">
                           You don't have any recent activity..
                        </div>              
                    @else
                        @foreach($recent_logs->take(5) as $event)
                        <li class="list-group-item">
                            @if($event->rep_change > 0)
                                <span id="rep-badge" class="badge badge-success mr-1">+{{ $event->rep_change }}</span>
                            @else
                                <span id="rep-badge" class="badge badge-danger mr-1">{{ $event->rep_change }}</span>
                            @endif

                            <!-- show username with a link to their page -->
                            @if($username = App\User::where('id',$event->from_user_id)->first()->username)                  
                                <a class="visitor-username" href="{{ route('user_page',$username) }}">{{ $username }}</a>
                            @else
                                <span style="font-weight:bold;">Someone</span><!-- note: show "someone" if the user doesn't have a username -->  
                            @endif
                            <!--------------------------------------------->

                            @if($event->log === 'wrote on your page.')
                                <a href="{{ route('user_page',Auth::user()->username) }}">{{ $event->log }}</a>
                            @else
                                {{ $event->log }}
                            @endif
                        </li>
                        @endforeach
                    @endif

                </ul>
            </div>
        </div>
        <!-------------->

        <div class="title-1">USERS I LIKE</div>

        <!-- users liked -->
        <div class="row">
            <div class="content-wrapper mt-0 mx-auto" style="width:80%;">
                        
                @if(count($users_liked) < 1)
                    <div id="no-likes" class="alert border" role="alert">
                        You still don't like any user..
                    </div>
                @else

                    <div class="list-group" style="width:100%;">

                    @foreach($users_liked as $user_liked)
                        @php
                            $username = App\User::where('id',$user_liked->recipient_id)->first()->username;
                            $avatar_url = App\User::where('id',$user_liked->recipient_id)->first()->avatar_url;
                        @endphp

                        <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}" >
                            <img id="avatar" src="{{ $avatar_url }}"></img>
                            <span class="ml-2" style="text-transform: capitalize;">{{ $username }}</span>
                        </a>
                    @endforeach 
                
                    </div>
                    <div class="mt-3">{{ $users_liked->links() }}</div>
                @endif

            </div>   
        </div>
        <!-------------------->

    </section>
</body>
@endsection