@extends('layouts/app')
@section('content')
<body class="blue-gradient">
    <!-- header section start -->
    <section class="header d-flex align-items-center shadow">
        <div class="container mb-5">
            <div class="row align-items-center">
                <div class="col-md-8 mt-4 pt-3 pb-4 pr-0">
                    <div class="welcome-text">
                        <h1 class="pr-5"><span>The easiest way</span> to say thanks online</h1>
                        <p>Receive tips instantly from followers directly to your pocket, without third parties involved.
                        Give fans a new way to show appreciation for your work.
                        </p>
                        <div class="buttons-wrapper mr-0 ml-0 mb-4">
                            <a id="community-btn" href="{{ route('community_activity') }}" class="btn btn-1 bg-primary"><i class="far fa-comments mr-2"></i>Community Activity</a>
                            <a id="start-btn" href="{{ route('register') }}" class="btn btn-1 bg-primary ml-3"><i class="fas fa-rocket mr-2"></i>Start Now</a>
                            <button href="#myModal" data-toggle="modal" type="button" class="btn play-btn">
                                <i class="fas fa-play"></i> 
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center p-0">
                    <div class="welcome-image">
                        <img src=" {{ asset('images/dash-coins-3.png') }} " title="Image by farber-alex (Freepick)">
                        <!-- image by farber-alex https://www.freepik.com/premium-vector/dash-cryptocurrency-tokens_10274762.htm -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- header section end --> 

    <!-- catchy section 1 -->
    <section class="catchy">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="catchy-title">
                        <h1>No clicks, <span>just a scan..</span></h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="catchy-text">
                        <p>Experience the easiest and fastest way to say thanks in the Internet. 
                            Fans do not need to register, type credit card information or any other
                             tedious procedure. For supporters to send a tip they just need to scan 
                             the QR code on your TMD page with their Dash app and they are done! Tips will be received by you in under a minute.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sub-header section end -->

    <!-- features section -->
    <section class="features shadow">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="features-title">
                        <h1>Have fun <span>instantly</span> with your <img class="pb-2" height="40px" src="{{ asset('images/black-dash-logo-h-40.png') }}"></h1>
                    </div>
                </div>
            </div>
            <div class="row pt-5">
                <div class="col-md-4">
                
                    <div class="card border">
                        <div class="card-body">
                            <div class="icon mt-3"><img src="{{ asset('images/dashy-santa-100.png') }}"></div>
                            <h5 class="card-title mt-4">Spend</h5>
                            <p class="card-text" style="font-size:14px;">Did you know there are thousands of merchants accepting Dash?</p>
                            <a href="https://cryptwerk.com/pay-with/dash/" target="_blank" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-body">
                        <div class="icon mt-3"><img src="{{ asset('images/dashy-jump-100.png') }}"></div>
                            <h5 class="card-title mt-4">Earn Passive Income</h5>
                            <p class="card-text" style="font-size:14px;">Put your Dash to work by staking and earn high yields.</p>
                            <a href="https://www.youtube.com/watch?v=fjj6bQga_sE" target="_blank" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border">
                        <div class="card-body">
                        <div class="icon mt-3"><img src="{{ asset('images/dashy-laptop-100.png') }}"></div>
                            <h5 class="card-title mt-4">Trade</h5>
                            <p class="card-text" style="font-size:14px;">Transfer your Dash to exchanges and trade for other crypto assets.</p>
                            <a href="https://www.dash.org/traders/" target="_blank" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- features section end -->

    <!-- get started section -->
    <section class="get-started">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="get-started-title">
                        <h1><span>Get started,</span> is easy..</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">1</div>
                        <h5>Sign up</h5>
                        <p><a href="{{ route('register') }}">Registering</a> won't take you more than a minute. You can 
                    use your Google acc if you want. </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">2</div>
                        <h5>Download the app</h5>
                        <p>Install the DashPay wallet app and set it up in just a few easy steps.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">3</div>
                        <h5>setup your TMD account</h5>
                        <p>Customize your page and remember to set up your wallet address so the QR code is displayed on your TMD page. </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">4</div>
                        <h5>All done!</h5>
                        <p>You can now start sharing your own TMD 
                        url to recieve tips in Dash and <a href="{{ route('community_activity') }}" >connect with others</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- get started section -->

    <!-- catchy section 2 -->
    <section class="catchy-2 shadow-sm">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="catchy-2-title">
                        <h1>Anyone can join, <span>Dash is permissionless</span></h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="catchy-2-text">
                        <p>No annoying procedures, start experience the freedom of crypto and connecting with other Dashers.
                           <a href="https://cryptwerk.com/pay-with/dash/" target="_blank">Spend</a> 
                           and make <a href="https://www.youtube.com/watch?v=fjj6bQga_sE" target="_blank">passive income with high yields</a>
                            as soon as you receive tips.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sub header 2 -->



    <!-- community section-->
    <section class="community">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="community-title">
                        <h1>Community</h1>
                    </div>
                </div>
            </div>
            <div class="row pt-2">

                <div class="col-md-6">
                    
                <div class="title-1" style="color:white;">RECENTLY REGISTERED</div>

                @foreach($newcomers->take(10) as $newcomer)
                @if($username = $newcomer->username)
                <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}" >
                    <img id="avatar" src="{{ $newcomer->avatar_url }}" style="width:25px;height:25px;border-radius:50%;"></img>
                    <span class="ml-2" style="text-transform: capitalize; color:#008de4; font-weight:600;">{{ $username }}</span>
                    <span id="join-date" class="float-right">Joined in {{ date('F d,Y', strtotime($newcomer->created_at)) }}</span>
                </a>
                @endif
                @endforeach

                </div>
                <div class="col-md-6">
                    
                <div class="title-1" style="color:white;">REPUTATION LEADERBOARD</div>

                @foreach($ranking->take(10) as $rank)

                @if($username = $rank->username)
                <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}">
                    <img id="avatar" src="{{ $newcomer->avatar_url }}" style="width:25px;height:25px;border-radius:50%;"></img>
                    <span class="ml-2" style="text-transform: capitalize; color:#008de4; font-weight:600;">{{ $username }}</span>
                    <span id="reputation-info" class="float-right">Reputation score: {{ $rank->reputation_score }}</span>
                </a>
                @endif
                    
                @endforeach

                </div>
                
            </div>
        </div>
    </section>
    <!-- sub-header section end -->

    <!-- footer -->
    <section class="footer shadow">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="footer-content">
                        <a href="http://www.dash.org" target="_blank"><img src="{{ asset('images/dashpowered.png') }}"></a>
                        <p class="mt-3 mb-1">
                        Copyright © 2021 Budapest HU | <a href="{{ url('/') }}">Tipmedash.com</a> | Developed by <a href="http://www.gabrielsalinas.codes" target="_blank">Gabriel Salinas</a> | All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sub-header section end -->

    <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: transparent; border:none;">
                <div class="modal-header p-0 m-0" style="background-color: transparent!important;border:none!important;">
                    <button type="button" class="close pr-3" data-dismiss="modal" style="color:white;">&times;</button>
                </div>
                <div class="modal-body p-0" style="background-color: transparent;">
                    <div class="embed-responsive embed-responsive-16by9" style="border-radius: 10px;width:100%;max-width:900px;">
                        <iframe id="dash-video"
                        src="https://www.youtube.com/embed/QDVLMegSnBQ" 
                        title="YouTube video player" frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------->

   <!-- scripts ------------------>
   <script>
        $(document).ready(function(){
            
            // -- script to stop the video from playing when closing modal ---
            var url = $("#dash-video").attr('src');
            
            $("#myModal").on('hide.bs.modal', function(){
                $("#dash-video").attr('src', '');
            });
            
            $("#myModal").on('show.bs.modal', function(){
                $("#dash-video").attr('src', url);
            });
            // ---------------------------------------------------------------

            // -- card hover effect -------
            $( ".card" ).hover(
                function() { $(this).addClass('shadow').css('cursor', 'pointer'); }, 
                function() { $(this).removeClass('shadow'); }
            );
            // ----------------------------

        });
    </script>
    <!----------------------------->

</body>
 
@endsection