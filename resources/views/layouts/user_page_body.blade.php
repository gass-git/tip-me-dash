<!---- Style to override emojiOneArea plugin ---->
<style>
.custom-textarea {
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    width:100%;
    height: 89px!important;
    border: 1px solid rgb(77, 213, 255, 0.8);
    border-radius: 2px;
    background-color:rgb(0, 141, 228, 0.1);
    padding-top:3px;
}
</style>
<!----------------------------------------------->
    
<body>
    <section class="w-100 ">
        
        <!-- Header image -->
        @guest
                <div class="header-img w-100" style="background-image:url({{ $page_owner->header_img_url }})">
                </div>
        @endguest

        @auth
        
            <!-- The visitor is the page owner -->    
            @if(Auth::user()->id === $page_owner->id)

                <form class="main-form" action="{{ url('upload_header_img') }}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="header-img w-100 d-flex align-items-end pr-2" style="background-image:url({{ $page_owner->header_img_url }})">
                        <div class="ml-auto">
                            <label class="btn btn-sm btn-outline-light mr-2" for="input" id="input-btn" type="file" name="input">Change cover</label>
                            <input type="file" name="input" id="input" style="display:none">
                            <button id="save-btn" class="btn btn-sm btn-outline-success mr-2 mb-2" type="submit" style="display:none;">save</button>
                            <a id="cancel-btn" class="btn btn-sm btn-outline-danger mr-2 mb-2" style="display:none;" href="/{{ $page_owner->username }}">Cancel</a>
                        </div>
                    </div>
                </form>  

            @else
                <div class="header-img w-100" style="background-image:url({{  $page_owner->header_img_url }})">
                </div>
            @endif

        @endauth
        <!---- END of header image ---->

        <div class="user-page container">
            <div class="row">
                
                <!-- Left column -->
                <div class="col-sm-3 p-0 ml-5">

                    <!-- User profile -->
                    <div class="profile-box shadow">

                        <div class="avatar" style="background-image:url({{ $page_owner->avatar_url }})"></div>

                        <!-- About section -->
                        <div class="pl-4 pt-3 pr-3 pb-0">

                            <h5>ABOUT</h5>

                            <p>
                            @if($about = $page_owner->about)
                                {{ $about }}
                            @else
                                Hey 👋 I just created a page here. You can now buy me a coffee or a pizza with Dash!
                            @endif
                            </p>

                            <b>Passionate About</b>
                            <br>
                            <p>
                            @if($passion = $page_owner->passionate_about) 
                                {{ $passion }} 
                            @else 
                                N/A 
                            @endif
                            </p>

                            <b>Website</b>
                            <br>
                            <p>
                            @if($website_url = $page_owner->website)
                                @php
                                    $input = $website_url;

                                    // in case scheme relative URI is passed, e.g., //www.google.com/
                                    $input = trim($website_url, '/');

                                    // If scheme not included, prepend it
                                    if (!preg_match('#^http(s)?://#', $input)) {$input = 'http://' . $input;}

                                    $urlParts = parse_url($input);

                                    // remove www
                                    $friendly_url = preg_replace('/^www\./', '', $urlParts['host']); 
                                @endphp
                                <i class="fas fa-link mr-1"></i>
                                <a href="{{ $website_url }}" target="_blank" style="color:#c4a699;">
                                    {{ $friendly_url }} 
                                </a>
                            @else
                                N/A
                            @endif
                            </p>

                            <b>Profile Views:</b> {{ $page_owner->page_views }}

                        </div>
                        <!-- END of about section -->
                            
                        <hr class="mx-auto" style="width:80%;">

                        <!-- Social section -->
                        <div class="pl-4 pt-0 pr-4 pb-0">

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
                        <!-- END of social section -->

                        <hr class="mx-auto" style="width:80%;">

                        <!-- Hall of fame section -->
                        @if($number_of_tips > 1)
                            <div class="pl-4 pt-0 pr-4 pb-4 mt-3" style="font-size:15px;">
                    
                    
                                <div style="font-size:14px;">
                                <i class="fas fa-trophy mr-2" style="color:var(--dark-yellow);font-size:17px;"></i>
                                
                            
                                    @if($user_id = $biggest_tip->sender_id)
                                    
                                        @php 
                                            $date = \Carbon\Carbon::parse($biggest_tip->created_at)->isoFormat('MMM Do YYYY');
                                            $registered_user = App\User::where('id', $user_id)->first();
                                            $registered_tipper = $registered_user->username; 
                                            $avatar_url = $registered_user->avatar_url;
                                        @endphp
                                
                                        <a href="/{{ $registered_tipper }}" style="text-decoration: none!important;">
                                            <b>{{ $registered_tipper }}</b>
                                        </a>

                                    @elseif($tipper = $biggest_tip->sent_by)
                                        <b style="color:black;">{{ $tipper }}</b>
                                    @else
                                        <b style="color:black;">Incognito</b> 
                                    @endif

                                    tipped ${!! number_format((float)($biggest_tip->usd_equivalent), 1) !!} usd
                                    on {{ $date }} - Equivalent to {!! number_format((float)($biggest_tip->dash_amount), 3) !!} ᕭ
                                    at the time of transfer.
                                </div>   
                            </div>
                        @endif
                        <!-- END of hall of fame section -->

                    </div><!-- END of user profile -->       
                </div><!-- END of left column -->


                <!-- Right column -->
                <div class="right-col col-sm-8 ml-4 text-white">

                    <!-- Username & location -->
                    <a href="/{{ $page_owner->username }}" style="text-decoration: none; color:white;">
                        <span style="margin-bottom:0;font-size:30px;text-transform:capitalize;">{{ $page_owner->username }}</span>
                        @foreach($tips_sent as $tip_sent)
                            <span><img src="{{ Identicon::getImageDataUri($tip_sent->stamp) }}" width="20" height="20" ></span>
                        @endforeach
                        <br>
                        Budapest, HU
                    </a>
                    <!------------------------>

                    <!-- tipping form -->
                    <form class="main-form" 
                          action="{{ url('process_tip/' . $page_owner->username) }}" 
                          method="post" 
                          enctype="multipart/form-data" >
                          @csrf 

                        <div class="form-wrapper shadow-sm mt-5 mb-5">

                            <!-- Start of form box 1 -->
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
                                <!--- END of name input --->

                                <!-- amount input -->
                                <div class="input-group mb-0">
                                    @if(old('amount_entered'))
                                        <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" placeholder="5.00" value="{{ old('amount_entered') }}">
                                    @else
                                        <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" placeholder="5.00" value="5.00">
                                    @endif
                                </div>
                                <!--- END of amount input ---->

                                <center style="font-size: 10px;">USD</center>
                            
                            </div>
                            <!-- END of form box 1 -->

                            <!-- Message textarea -->
                            <div class="box2">
                                <textarea name="msg" class="custom-textarea" id="msg" placeholder="Optional message">{{ old('msg') }}</textarea>
                                @error('msg')
                                    <span style="color:red; font-size:13px;">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </span>
                                @enderror 

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
                                            travel_places: false
                                        }
                                    });
                                </script>
                                <!------------------>    

                            </div>
                            <!-- END of message textarea -->
     
                            <!-- submit tip button -->
                            <div class="box3">
                                <button class="tip-btn" type="submit">Tip</button>
                            </div> 
                            <!----------------------->

                        </div>
                    </form>
                    <!------ END of send tip form -------->


                    @foreach($tips as $tip)

                        @if($tip->sender_id)

                            @php 
                                $registered_user = App\User::where('id',$tip->sender_id)->first();
                                $registered_tipper = $registered_user->username; 
                                $avatar_url = $registered_user->avatar_url;
                            @endphp
                        
                        @endif

                        <div class="tip-box mb-4 mt-4 pt-2 pb-2 pl-0 pr-0">

                            <div class="row mb-0">

                                

                                <div class="col-sm-10">

                                    <div class="d-flex">

                                        <div class="p-2 ml-3 mt-2 mr-4 mb-0 msg" style="font-size:14px;">
                                            
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

                                                @if($tip->message)
                                                    + <span id="{{ $tip->id }}" class="show-msg" style="cursor:pointer;">
                                                        <i id="tip-msg-icon-{{ $tip->id }}" class="fas fa-envelope"></i>
                                                    </span>
                                                @endif    
                                            </p>

                                            @if($msg = $tip->message)
                                                <div id="tip-msg-{{ $tip->id }}" class="tip-msg" style="margin-top:-7px;display:none;">
                                                    {{ $msg }}
                                                </div>
                                            @endif    

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
                                        <a href="https://explorer.dash.org/insight/tx/{{ $tip->stamp }}" target="_blank" class="stamp my-auto " style="text-align:right;margin-right:23px; padding:7px;" title="Transaction stamp">    
                                            <img src="{{ Identicon::getImageDataUri($tip->stamp) }}" width="60" height="60" >
                                        </a> 
                                    </div>

                                </div>  

                            </div>    

                        </div>
                    
                    @endforeach

                    <!-- Open message JQuery -->
                    <script>
                        $('.show-msg').click(function(){
                            id = $(this).attr('id');
                            $('#tip-msg-'+id).toggle().css('display');
                            $('#tip-msg-icon-'+id).toggleClass('fa-envelope-open');
                        });
                    </script>   
                    <!-------------------------->

                </div> <!-- ENF of right column -->

            </div> <!-- END of row -->
        </div> <!-- END of user-page container -->
    </section> <!-- END of w-100 section -->
</body>

