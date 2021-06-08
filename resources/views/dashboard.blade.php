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

                                <!-- username pending -->
                                @if(!Auth::user()->username)
                                <div class="alert alert-primary" role="alert" style="width:100%;font-size:15px;">
                                  
                                    <i class="far fa-bell mr-2" style="font-size:17px;"></i>It seems you don't have a username. In order
                                        to activate your own TMD page you need to <a href="{{ route('edit_profile') }}">create one</a>.  
                                </div>
                                @endif
                                <!---------------------->
                                
                                <!-- wallet address pending -->
                                @if(!Auth::user()->wallet_address)
                                <div class="alert alert-primary" role="alert" style="width:100%;font-size:15px;">
                                    <i class="far fa-bell mr-2" style="font-size:17px;"></i>Enter your Dash wallet Address
                                    <a href="{{ route('edit_profile') }}">here</a> to start receiving tips! If you haven't 
                                    installed the app, you can download it from 
                                    <a href="https://play.google.com/store/apps/details?id=hashengineering.darkcoin.wallet&hl=es_CR" target="_blank">Google Play</a>
                                    or the
                                    <a href="https://apps.apple.com/us/app/dash-wallet/id1206647026" target="_blank">Apple Store</a>.
                                </div>
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
                    <div id="rep-col" class="col-md-3">
                        <div id="rep-card" class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Reputation</h5>
                                <p class="card-text">{{ Auth::user()->reputation_score }}</p>
                            </div>
                        </div>
                    </div>
                    <!---------------->

                    <!-- page link -->
                    <div id="link-col" class="col-md-9">
                        <div id="page-link-card" class="card text-white">
                            <div class="card-body">
                                <h5 class="card-title">Share your page link</h5>
                                
                                <!-- does the user have a username? -->
                                @if($username = Auth::user()->username)
                                    <form class="form-inline mr-0">

                                        <div class="input-group w-100">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-globe-americas" style="color:#008de4; font-size:22px;"></i>
                                                </span>
                                            </div>
                                            <input id="user-page" type="text" class="form-control" value="http://tipmedash.com/{{ $username }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-dark" type="button" onclick="copy_url()" id="btn">copy</button>
                                            </div>
                                        </div>
                                        <!-- <button class="ml-2 btn btn-info" onclick="copy_url()" id="btn">Copy</button> -->
                                    </form>
                                @else
                                    <p>
                                    <i class="fas fa-exclamation mr-2"></i>Not available. You need to <a href="{{ route('edit_profile') }}" style="color:#FEDD00;">create a username</a> to have your own page link.
                                    </p>
                                @endif

                                
                            </div>
                        </div>
                    </div>
                    <!-- end of page link --->

                    <!-- script for copy url button -->
                    <script>
                        function copy_url() {
                        var url = document.getElementById("user-page");
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

                @if(Auth::user()->wallet_address)
                <div class="row">

                    <!-- Dash wallet address -->
                    <div id="address-col" class="col-md-12">
                        <div id="wallet-address-card" class="card text-white">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="wallet_address" class="float-left">Dash wallet address </label>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend"> 
                                            <span id="dash-span" class="input-group-text" id="basic-addon1"><img class="dash-icon" width="22" src="{{ asset('images/blue-dash-icon.png') }}"></span>    
                                        </div>
                                        <input id="dash-address-input" class="form-control" type="text" name="wallet_address" value="{{ Auth::user()->wallet_address }}" readonly/>
                                        <div class="input-group-append">
                                            <button id="dropdown-btn" type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" onclick="copy_address()">Copy</a>
                                                <a class="dropdown-item" target="_blank" href="https://www.walletvalidator.com/dash-wallet-validator/{{ Auth::user()->wallet_address }}">Check address</a>
                                                <a class="dropdown-item" target="_blank" href="https://explorer.mydashwallet.org/address/{{ Auth::user()->wallet_address }}">Address details</a>
                                            </div>
                                        </div>
                                    </div>   
                                    
                                </div>
                            </div>
                        </div>                    
                    </div>
                    <!-- End of Dash wallet address -->

                </div><!--- End of second row -->
                @endif


                <!-- script to copy wallet address -->
                <script>
                    function copy_address() {
                    var url = document.getElementById("dash-address-input");
                    url.select();
                    url.setSelectionRange(0, 200)
                    document.execCommand("copy");
                    }
                </script>   
                <!---------------------------> 
                                            

            </div>
        </div>
        <!------ end of summary ------->                               

        <!-- new commers -->
        @if(Auth::user()->reputation > 50)
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
        @endif                            

        <div class="title-1">RECENT ACTIVITY</div>

        <!-- recent -->
        <div class="row">
            <div id="wrapper" class="mt-0 mx-auto">
                <ul class="list-group" style="width:100%;">

                    @if(count($recent_logs) < 1)
                        <div id="no-activity" class="alert border" role="alert">
                           No recent activity..
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

    <!-- modal balance -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #008de4;">
            <h5 class="modal-title" id="exampleModalLongTitle" style="color:white; font:20px 'montserrat',sans-serif;">Balance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body pb-0 pt-5" style="padding-left:100px;">
            <iframe frameborder="0"
        style="width: 100%; overflow:hidden;" src="https://explorer.mydashwallet.org/address/{{Auth::user()->wallet_address}}/balance/"></iframe>
        </div>
        </div>
    </div>
    </div>                
    <!---- end of modal ------->

</body>
@endsection