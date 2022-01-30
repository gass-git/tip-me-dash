<div class="profile-box pb-4">

    <div class="avatar" style="background-image:url({{ $page_owner->avatar_url }})"></div>

    <!-- Tips sent --> {{-- Show if the user has tipped 5 different people or more --}}
    @if($people_tipped >= 5)
        <div id="tips-record" class="d-flex flex-row-reverse justify-content-center" style="height:40px">
            
            @foreach ($tips_sent as $sent)

                @php

                    /** @abstract
                     * 
                     *  Possible scenario: the recipient of the tip deletes his account.
                     * 
                     */
                    $regd_recipient = App\User::where('id',$sent->recipient_id)->first();

                @endphp

                @if($regd_recipient)

                    <div class="small-stamp mt-2 mr-2 mb-2 ml-2">
                        <a href="/{{ $regd_recipient->username }}">
                            <img src="{{ Identicon::getImageDataUri($sent->stamp) }}" width="25" height="25" >
                        </a>
                    </div>  

                @else

                    <div class="small-stamp mt-2 mr-2 mb-2 ml-2">
                        <img src="{{ Identicon::getImageDataUri($sent->stamp) }}" width="25" height="25" title="the recipient of this tip deleted his account">
                    </div>  

                @endif

            @endforeach

        </div>
    @endif
    <!-- END of tips sent -->

    <!-- Number pills -->
    <div class="d-flex mt-2 pr-3 pt-2 pl-3 pb-2" style="height: 48px;">
        
        <div class="views-pill flex-fill mr-1 pt-1 pr-2 pb-1 pl-2" title="unique visitors">
            
            <div class="d-flex">
                <div class="m-0 pl-1" style="width:30%;">
                    <i class="far fa-eye m-0"></i> 
                </div>
                <div class="m-0 pl-2" style="width:70%;text-align:center;">
                    {{ $page_owner->page_views }}
                </div>
            </div>

        </div> 

        
        <div class="score-pill ml-1 pt-1 pr-2 pb-1 pl-2">
            
            <div class="d-flex">
                <div class="m-0 pl-1" style="width:30%;">
                    ⁂
                </div>
                <div class="m-0 pl-2" style="width:70%;text-align:center;" title="mesh points">
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
        <div class="pl-3 pt-0 pr-3 pb-0 mt-3 mb-0" style="font-size:15px;">

            <p>
                <span style="font-size:20px">🏆</span>
            
                @php 

                    /** @abstract 
                    * 
                    * SCENARIOUS:
                    * 
                    * 1) Tip has sender_id and the tipper has not deleted his acc
                    * 2) Tip has sender_id but the tipper has deleted his acc
                    * 3) Tip has a tipper name
                    * 4) Tip has no sender_id and no tipper name (Incognito)
                    *
                    */

                    $date = \Carbon\Carbon::parse($biggest_tip->created_at)->isoFormat('MMM Do YYYY');
                    $regd_tipper = App\User::where('id', $biggest_tip->sender_id)->first();

                @endphp
        
                @if($biggest_tip->sender_id AND $regd_tipper)
                
                    <a href="/{{ $regd_tipper->username }}" style="text-decoration: none!important;">
                        <b style="text-transform:capitalize;">{{ $regd_tipper->username }}</b>
                    </a>

                @elseif($biggest_tip->sender_id AND $regd_tipper === null)    

                    <b style="color:var(--deep-blue-1);text-transform:capitalize;" title="this user has deleted his account">{{ $biggest_tip->sent_by }}</b>

                @elseif($biggest_tip->sent_by)

                    <b style="color:var(--deep-blue-1);text-transform:capitalize;">{{ $biggest_tip->sent_by }}</b>

                @else

                    <b style="color:var(--deep-blue-1)">Incognito</b> 

                @endif

                tipped ${!! number_format((float)($biggest_tip->usd_equivalent), 1) !!} usd
                on {{ $date }}, equivalent to {!! number_format((float)($biggest_tip->dash_amount), 3) !!} ᕭ
                at the time of transfer.
            </p>   
        </div>
    @endif
    <!-- END of hall of fame section -->

</div>