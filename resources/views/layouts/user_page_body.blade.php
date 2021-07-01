<!---- Style to override emojiOneArea plugin ---->
<style>
.custom-textarea {
    font-size: 12px;
    width:100%;
	height: 84px!important;
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
                    <div class="mt-4 mb-4 mr-4 ml-4">
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
                    <div class="pl-4 pt-3 pr-3 pb-3 mt-3" id="simpleBox">
                        
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
                <div class="pl-4 pt-3 pr-4 pb-4 mt-3" id="simpleBox" style="font-family: sans-serif;font-size:13px;">
                    <i class="fas fa-trophy mr-2" style="color:var(--dark-yellow);"></i><b style="color:#008de4;">Hall of Fame</b>
                    <hr class="mt-1 mr-4">
                    <div style="font-size:11px;">
                        <b><span style="color:var(--light-deep-blue);">
                                @if($biggest_tip->sent_by)
                                {{ $biggest_tip->sent_by }}
                                @else Incognito @endif
                            </span> 
                        </b>
                            tipped
                            <span style="color:#008de4;">
                                ${{ $biggest_tip->usd_equivalent }} usd
                            </span>
                                in 
                                <span style="color:rgb(0,0,0,0.8);">
                                {{ \Carbon\Carbon::parse($biggest_tip->created_at)->isoFormat('MMM Do YYYY')}}</span> - Equivalent to 
                                <span style="color:#008de4;">{{ $biggest_tip->dash_amount }} DASH</span>
                                 at the time of transfer.
                            
                    </div>   
                </div>
                @endif

            </div>
            <!-- END of first column -->

            <!-- Second column -->
            <div class="col-md-9 pl-3 pt-2 pr-0 pb-2">
 
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

                        <div class="form-wrapper pr-4 pl-4 pb-0" style="border:1px solid #d6ddfc;background-color: rgba(255, 255, 255, 0.8);">

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
                                    <input name="name" type="text" class="form-control" placeholder="name (optional)" value="{{ old('name') }}">
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
                                <textarea name="msg" class="custom-textarea" id="msg" placeholder="optional message">{{ old('msg') }}</textarea>
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

                <div class="title-1 mt-4 mb-2">RECENT TIPS</div>

                <!-- guestbook -->
                
                @if($number_of_tips < 1)
                    <div class="tip-info pt-2 pr-0 pl-4 mt-3 pb-2" style="background-color: rgba(255, 255, 255, 0.3);">
                        <span style="color:var(--light-deep-blue); text-transform:capitalize;">{{ $page_owner->username }}</span> 
                        has not received any tips yet
                    </div>
                @endif

                @foreach($tips as $tip)

                    @if($tip->message)
                        
                        <div class="tip-info pt-3 pr-0 pl-4 mt-3 pb-0" style="background-color: rgba(255, 255, 255, 0.7);">
                            <span style="color:var(--light-deep-blue); text-transform:capitalize;">
                                @if($tip->sender_id)
                                    @php $tipper = App\User::where('id',$tip->sender_id)->first()->username; @endphp
                                    <a href='/{{ $tipper }}'>{{ $tipper }}</a>
                                @elseif($tipper = $tip->sent_by)
                                    {{ $tipper }}
                                @else 
                                    Incognito
                                @endif
                            </span> 
                            <span>tipped</span>
                            <span style="color:#008de4;">{{ $tip->dash_amount }} <b style="font-size:16px; ">ᕭ</b></span>
                            <p class="mt-3 mr-4">{{ $tip->message }}</p>
                            <p class="mt-0 mr-4 mb-2" style="color: #c2c2c2; font-size: 11px; float: right;">
                                Equivalent to ${{ $tip->usd_equivalent }} USD at the moment of transfer
                            </p>
                        </div>

                    @else

                        <div class="tip-info pt-2 pr-0 pl-4 mt-3 pb-2" style="background-color: rgba(255, 255, 255, 0.3);">
                            <span style="color:var(--light-deep-blue);">@if($tip->sent_by){{ $tip->sent_by }}@else Incognito @endif</span> 
                            <span>tipped</span> 
                            <span style="color:#008de4;">{{ $tip->dash_amount }} <b style="font-size:16px; ">ᕭ</b></span>
                            <div class="mt-1 mr-4 mb-0" style="color: rgb(0, 141, 228,0.4); font-size: 17px; float: right;">
                                <i class="fas fa-info-circle" title="Equivalent to ${{ $tip->usd_equivalent }} USD at the moment of transfer"></i>
                            </div>
                        </div>

                    @endif

               @endforeach


                <!-- end of guestbook -->
                
                <!--  pagination -->
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