@extends('layouts/app')
@section('content')
@include('layouts/components/navbar_one')

<body id="top" class="blue-gradient">
    <!-- header section start -->
    <section class="header d-flex align-items-center shadow">
        <div class="container mb-5">
            <div class="row align-items-center">
                <div class="col-md-8 mt-4 pt-3 pb-4 pr-0">
                    <div class="welcome-text">
                        
                        <h1 class="pr-5 ml-1"><span>An easy way</span> to give thanks online</h1>

                        <div class="alert alert-info mr-5 mt-3" role="alert">
                            <h4 class="alert-heading">Hi there 👋 the site is currently inactive</h4>
                            <p>We're awaiting approval or refusal from the <a href="https://dash.org" target="_blank">Dash</a> community regarding our proposal. 
                                If the proposal is approved, the site will be activated with all its features.   
                        </p>
                            <hr>
                            <p class="mb-0">If you want to know more about it click 👉  <a href="https://www.dash.org/forum/index.php?threads/pre-proposal-tipmedash-com-a-fast-fun-way-to-tip-online.53610/" target="_blank">here</a></p>
                        </div>
                        <!--
                        <p>Receive tips instantly from followers directly to your pocket, without third parties involved.
                        Give fans a new way to show appreciation for your work.
                        </p>
                        -->
                        

                        
                        <div class="buttons-wrapper mr-0 ml-0 mb-4">
                            <!--
                            <a id="community-btn" href="{{ url('recent') }}" class="btn btn-1 bg-primary"><i class="far fa-comments mr-2"></i>Latest donations</a>
                            <a id="start-btn" href="{{ route('register') }}" class="btn btn-1 bg-primary ml-3"><i class="fas fa-rocket mr-2"></i>Start my page</a>
                            -->
                            <a id="community-btn" href="#myModal" data-toggle="modal" type="button" class="btn btn-1 bg-primary"><i class="fas fa-play mr-2"></i> Get Inspired </a> 
                            <!--
                            <button href="#myModal" data-toggle="modal" type="button" class="btn play-btn">
                               get inspired 
                            </button>
                            -->
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
                        <h1>Scan and send<span> digital cash</span></h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="catchy-text">
                        <p>Experience the easiest and fastest way to say thanks in the Internet. 
                            Fans do not need to register, type credit card information or any other
                             tedious procedure. For supporters to send a tip they just need to scan 
                             a QR code with their <a href="https://www.dash.org/individuals/" target="_blank" title="Learn more">Dash Wallet App</a> and they are done! 
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
                            <h5 class="card-title mt-4">Trade and Invest</h5>
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
                        <h1><span>Get started,</span> it's easy..</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">1</div>
                        <h5>Sign up</h5>
                        <!--
                        <p><a href="{{ route('register') }}">Registering</a> won't take you more than a minute. You can 
                    use your Google acc. </p>
                        -->
                        <p>Registering won't take you more than a minute. You can 
                    use your Google acc. </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">2</div>
                        <h5>Download the app</h5>
                        <p>The Dash Wallet App can be found in <a href="https://play.google.com/store/apps/details?id=hashengineering.darkcoin.wallet&hl=es_CR" target="_blank">Google Play</a>
                         or in the <a href="https://apps.apple.com/us/app/dash-wallet/id1206647026" target="_blank">App store</a>.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">3</div>
                        <h5>setup the acc</h5>
                        <p>Customize your page and enter your DASH wallet address.
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="how-it-works-item">
                        <div class="step">4</div>
                        <h5>All done!</h5>
                        <p>You can now start sharing your own TMD 
                        url to recieve tips in Dash and connect with others.</p>
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
                           <a href="https://cryptwerk.com/pay-with/dash/" target="_blank" title="Learn more">Spend</a> 
                           and make <a href="https://www.youtube.com/watch?v=fjj6bQga_sE" target="_blank" title="Learn more">passive income with high yields</a>
                            as soon as you receive tips.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sub header 2 -->
    
    <!--
    @include('layouts/components/stats')
    -->
    @include('layouts/components/footer_one')

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