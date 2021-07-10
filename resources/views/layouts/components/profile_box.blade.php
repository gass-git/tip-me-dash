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

</div>