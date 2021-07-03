<!---- Style to override emojiOneArea plugin ---->
<style>
.custom-textarea {
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    width:100%;
	height: 89px!important;
	border: 1px solid rgb(77, 213, 255, 0.8);
	border-radius: 5px;
    background-color:rgb(0, 141, 228, 0.1);
    padding-top:3px;
}
</style>
<!----------------------------------------------->

<body>

    <section class="user-page container">
        <div class="row">
            
            <!-- First column -->
            <div class="col-md-3 pl-0 pt-2 pr-0 pb-2">

                <!-- Profile box -->
                <div class="profile-box">
                    
                    <div class="username">
                        {{ $page_owner->username }} 
                    </div>
                    
                    <img class="mt-2" id="avatar-img" src="{{ $page_owner->avatar_url }}">

                    <!-- About -->
                    <div style="margin:30px;">
                        <div id="about">
                        @if($page_owner->about)
                            {{ $page_owner->about }}
                        @else
                            Hey 👋 I just created a page here. You can now buy me a coffee or a pizza with Dash!
                        @endif
                        </div>
                    </div>
                    <!----------->         
                    
                </div>
                <!-- END of avatar card -->

                <!-- Social links --->
                @if($page_owner->twitter OR $page_owner->youtube OR $page_owner->github)
                    <div class="pl-4 pt-4 pr-3 pb-4 mt-3" id="simpleBox">
                        
                        @if($page_owner->twitter)
                            <div class="mt-0">
                                <a href="https://twitter.com/{{ $page_owner->twitter }}" target="_blank">
                                    <img alt="Twitter Follow" src="https://img.shields.io/twitter/follow/{{ $page_owner->twitter }}?style=social">
                                </a>
                            </div>
                        @endif

                        @if($page_owner->youtube)
                            <div class="mt-2">
                                <a href="https://youtube.com/channel/{{ $page_owner->youtube }}" target="_blank">
                                    <img src="https://img.shields.io/youtube/channel/views/{{ $page_owner->youtube }}?style=social">
                                </a>
                            </div>
                        @endif

                        @if($page_owner->github)
                            <div class="mt-2">
                                <a href="https://github.com/{{ $page_owner->github }}" target="_blank">
                                    <img src="https://img.shields.io/github/followers/{{ $page_owner->github }}?style=social">
                                </a>
                            </div>
                        @endif

                    </div>
                @endif
                <!-- END of social links -->

                @if($number_of_tips > 1)
                <div class="pl-4 pt-4 pr-4 pb-4 mt-3" id="simpleBox" style="font-family: sans-serif;font-size:15px;">
                    <i class="fas fa-trophy mr-2" style="color:var(--dark-yellow);"></i><b style="color:#008de4;">Hall of Fame</b>
                    <hr class="mt-1 mr-4">
                    <div style="font-size:12px;">
                        <b><span style="color:var(--light-deep-blue);">
                                
                                @if($user_id = $biggest_tip->sender_id)
                                
                                    @php 
                                        $registered_user = App\User::where('id', $user_id)->first();
                                        $registered_tipper = $registered_user->username; 
                                        $avatar_url = $registered_user->avatar_url;
                                    @endphp
                            
                                    <a href="/{{ $registered_tipper }}" style="text-decoration: none!important;">
                                        {{ $registered_tipper }}
                                    </a>

                                @elseif($tipper = $biggest_tip->sent_by)

                                    {{ $tipper }}

                                @else
                                                            
                                    Incognito 
                                
                                @endif


                            </span></b>
                            tipped
                            <span style="color:#008de4;">
                                ${{ $biggest_tip->usd_equivalent }} usd
                            </span>
                                in 
                                <span style="color:rgb(0,0,0,0.8);">
                                {{ \Carbon\Carbon::parse($biggest_tip->created_at)->isoFormat('MMM Do YYYY')}}</span> - Equivalent to 
                                <span style="color:#008de4;">{!! number_format((float)($biggest_tip->dash_amount), 4) !!} ᕭ</span>
                                 at the time of transfer.
                            
                    </div>   
                </div>
                @endif

            </div>
            <!-- END of first column -->

            <!-- Second column -->
            <div class="col-md-9 pt-2 pr-0 pb-2" id="second-col">
 
                <!-- about section start -->
                <div class="about-wrapper">

                    <!-- Extra info header -->
                    <div class="extra-info">
                        <div class="row mt-2">

                            <!-- Left extra info column -->
                            <div class="col pr-0" style="min-width:120px;">
                                <p><b>Location</b><br> @if($location = $page_owner->location) {{ $location }} @else N/A @endif </p>
                                <p><b>Passionate About</b><br> @if($passion = $page_owner->passionate_about) {{ $passion }} @else N/A @endif</p>
                            </div>
                            <!------------------------------>

                            <!-- Right extra info column -->
                            <div class="col">
                                
                                <!-- Personal website -->
                                <p><b>Website</b><br>
                                @if($website_url = $page_owner->website)
                                    
                                    <!-- php: make the url friendly -->
                                    @php
                                        {{

                                        $input = $website_url;

                                        // in case scheme relative URI is passed, e.g., //www.google.com/
                                        $input = trim($website_url, '/');

                                        // If scheme not included, prepend it
                                        if (!preg_match('#^http(s)?://#', $input)) {
                                            $input = 'http://' . $input;
                                        }

                                        $urlParts = parse_url($input);

                                        // remove www
                                        $friendly_url = preg_replace('/^www\./', '', $urlParts['host']);

                                        }}
                                    @endphp
                                    <!--- end of php ---->
                                    <i class="fas fa-link mr-1"></i><a href="{{ $website_url }}" target="_blank" style="color:#c4a699;">{{ $friendly_url }} </a></p>
                                @else
                                    N/A
                                @endif
                                <!-- End of personal website -->

                                <p><b>Profile Views</b><br> {{ $page_owner->page_views }}</p>
                                
                            </div>
                            <!-- END of extra info -->

                        </div>
                    </div>
                    <!-- END of extra info section -->

                </div>
                <!-- about section end -->

                <!-- tipping form -->
                <div class="col-md-12 p-0 mt-3">
                    
                    <form class="main-form" 
                          action="{{ url('process_tip/' . $page_owner->username) }}" 
                          method="post" 
                          enctype="multipart/form-data" >
                          @csrf 

                        <div class="form-wrapper">

                            <div class="box1 pb-0">

                                 <!-- name input -->
                                @if(Auth::user())
                                    <input style="text-transform: capitalize;" 
                                           name="name" 
                                           type="text" 
                                           value="{{ Auth::user()->username }}" 
                                           class="form-control"
                                           readonly>
                                @else
                                    <input name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name') }}">
                                    @error('name')
                                        <span style="color:red; font-size:13px;">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                @endif

                                <!-- amount input -->
                                <div class="input-group mb-0">
                                    @if(old('amount_entered'))
                                        <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" placeholder="5.00" value="{{ old('amount_entered') }}">
                                    @else
                                        <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" placeholder="5.00" value="5.00">
                                    @endif
                                </div>

                                <center style="font-size: 10px;">USD</center>

                            </div>

                            <!-- optional message input -->
                            <div class="box2">
                                <textarea name="msg" class="custom-textarea" id="msg" placeholder="Optional message">{{ old('msg') }}</textarea>
                                @error('msg')
                                    <span style="color:red; font-size:13px;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror 
                            </div>

                            <!-- emoji plugin -->
                            <script>
                                $("#msg").emojioneArea({
                                    pickerPosition: "bottom",
                                    filtersPosition: "bottom",
                                    tonesStyle: "square",
                                    shortnames: true,
                                    tones:false,
                                    search:false,
                                    filters: {
                                        flags : false,
                                        animals_nature: false,
                                        activity: false,
                                        travel_places: false,
                                        symbols: false
                                    }
                                });
                            </script>
                            <!------------------>

                            <!-- submit tip button -->
                            <div class="box3">
                                <button class="tip-btn" type="submit">Tip</button>
                            </div> 
                            <!----------------------->

                        </div>

                    </form>

                </div>    
                <!------------------->

                <div class="title-1 ml-1 mt-4 mb-3">RECENT TIPS</div>
 
                @foreach($tips as $tip)

                    @if($tip->sender_id)

                        @php 
                            $registered_user = App\User::where('id',$tip->sender_id)->first();
                            $registered_tipper = $registered_user->username; 
                            $avatar_url = $registered_user->avatar_url;
                        @endphp
                    
                    @endif


                    <div class="tip-box mb-4 pt-2 pb-2 pl-0 pr-0">

                        <div class="row mb-0">

                            <div class="col-sm-10">

                                <div class="d-flex">

                                    <div class="p-2 ml-4 mt-2 mr-5 mb-0 msg" style="font-size:14px;">
                                        
                                        <p class="tip-title">

                                            <i class="fas fa-donate ml-1 mr-1" style="color:#c5ab84;cursor:pointer;font-size:18px;" title="Equivalent to US${{ $tip->usd_equivalent }} at the moment of transfer"></i>
                                        
                                            @if($tip->sender_id)
                                                <a href="/{{ $registered_tipper }}" style="text-decoration: none!important;" title="Registered user">
                                                    <span style="color:var(--light-deep-blue);">
                                                        {{ $registered_tipper }}
                                                    </span>
                                                </a>    
                                            @else
                                                <span style="color:var(--light-deep-blue);">
                                                    {{ $tip->sent_by }}
                                                </span>
                                            @endif

                                            Tipped <span>{!! number_format((float)($tip->dash_amount), 5) !!} ᕭ</span>
                                        
                                        </p>

                                        <div style="margin-top:-7px;">
                                            {{ $tip->message }}
                                        </div>
                                        
                                        <p class="mt-2 mb-0" style="font-size:11px;color:grey;">
                                            
                                            @php
                                                $praise = App\tip::where('id',$tip->id)->first()->praise;
                                            @endphp
        
                                            <!-- Visitor not logged in -->
                                            @guest
                                                
                                                <span style="font-size:13px"> ― </span> 
                                                <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}}</span>
                                            
                                                @if($praise === 'like')
                                                    ⁂<i class="fas fa-thumbs-up ml-2" style="color:var(--dash-blue);font-size:12px;"></i> Thanks! this is great
                                                @endif
                                                
                                                @if ($praise === 'love')
                                                    ⁂<i class="fas fa-heart ml-2" style="color:red;font-size:12px;"></i> Love it!
                                                @endif
        
                                                @if ($praise === 'brilliant')
                                                    ⁂<i class="fas fa-lightbulb ml-2" style="color:rgb(238, 204, 13);font-size:12px;"></i> This is brilliant
                                                @endif
        
                                                @if ($praise === 'cheers')
                                                    ⁂<i class="fas fa-beer ml-2" style="color:#FFA900; font-size:12px;"></i> Cheers!
                                                @endif
                                                    
                                            @endguest
                                            <!---------------------------->
        
                                            <!-- Logged in user -->
                                            @auth
        
                                                <!-- Visitor is the page owner -->
                                                @if(Auth::user()->id === $page_owner->id)
        
                                                
                                                <span style="font-size:13px"> ― </span> 
                                                <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}}</span>
    
                                                <span class="ml-5" id="{{ $tip->id }}" style="color:#c5ab84; ">
                                                    
                                                    @if($praise === 'like')
                                                        <a class="like mr-3"  title="Like it" style="color:#008de4; cursor:pointer;"><i class="fas fa-thumbs-up" style="font-size:16px;"></i></a>
                                                    @else
                                                        <a class="like mr-3"  title="Like it" style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-thumbs-up" style="font-size:16px;"></i></a>
                                                    @endif
                                                    
                                                    @if($praise === 'love')
                                                        <a class="love mr-3" title="love it"  style="color:red; cursor:pointer;"><i class="fas fa-heart" style="font-size:16px;"></i></a>
                                                    @else
                                                        <a class="love mr-3" title="love it"  style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-heart" style="font-size:16px;"></i></a>
                                                    @endif
    
                                                    @if($praise === 'brilliant')
                                                        <a class="brilliant mr-3" title="It's brilliant" style="color:rgb(238, 204, 13); cursor:pointer;"><i class="fas fa-lightbulb" style="font-size:16px;"></i></a>
                                                    @else
                                                        <a class="brilliant mr-3" title="It's brilliant" style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-lightbulb" style="font-size:16px;"></i></a>
                                                    @endif
    
                                                    @if($praise === 'cheers')
                                                        <a class="cheers mr-3" title="Cheers!"  style="color:#FFA900; cursor:pointer;"><i class="fas fa-beer" style="font-size:16px;"></i></a>
                                                    @else
                                                        <a class="cheers mr-3" title="Cheers!"  style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-beer" style="font-size:16px;"></i></a>
                                                    @endif
    
                                                </span>
        
        
                                                <!-- Visitor is NOT the page owner -->    
                                                @else
        
                                                
                                                    <span style="font-size:13px"> ― </span> 
                                                    <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}}</span>
                                                    
                                                    @if($praise === 'like')
                                                        ⁂<i class="fas fa-thumbs-up ml-2" style="color:var(--dash-blue);"></i> Thanks! this is great
                                                    @endif
                                                    
                                                    @if ($praise === 'love')
                                                        ⁂<i class="fas fa-heart ml-2" style="color:red;"></i>  Love it!
                                                    @endif
        
                                                    @if ($praise === 'brilliant')
                                                        ⁂<i class="fas fa-lightbulb ml-2" style="color:rgb(238, 204, 13);"></i>  This is brilliant
                                                    @endif
        
                                                    @if ($praise === 'cheers')
                                                        ⁂<i class="fas fa-beer ml-2" style="color:#FFA900;"></i> Cheers!
                                                    @endif
        
                                                @endif
        
                                            @endauth  
                                            <!---------------------->
    
                                        </p>
        
                                    </div>

                                </div>   

                            </div>

                            <div class="col-sm-2">

                                <div class="d-flex flex-row-reverse" style="height:100%;">
                                    <a href="https://explorer.dash.org/insight/tx/{{ $tip->stamp }}" target="_blank" class="stamp my-auto " style="text-align:right;margin-right:35px; padding:7px;" title="Transaction stamp">    
                                        <img src="{{ Identicon::getImageDataUri($tip->stamp) }}" width="60" height="60" >
                                    </a> 
                                </div>

                            </div>   

                        </div>    

                    </div>
                    
                @endforeach

                <!-- pagination -->
                <center>
                    <div class="mt-3" style="display:inline-block;">
                        {{ $tips->links() }}
                    </div>
                </center>
                <!------------------------->

            </div>  
        </div>

        
        <!-- modal:dont know how it works? -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Don't know how it works?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- first paragraph -->
                        <i class="fas fa-arrow-alt-circle-right mr-2" style="color:#008de4;"></i>If you have the 
                        <a href="https://www.dash.org/downloads/" target="_blank">Dash app</a> with available balance, go ahead and scan
                        the QR code and send some Dash. 
                    
                        <!-- second paragraph -->
                        <div class="mt-2">
                            <i class="fas fa-arrow-alt-circle-right mr-2" style="color:#008de4;"></i>In case you don't have the app and would
                            like to know more <a href="https://www.dash.org/individuals/" target="_blank">click here</a>.
                        </div>

                        <!-- third paragraph -->
                        <div class="mt-2">
                            <i class="fas fa-arrow-alt-circle-right mr-2" style="color:#008de4;"></i>In case you know absolutely nothing
                                or don't have much time we recommend this 
                                <a href="https://www.youtube.com/watch?v=4OvRs8sT5FM&list=PLfmi5pQYsS8OwkTZ-pAF5YCy_ljkLeihB&index=17" target="_blank">46 sec video.</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->

    </section>


</body>