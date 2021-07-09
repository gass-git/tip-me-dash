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
    <section class="w-100">
        
        <!------- Global php stdClass objects -------------->
        @php

            // Page owner tips
            $tips = App\Tip::where('recipient_id',$page_owner->id)
                        ->where('status','confirmed')
                        ->orderBy('id','DESC')
                        ->paginate(5);
        
            $supporters = App\Tip::where('recipient_id',$page_owner->id)
                                ->where('status','confirmed')
                                ->distinct('sender_ip')
                                ->count();

            // Amount of people tipped by a page owner               
            $people_tipped = App\Tip::where('sender_id',$page_owner->id)
                                ->where('status','confirmed')
                                ->distinct('recipient_id')
                                ->count();

            // Five most recent tips sent to different people                    
            $tips_sent = App\Tip::where('sender_id',$page_owner->id)
                        ->where('status','confirmed')
                        ->distinct('recipient_id')
                        ->latest()->take(5)->get();

            $biggest_tip = App\Tip::where('recipient_id',$page_owner->id)
                        ->where('status','confirmed')
                        ->orderBy('usd_equivalent','DESC')
                        ->first();

        @endphp
        <!------- END of global php stdClass classes ------->


        <!-- Header image -->
            @guest

                @if ($page_owner->header_img_url)
                    <div class="header-img w-100" style="background-image:url({{ $page_owner->header_img_url }})">
                    </div>
                @else  
                    <div class="header-img w-100" style="background-image:var(--blue-gradient-1)">
                    </div>
                @endif  

            @endguest

            @auth
            
                @if(Auth::user()->id === $page_owner->id) <!-- The logged user is the page owner -->    

                    <form class="main-form" action="{{ url('upload_header_img') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        @if ($page_owner->header_img_url)
                            <div class="header-img w-100 d-flex align-items-end pr-2"  style="background-image:url({{ $page_owner->header_img_url }})">
                        @else  
                            <div class="header-img w-100 d-flex align-items-end pr-2"  style="background-image:var(--blue-gradient-1)">
                        @endif  
                            <div class="ml-auto" style="z-index:2;">
                                <label class="btn btn-sm btn-outline-light mr-2" for="input" id="input-btn" type="file" name="input">Change cover</label>
                                <input type="file" name="image" id="input" style="display:none">
                                <button id="save-btn" class="btn btn-sm btn-success mr-2 mb-2" type="submit" style="display:none;">save</button>
                                <a id="cancel-btn" class="btn btn-sm btn-danger mr-2 mb-2" style="display:none;" href="/{{ $page_owner->username }}">Cancel</a>
                            </div>
                        </div>
                    </form>  

                    @error('image')
                        @php
                            Alert::toast($message, 'info');
                        @endphp
                    @enderror
                
                @else <!-- The logged visitor is not the page owner -->    

                    @if ($page_owner->header_img_url)
                        <div class="header-img w-100" style="background-image:url({{ $page_owner->header_img_url }})">
                        </div>
                    @else  
                        <div class="header-img w-100" style="background-image:var(--blue-gradient-1)">
                        </div>
                    @endif  

                @endif

            @endauth
        <!---- END of header image ---->

        <div class="user-page container">
            <div class="row">
                
                <!-- Left column -->
                <div class="col-md-3 p-0">

                    <!-- User profile -->
                    <div class="profile-box pb-4">

                        <div class="avatar" style="background-image:url({{ $page_owner->avatar_url }})"></div>


                        <!-- Tips sent -->
                        @if($people_tipped > 4)
                            <div class="d-flex flex-row-reverse pr-2" style="height:40px;">
                                
                                @foreach ($tips_sent as $sent)

                                    @php
                                        $recipient_username = App\User::where('id',$sent->recipient_id)->first()->username;
                                    @endphp

                                    <div class="small-stamp mt-2 mr-2 mb-2 ml-2">
                                        <a href="/{{ $recipient_username }}">
                                            <img src="{{ Identicon::getImageDataUri($sent->stamp) }}" width="25" height="25" >
                                        </a>
                                    </div>    
            
                                @endforeach

                            </div>
                        @endif
                        <!-- END of tips sent -->


                        <!-- Number pills -->
                        <div class="d-flex mt-2 pr-3 pt-2 pl-3 pb-2" style="height: 48px;">
                            
                            <div class="views-pill flex-fill mr-1 pt-1 pr-2 pb-1 pl-2" title="page views">
                                
                                <div class="d-flex">
                                    <div class="m-0 pl-1" style="width:30%;">
                                        <i class="far fa-eye m-0"></i> 
                                    </div>
                                    <div class="m-0 pl-2" style="width:70%;text-align:center;">
                                        {{ $page_owner->page_views }}
                                    </div>
                                </div>
            
                            </div> 

                            
                            <div class="score-pill ml-1 pt-1 pr-2 pb-1 pl-2" title="people tipped">
                                
                                <div class="d-flex">
                                    <div class="m-0 pl-1" style="width:30%;">
                                        ⁂
                                    </div>
                                    <div class="m-0 pl-2" style="width:70%;text-align:center;" title="points">
                                        {{ $page_owner->points }}
                                    </div>
                                </div>



                            </div>
                            
                        </div>
                        <!-- END of number pills -->

                        
                        <!-- About section -->
                        <div class="pt-3 pr-3 pb-0 pl-3">
                            <p>
                            @if($about = $page_owner->about)
                                {{ $about }}
                            @else
                                Hey 👋 I just created a page here. You can now buy me a coffee or a pizza with Dash!
                            @endif
                            </p>

                            @if($passion = $page_owner->passionate_about) 
                                <b>Passionate About</b>
                                <br>
                                <p>{{ $passion }}</p>
                            @endif
                            
                            @if($website_url = $page_owner->website)
                                @php
                                    $input = $website_url;
                                    $input = trim($website_url, '/');
                                    if (!preg_match('#^http(s)?://#', $input)) {$input = 'http://' . $input;}
                                    $urlParts = parse_url($input);
                                    $friendly_url = preg_replace('/^www\./', '', $urlParts['host']); 
                                @endphp
                                <b>Website</b>
                                <br>
                                <p>
                                <i class="fas fa-link mr-1"></i>
                                <a href="{{ $website_url }}" target="_blank">{{ $friendly_url }}</a>
                                </p>
                            @endif
                        </div>
                        <!-- END of about section -->
                            
                        <!-- Social section -->
                        @if($page_owner->twitter OR $page_owner->youtube OR $page_owner->github)
                            <hr class="mx-auto" style="width:80%;">
                            <div class="pl-3 pt-0 pr-4 pb-0">

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
                        <!-- END of social section -->


                        <!-- Hall of fame section -->
                        @if($supporters > 1)
                        <hr class="mx-auto" style="width:80%;">
                            <div class="pl-3 pt-0 pr-3 pb-4 mt-3" style="font-size:15px;">
                    
                                <div style="font-size:14px;">
                                    <span style="font-size:20px">🏆</span>
                                
                                    @php 
                                        $date = \Carbon\Carbon::parse($biggest_tip->created_at)->isoFormat('MMM Do YYYY');
                                    @endphp
                            
                                    @if($user_id = $biggest_tip->sender_id)
                                    
                                        @php 
                                            $registered_user = App\User::where('id', $user_id)->first();
                                            $registered_tipper = $registered_user->username; 
                                            $avatar_url = $registered_user->avatar_url;
                                        @endphp
                                
                                        <a href="/{{ $registered_tipper }}" style="text-decoration: none!important;">
                                            <b style="text-transform:capitalize;">{{ $registered_tipper }}</b>
                                        </a>

                                    @elseif($tipper = $biggest_tip->sent_by)
                                        <b style="color:black;text-transform:capitalize;">{{ $tipper }}</b>
                                    @else
                                        <b style="color:black;">Incognito</b> 
                                    @endif

                                    tipped ${!! number_format((float)($biggest_tip->usd_equivalent), 1) !!} usd
                                    on {{ $date }}, equivalent to {!! number_format((float)($biggest_tip->dash_amount), 3) !!} ᕭ
                                    at the time of transfer.
                                </div>   
                            </div>
                        @endif
                        <!-- END of hall of fame section -->

                    </div><!-- END of user profile -->       
                </div><!-- END of left column -->


                <!-- Right column -->
                <div class="right-col col-md-9">

                    <div class="d-flex">

                        <!-- Responsive avatar -->
                        <div>
                            <img id="mobile-avatar" class="mr-2" src="{{ $page_owner->avatar_url }}">
                        </div>

                        <!-- END of responsive avatar -->

                        <!-- Username & location -->
                        <a href="/{{ $page_owner->username }}" style="text-decoration: none; color:rgb(255,255,255,0.9);">
                            <p class="ml-2" style="margin-bottom:0;font-size:30px;text-transform:capitalize; line-height:23px;font-weight:300;">
                                {{ $page_owner->username }}
                            </p>
                            <span class="ml-2" style="padding-left:2px;word-spacing:1.5px;font-size:12px;font-weight:100; font-family:var(--roboto);font-weight:100;color:rgb(255,255,255,0.8);">
                                @if($location = $page_owner->location)
                                {{ $location }}
                                @else
                                    Location N/A
                                @endif
                            </span>
                        </a>
                        <!------------------------>

                    </div>    

                    <!-- tipping form -->
                    <form class="main-form" 
                          action="{{ url('process_tip/' . $page_owner->username) }}" 
                          method="post" 
                          enctype="multipart/form-data" >
                          @csrf 

                        <div class="border form-wrapper">

                            <!-- Start of form box 1 -->
                            <div class="box1 pb-0">
                                <!-- name input -->
                                @if(Auth::user())
                                    <input style="text-transform: capitalize;" 
                                           name="name" 
                                           type="text" 
                                           value="{{ Auth::user()->username }}" 
                                           class="form-control"
                                           readonly/>
                                @else
                                    <input name="name" type="text" class="form-control" onfocus="this.placeholder =''" onblur="this.placeholder = 'Name'" placeholder="Name"  " value="{{ old('name') }}">
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

                                <center><div style="font-size: 11px;color:grey;">USD</center>
                            
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

                                 

                            </div>
                            <!-- END of message textarea -->

                            <!-- Lock icon --->
                            <div id="lock-style" style="position: absolute;display:flex;right:125px;top:183px; cursor:pointer;">
                                <i id="lock" class="fas fa-lock-open" title="private message option"></i>
                                <input id="lock-checkbox" name="lock" type="checkbox" style="display:none;"/>
                            </div>
                            <!---------------->

                            <!-- submit tip button -->
                            <div class="box3">
                                <button class="tip-btn" type="submit">Tip</button>
                            </div> 
                            <!----------------------->

                        </div>
                    </form>
                    <!------ END of send tip form -------->


                    <!-- If user has not received tips show this -->
                    @if($supporters === 0)
                        <center style="color:grey; word-spacing:1px;">
                            <b style="text-transform:capitalize;color:var(--light-deep-blue);">
                                {{ $page_owner->username }}
                            </b> 
                            <span style="font-weight:200">has not received any tips yet</span>
                        </center>
                    @endif
                    <!---------------------------------------------->


                    <!----------- Tips section ------------------> 
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

                                <div class="col-sm-10 mr-0">

                                    <div class="d-flex mt-2">

                                        <div class="tip-body">
                                            
                                            <p class="d-flex tip-title align-items-lg-center p-2">

                                                <i class="fas fa-donate ml-0 mr-1" data-toggle="tooltip" data-placement="top" style="color:var(--dark-yellow);font-size:18px;" title="equivalent to ${{ $tip->usd_equivalent }} usd at the moment of transfer"></i>
                                            
                                                @if($tip->sender_id)
                                                    <a href="/{{ $registered_tipper }}" style="text-decoration: none!important;" title="Registered user">
                                                        <span class="ml-1" style="color:var(--light-deep-blue);text-transform:capitalize;">
                                                            {{ $registered_tipper }}
                                                        </span>
                                                    </a>    
                                                @elseif($tip->sent_by)
                                                    <span class="ml-1" style="color:var(--light-deep-blue);text-transform:capitalize;">
                                                        {{ $tip->sent_by }}
                                                    </span>
                                                @else
                                                    <span class="ml-1" style="color:var(--light-deep-blue);text-transform:capitalize;">
                                                        Incognito
                                                    </span>
                                                @endif

                                                <span class="ml-1 mr-1" style="color:#646464">Tipped </span>
                                                <span>{!! number_format((float)($tip->dash_amount), 5) !!} ᕭ</span>
                                                

                                                <!-- Envelope icon -->
                                                @if($tip->message AND $tip->private_msg === 'yes')

                                                    @guest
                                                        
                                                        <span class="show-msg" style="color:black;cursor:not-allowed;">
                                                            <i class="ml-2 fas fa-envelope" title="private message" style="padding-bottom:0px;"></i>
                                                        </span>

                                                    @endguest
                                                    
                                                    @auth
                                                        
                                                        @if(Auth::user()->id === $page_owner->id)

                                                            <span id="{{ $tip->id }}" class="show-msg" style="color:black">
                                                                <i id="tip-msg-icon-{{ $tip->id }}" class="ml-2 fas fa-envelope" title="show private message" style="padding-bottom:0px;"></i>
                                                            </span>

                                                        @else

                                                            <span class="show-msg" style="color:black;cursor:not-allowed;">
                                                                <i class="ml-2 fas fa-envelope" title="private message" style="padding-bottom:0px;"></i>
                                                            </span>

                                                        @endif

                                                    @endauth

                                                @elseif($tip->message AND $tip->private_msg === null)
                                                
                                                    <span id="{{ $tip->id }}" class="show-msg">
                                                        <i id="tip-msg-icon-{{ $tip->id }}" class="ml-2 fas fa-envelope" title="show private message" style="padding-bottom:0px;"></i>
                                                    </span>

                                                @endif
                                                <!-- END of envelope icon -->

                                            </p><!-- END of tip tittle -->

                                            <!---- Message content section ------>
                                            @if($tip->message AND $tip->private_msg === 'yes')

                                                @auth

                                                    @if(Auth::user()->id === $page_owner->id)

                                                        <div id="tip-msg-{{ $tip->id }}" class="border tip-msg pt-3 pr-4 pb-3 pl-4 mt-3 mb-3" style="display:none;">
                                                            {{ $tip->message }}
                                                        </div>    
 
                                                    @endif

                                                @endauth

                                            @elseif($tip->message AND $tip->private_msg === null)
                                            
                                                <div id="tip-msg-{{ $tip->id }}" class="border tip-msg pt-3 pr-4 pb-3 pl-4 mt-3 mb-3" style="display:none;">
                                                    {{ $tip->message }}
                                                </div>

                                            @endif    
                                            <!--- END of message content section ----->


                                            <!--- Tip footer info and praise buttons --->    
                                            <p class="mt-4 mb-0" style="font-size:11px;color:grey;">
                                                
                                                @php
                                                    $praise = App\Tip::where('id',$tip->id)->first()->praise;
                                                @endphp
            
                                                <!-- Visitor not logged in -->
                                                @guest
                                                    
                                                    <span style="font-size:13px"> ― </span> 
                                                    <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}} - 
                                                        <span style="cursor:help" data-toggle="tooltip" data-placement="top" title="dash/usd exchange rate at the moment of transfer">DP ${{ $tip->dash_usd }}</span>
                                                    </span>
                                                
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
            
                                                    <!-- Looged user is the page owner -->
                                                    @if(Auth::user()->id === $page_owner->id)
            
                                                        <span id="auth-tip-date" style="font-size:13px"> ― </span> 
                                                        <span id="auth-tip-date" class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}} - 
                                                            <span style="cursor:help" data-toggle="tooltip" data-placement="top" title="dash/usd exchange rate at the moment of transfer">DP ${{ $tip->dash_usd }}</span>
                                                        </span>
                                                        
                                                    <span class="praise-icons" id="{{ $tip->id }}">
                                                        
                                                        @if($praise === 'like')
                                                            <a id="like-{{ $tip->id }}" class="like mr-3"  title="Like it" style="color:#008de4; cursor:pointer;"><i class="fas fa-thumbs-up" style="font-size:16px;"></i></a>
                                                        @else
                                                            <a id="like-{{ $tip->id }}" class="like mr-3"  title="Like it" style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-thumbs-up" style="font-size:16px;"></i></a>
                                                        @endif
                                                        
                                                        @if($praise === 'love')
                                                            <a id="love-{{ $tip->id }}" class="love mr-3" title="love it"  style="color:red; cursor:pointer;"><i class="fas fa-heart" style="font-size:16px;"></i></a>
                                                        @else
                                                            <a id="love-{{ $tip->id }}" class="love mr-3" title="love it"  style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-heart" style="font-size:16px;"></i></a>
                                                        @endif

                                                        @if($praise === 'brilliant')
                                                            <a id="brilliant-{{ $tip->id }}" class="brilliant mr-3" title="It's brilliant" style="color:rgb(238, 204, 13); cursor:pointer;"><i class="fas fa-lightbulb" style="font-size:16px;"></i></a>
                                                        @else
                                                            <a id="brilliant-{{ $tip->id }}" class="brilliant mr-3" title="It's brilliant" style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-lightbulb" style="font-size:16px;"></i></a>
                                                        @endif

                                                        @if($praise === 'cheers')
                                                            <a id="cheers-{{ $tip->id }}" class="cheers mr-3" title="Cheers!"  style="color:#FFA900; cursor:pointer;"><i class="fas fa-beer" style="font-size:16px;"></i></a>
                                                        @else
                                                            <a id="cheers-{{ $tip->id }}" class="cheers mr-3" title="Cheers!"  style="color:rgb(175, 175, 175); cursor:pointer;"><i class="fas fa-beer" style="font-size:16px;"></i></a>
                                                        @endif

                                                    </span>
            
            
                                                    <!-- Logged user is NOT the page owner -->    
                                                    @else
            
                                                    
                                                        <span style="font-size:13px"> ― </span> 
                                                        <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}} - 
                                                            <span style="cursor:help" data-toggle="tooltip" data-placement="top" title="dash/usd exchange rate at the moment of transfer">DP ${{ $tip->dash_usd }}</span>
                                                        </span>
                                                        
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
                                            <!--- END of tip footer info and praise buttons -->

                                        </div>

                                    </div>   

                                </div>

                                <div class="col-sm-2">

                                    <div class="stamp d-flex flex-row-reverse mt-3 mr-4" style="height:60px;">
                                        <a href="https://explorer.dash.org/insight/tx/{{ $tip->stamp }}" 
                                           target="_blank" 
                                           class="stamp" 
                                           title="tx stamp">

                                           <img src="{{ Identicon::getImageDataUri($tip->stamp) }}" width="65" height="65" >
                                        </a> 
                                    </div>

                                </div>  

                            </div>    

                        </div>
                    
                    @endforeach
                    <!--------- END of tip section  ------------------> 

                </div> <!-- END of right column -->
            </div> <!-- END of row -->
        </div> <!-- END of user-page container -->
    </section> <!-- END of w-100 section -->
</body>

