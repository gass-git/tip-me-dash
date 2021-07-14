@extends('layouts/app')
@section('content')
@include('layouts/components/navbar_one')

<body>
    <section class="dashboard container">
        
        <div class="row">

            <!-- welcome to the dashboard section -->
            <div id="welcome" class="ml-0 pt-3">

                @if(Auth::user()->username)
                    <a id="username" href="{{ route('user_page',Auth::user()->username) }}">{{ Auth::user()->username }}</a>, welcome to your dashboard
                @else
                    Welcome to your dashboard 
                @endif

            </div>
            <!-------------------------------------->

        </div><!-- END of row -->

        <!-- Pending alerts -->
        @if(!Auth::user()->wallet_address OR !Auth::user()->username)
                
            <div class="row">
                
                <!-- Username pending -->
                @if(!Auth::user()->username)
                <div class="alert alert-primary" role="alert" style="width:100%;font-size:15px;">
                    
                    <i class="far fa-bell mr-2" style="font-size:17px;"></i>
                    It seems you don't have a username. In order to activate your own TMD page you need to 
                    <a href="{{ route('edit_profile') }}">create one</a>.  
                
                </div>
                @endif
                <!---------------------->
                
                <!-- wallet address pending -->
                @if(!Auth::user()->wallet_address)
                <div class="alert alert-primary" role="alert" style="width:100%;font-size:15px;">
                    
                    <i class="far fa-bell mr-2" style="font-size:17px;"></i>
                    Enter your Dash wallet address
                    <a href="{{ route('edit_profile') }}">here</a> to start receiving tips. If you haven't 
                    installed the app, you can download it from 
                    <a href="https://play.google.com/store/apps/details?id=hashengineering.darkcoin.wallet&hl=es_CR" target="_blank">Google Play</a>
                    or the
                    <a href="https://apps.apple.com/us/app/dash-wallet/id1206647026" target="_blank">App Store</a>.
                
                </div>
                @endif
                <!----------------------------->

            </div><!-- END of row -->

        @endif
        <!------ END pending alerts ------->


        <!-- Avatar and page link box -->   
        <div class="row p-0 mb-3" id="first-box">
  
            <!-- Avatar column -->
            <div class="col-sm-3 pl-4 pt-4 pr-0 pb-3">
                <img src="{{ Auth::user()->avatar_url }}">
            </div>
            <!---------------->

            <!-- Right column -->
            <div class="col-sm-9 pl-2 pt-3 pr-2 pb-3">

                <div id="page-link">
                    <h5 class="pb-2">Share your page link</h5>
                    
                        <!-- Does the user have a username? -->
                        @if($username = Auth::user()->username)
                            <form class="form-inline">
                                <div class="input-group" style="width: 100%;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-globe-americas" style="color:#008de4; font-size:22px;"></i>
                                        </span>
                                    </div>
                                    <input id="personal-url" type="text" class="form-control" value="https://tipmedash.com/{{ $username }}" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-dark" type="button" onclick="copy_url()" id="btn">copy</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p>
                            <i class="fas fa-exclamation mr-2"></i>Not available. You need to <a href="{{ route('edit_profile') }}" style="color:#008de4;">create a username</a> to have your own page link.
                            </p>
                        @endif
                </div>        
            </div>
            <!-- END of right column -->

        </div>
        <!-- END avatar and page link box -->
                    
        <!-- script for copy url button -->
        <script>
            function copy_url() {
            var url = document.getElementById("personal-url");
            url.select();
            url.setSelectionRange(0, 200)
            document.execCommand("copy");

            var btn = document.getElementById("btn");
            btn.innerHTML = "Copied";
            setTimeout(() => {document.getElementById("btn").innerHTML = "Copy"}, 2000);
            }
        </script>     
        <!--------------------------->


        <!-- Tips stats -->
        <div class="row p-3" id="tips-stats">
            
            @php 
                use Carbon\Carbon;

                $confirmed_tip = App\Tip::where('recipient_id',Auth::user()->id)->where('status','confirmed');
                $number_of_tips = $confirmed_tip->count();
                $dash_30_days = $confirmed_tip->whereDate('created_at', '>', Carbon::now()->subDays(30))->sum('dash_amount');
                $usd_30_days = $confirmed_tip->whereDate('created_at', '>', Carbon::now()->subDays(30))->sum('usd_equivalent');
                $dash_all_time = $confirmed_tip->sum('dash_amount');
                $usd_all_time = $confirmed_tip->sum('usd_equivalent');
            @endphp

            <div class="col-sm-4">
                <div class="pt-4" id="style-one">{{ $number_of_tips }}</div>
                <div class="pt-2" id="style-two">Tips</div>
            </div>

            <div class="col-sm-4" style="border-left:1px solid rgba(138, 138, 138, 0.4);">
                <div class="mt-4 p-0" id="style-one">
                    <span style="vertical-align:middle;">
                        {!! number_format((float)($dash_30_days), 3) !!} 
                    </span>
                    <img class="align-items-center" src="{{ asset('images/dash-icon.png') }}">
                </div>
                <div class="pt-2" id="style-three">Equivalent to US${{ $usd_30_days }}</div>
                <div class="pt-2" id="style-two">Last 30 days</div>
            </div>

            <div class="col-sm-4" style="border-left:1px solid rgba(138, 138, 138, 0.4);">
                <div class="mt-4 p-0" id="style-one">
                    <span style="vertical-align:middle;">
                        {!! number_format((float)($dash_all_time), 3) !!} 
                    </span>
                    <img class="align-items-center" src="{{ asset('images/dash-icon.png') }}">
                </div>
                <div class="pt-2" id="style-three">Equivalent to US${{ $usd_all_time }}</div>
                <div class="pt-1" id="style-two">All time</div>
            </div>

        </div>    
        <!-- END of tips stats -->

        <!-- Adress box -->
        @if(Auth::user()->wallet_address)
            <div class="row mt-3 text-white" id="address-box">
                <div class="col-sm-12 pt-3 pl-4">
                    
                    <h5 class="mt-1">Dash wallet address</h5>

                    <div class="form-group mt-4 mr-3 mb-4">
                        <div class="input-group mb-1">
                            <div class="input-group-prepend"> 
                                <span id="dash-span" class="input-group-text"><img class="dash-icon" width="22" src="{{ asset('images/blue-dash-icon.png') }}"></span>    
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

            </div><!--- End of row -->
        @endif
        <!-- END of address box -->

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
                                        

        <div class="title-1 mt-4" style="font-size:17px;"><i class="fas fa-broadcast-tower mr-2"></i>RECENT ACTIVITY</div>


        <!-- Recent activity section -->
        <div class="row mt-3">
            <div class="m-0 p-0" style="width:100%;">
                
                <!-- Continue if the authenticated user has events in his log -->
                @if($number_of_events > 0)

                    <ul class="list-group">

                        <!-- Loop through the last events -->
                        @foreach($events as $event)

                            <li class="list-group-item">

                                <i class="fas fa-angle-right mr-2" style="color:var(--light-deep-blue);font-size:18px;"></i>
                                
                                @if($event->type === 'tip')

                                    <b style="color:var(--light-deep-blue);text-transform:capitalize;">
                                        @if($event->from_id)

                                            @php
                                                $tipper = app\user::where('id',$event->from_id)->first()->username; // User who tipped
                                            @endphp

                                            <a href="/{{ $tipper }}" style="color:#225ccf;">
                                                {{ $tipper }}
                                            </a>

                                        @else
                                            {{ $event->guest_name }} 
                                        @endif
                                    </b>

                                    @php    
                                        $recipient = app\user::where('id',$event->to_id)->first()->username; // User who received the tip
                                    @endphp

                                    <a href="/{{ $recipient }}">
                                        {{ $event->p2p_event }}
                                    </a>

                                @endif


                                @if($event->type === 'praise' AND $event->from_id)

                                    @php 
                                        $clapper = app\user::where('id',$event->from_id)->first()->username;  // User who praises
                                        $recipient = app\user::where('id',$event->to_id)->first()->username;  // User who receives the praise
                                    @endphp

                                    <b style="color:var(--light-deep-blue);text-transform:capitalize;">

                                        <a href="/{{ $clapper }}" style="color:#225ccf;">
                                            {{ $clapper }}
                                        </a>
                            
                                    </b>         

                                    <a href="/{{ $clapper }}">
                                        {{ $event->p2p_event }}
                                    </a>

                                @endif

                                <span id="date-span">
                                    {{ Carbon::parse($event->created_at)->isoFormat('MMM Do YYYY')}}
                                </span>
                        
                            </li>

                        @endforeach    
                        <!-- END of events loop -->

                    </ul>       

                <!-- The user doesn't have events on his log -->    
                @else
                    <div class="no-activity-box pl-4 pt-2 pb-2">
                        No recent activity
                    </div> 
                @endif

            </div>
        </div>
        <!------ END of recent activity section ----->

         <!--  pagination -->
         <center>
            <div class="mt-3" style="display:inline-block;">
                {{ $events->links() }}
            </div>
        </center>
        <!------------------------->
        
    </section><!-- END of dashboard container -->
</body>
@endsection