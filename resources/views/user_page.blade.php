@extends('layouts/app')
@section('content')
<body>
    <section class="user-page container">
        <div class="row">
            
            <div class="col-md-3 p-2">

                <!-- avatar card start -->
                <div class="border avatar-card card">
                    <div class="username">
                        {{ $page_owner->username }} 
                    </div>
                    
                    <img id="avatar-img" src="{{ asset($page_owner->avatar_url) }}">
                    <div class="reputation-score" title="A high reputation can indicate a person worth checking out.">{{ $page_owner->reputation_score }}</div>
                    
                    <!-- boost reputation button -->
                    @if(empty($reputation_click->status))
                    <button type="submit" value="inactive" id="rep-btn" class="reputation-btn shadow-none btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus mr-2"></i>Boost Reputation
                    </button>
                    @else
                    <button type="submit" value="active" id="rep-btn" class="reputation-btn shadow-none btn btn-sm btn-outline-primary">
                        <i class="fas fa-check mr-2"></i>Unboost Reputation
                    </button>
                    @endif

                    <script>
                    $('.reputation-btn').on('click',function(event){

                        var btn = this;
                        var route = "{{ route('boost_reputation') }}";
                        var csrf_token = '{{ csrf_token() }}';
                        var id = '{{ $page_owner->id }}';
                        var active_html = '<i class="fas fa-check mr-2"></i>Unboost Reputation';
                        var inactive_html = '<i class="fas fa-plus mr-2"></i>Boost Reputation';
                        var rep_score_element = $(".reputation-score");
                        var rep_score = parseInt($(".reputation-score").html());

                        $.ajax({
                            method: 'POST',
                            url: route,
                            data: {page_owner_id:id, _token:csrf_token},
                            success:function() {
                                if(btn.value == 'inactive'){
                                    btn.value = 'active';
                                    btn.innerHTML = active_html;
                                    $(".reputation-score").html(rep_score + 5);
                                    }else{
                                        btn.value = 'inactive';
                                        btn.innerHTML = inactive_html;
                                        $(".reputation-score").html(rep_score - 5);
                                }
                            },
                            error: function () {
                                window.location.reload();
                                
                            }
                        });

                    });
                    </script>
                    <!---------------------------->

                </div>
                <!-- avatar card end -->

                @if($page_owner->twitter)
                <!-- Badges card -->
                <div class="border badges-card">
                    <a href="https://twitter.com/{{ $page_owner->twitter }}" target="_blank">
                        <img alt="Twitter Follow" src="https://img.shields.io/twitter/follow/{{ $page_owner->twitter }}?style=social">
                    </a>
                </div>
                <!-- End of badges card -->
                @endif

                <!-- QR code card start -->
                <div class="border qr-card card">

                    @if($page_owner->wallet_address)
                    <div class="tip-msg">
                        Send <span>{{ $page_owner->username }}</span> some <img width="19" src="{{ asset('images/small-blue-dash-icon.png') }}">
                    </div>
                    
                    <img class="qr-box border"
                         id="qr-box"
                         onclick="copy_address()" 
                         data-toggle="tooltip" 
                         data-placement="right" 
                         title=""
                         src="data:image/png;base64, {{ base64_encode(QrCode::color(1, 32, 96)->format('png')->errorCorrection('H')->style('round')->size(250)->merge('http://tipmedash.com/images/dash-qr-deep-blue-logo.png',0.22,true)->generate($page_owner->wallet_address)) }}">
                    
                    <a id="sub-qr-link" data-toggle="modal" data-target="#exampleModal">Don't know how it works?</a>
                    @else
                    <center class="mt-1 mb-3">The QR code is not available.</center> 
                    @endif
                </div>
                <!-- QR code card end -->

                

            </div>
            <div class="col-md-9 p-2">

                <!-- message form start -->
                <div class="border send-message-box">
                    <form class="main-form" action="{{ route('post_message',['username' => $page_owner->username]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group m-0">
                            <textarea name="message" class="custom-textarea">{{ old('message') }}</textarea>
                            <!-- emoji plugin -->
                            <script>
                                $(".custom-textarea").emojioneArea({
                                    shortnames: true,
                                    tones:false,
                                    search:false,
                                    pickerPosition: "bottom",
                                    placeholder: "Write something on {{ $page_owner->username }} page..",
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
                        </div>
                        
                        <div class="form-group float-right">
                            <button type="submit" class="post-button shadow-none btn btn-sm btn-outline-primary"><i class="fas fa-pencil-alt mr-2"></i>Post</button>
                        </div>
                    </form>
                </div>
                <!-- message form end -->          

                <!-- about section start -->
                <div class="border about-card card">
                    
                    <div class="about-me m-0">
                    @if($about = $page_owner->about) 
                    {!! nl2br(e($about)) !!}
                    @else
                    It seems that <b>{{ $page_owner->username }}</b> prefers to keep an air of mistery...
                    @endif
                    </div>

                    
                    <!------ optional header image --------
                    <div class="image-frame pl-2 pr-2">
                        <div class="optional-img" style="background-image:url(images/heroes.jpg);">
                        </div>
                    </div>
                    --------------------------------------->
                    
                    <div class="extra-info">
                        <div class="row mt-2">
                            <div class="col pr-0" style="min-width:120px;">
                                <p><b>Passionate About</b><br> @if($passion = $page_owner->passionate_about) {{ $passion }} @else N/A @endif</p>
                                <p><b>Location</b><br> @if($location = $page_owner->location) {{ $location }} @else N/A @endif </p>
                                <p><b>Favorite Crypto</b><br>@if($favorite_crypto = $page_owner->favorite_crypto)  {{ $favorite_crypto }} @else N/A @endif</p>
                            </div>
                            <div class="col">
                                <p><b>Profile Views</b><br> {{ $page_owner->page_views }}</p>
                                
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

                                <p><b>Desired Superpower</b><br>@if($superpower = $page_owner->desired_superpower)  {{ $superpower }} @else N/A @endif</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- about section end -->

                <div class="title-1">RECENT MESSAGES</div>

                <!-- guestbook -->
                <div class="guestbook card border">

                    @if(count($posts) < 1)
                        <div class="ml-4" style="font-size:15px;">
                        <span style="color:#225ccf;text-transform:capitalize;font-weight:bold; font-size:16px;">{{ $page_owner->username }}</span> has not received any messages yet.    
                        </div>
                    @endif

                    <!-- loop start -->
                    @foreach($posts as $post)
                    <div class="row pt-2 pr-4 pb-3 pl-4">
                        
                        <!-- note: if the visitor has a username, then show the avatar and username with a link to their TMD page -->
                        @if($username = App\User::where('id',$post->author_id)->first()->username)
                        <div class="visitor-avatar col ml-4">
                            <a href="{{ route('user_page',$username) }}"><img src="{{ App\User::where('id',$post->author_id)->first()->avatar_url }}">
                        </div>

                        <div class="col p-0 ml-3 mr-4 text-left" style="min-width: 180px!important;"">
                            
                            <!-- username -->
                            <a class="visitor-username" href="{{ route('user_page',$username) }}">{{ $username }}</a>
                        @else
                        <!-- note: show "someone" if the visitor doesn't have a username -->
                        <div class="visitor-avatar col ml-4">
                            <img src="{{ App\User::where('id',$post->author_id)->first()->avatar_url }}">
                        </div>

                        <div class="col p-0 ml-3 mr-4 text-left" style="min-width: 280px!important;">
                            
                            <!-- username -->
                            <a class="visitor-username">Someone</a>
                            <!-------------->
                        @endif

                            <!-- visitor/s messages -->
                            <div class="msg-box">
                                <p>{!! nl2br(e($post->message)) !!}</p>
                            </div>
                            <!--------->
                            
                            <!-- like/love/brilliant btns -->
                            @guest <!-- first case: the visitor is an unregistered guest -->

                                    @if($post->likes_it === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-thumbs-up" style="color:#008de4;"></i> {{ $page_owner->username }}</b> likes this.
                                    </div>
                                    @endif
                                    
                                    @if($post->loves_it === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-heart" style="color:red;"></i> {{ $page_owner->username }}</b> loves this.
                                    </div>
                                    @endif

                                    @if($post->brilliant === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-lightbulb" style="color:#d5b60a;"></i> {{ $page_owner->username }}</b> thinks this is brilliant. 
                                    </div>
                                    @endif
                            
                            @endguest

                            @auth <!-- second case: a logged visitor that is not the page owner -->

                                @if(Auth::user()->id !== $page_owner->id)
                                
                                    @if($post->likes_it === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-thumbs-up" style="color:#008de4;"></i> {{ $page_owner->username }}</b> likes this.
                                    </div>
                                    @endif
                                    
                                    @if($post->loves_it === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-heart" style="color:red;"></i> {{ $page_owner->username }}</b> loves this.
                                    </div>
                                    @endif

                                    @if($post->brilliant === 1)
                                    <div class="post-feedback">
                                        <b><i class="fas fa-lightbulb" style="color:#d5b60a;"></i> {{ $page_owner->username }}</b> thinks this is brilliant. 
                                    </div>
                                    @endif

                                @else
                                <div class="praise-or-delete row" id="{{ $post->message_id }}"> <!-- third case: the logged visitor is the page owner -->
                                    
                                    <!-- like btn -->
                                    @if($post->likes_it === 0)
                                    <a class="likes_it" title="Like it"><i class="fas fa-thumbs-up"></i></a>
                                    @else
                                    <a class="likes_it" title="Unlike this?" style="color:#008de4;"><i class="fas fa-thumbs-up"></i></a>
                                    @endif                                
                                    <!-------------->

                                    <!-- loves it btn -->
                                    @if($post->loves_it === 0)
                                    <a class="loves_it ml-3" title="Love it"><i class="fas fa-heart"></i></a>
                                    @else
                                    <a class="loves_it ml-3" title="Don't love this anymore?" style="color:red;"><i class="fas fa-heart"></i></a>
                                    @endif                                
                                    <!------------------>
                                    
                                    <!-- its brilliant btn -->
                                    @if($post->brilliant === 0)
                                    <a class="brilliant ml-3" title="It's brilliant!"><i class="fas fa-lightbulb"></i></a>
                                    @else
                                    <a class="brilliant ml-3" title="Don't think is brilliant anymore?" style="color:#d5b60a;"><i class="fas fa-lightbulb"></i></a>
                                    @endif                                
                                    <!------------------>

                                    <!-- delete post --->
                                    <a class="delete-post border">Delete</a>
                                    <!------------------>

                                </div>

                                @endif
                            @endauth
                            <!-- end of like/love/brilliant btns section --->
                            
                        </div>
                    </div>
                    @endforeach
                    <!-- loop finish -->

                    <script>
                        // global variables
                        var csrf_token = '{{ csrf_token() }}';
                        var gray = 'rgb(211, 211, 211)';
                        var dark_yellow = 'rgb(213, 182, 10)';
                        var dash_blue = '#008de4';

                        // --------- like btn script -------------------------------
                        $('.likes_it').on('click', function(event) {   
                            
                            var route = "{{ route('likes_it') }}";

                            // get the id of the post
                            var post_id = $(this).parent().attr('id');

                            // get the element clicked
                            var element = $('#' + post_id).children().eq(0);

                            // change element color and title on click
                            if(element.css('color') === gray){
                                element.css('color',dash_blue);
                                element.attr('title', 'Unlike this?');
                            }else{
                                element.css('color',gray);
                                element.attr('title', 'Like it');
                            }

                            // set the 2nd & 3rd children elements to default color and title
                            $('#' + post_id).children().eq(1).css('color',gray);
                            $('#' + post_id).children().eq(1).attr('title','Love it');
                            $('#' + post_id).children().eq(2).css('color',gray);
                            $('#' + post_id).children().eq(2).attr('title',"It's brilliant!");

                            $.ajax({
                                method:'POST',
                                url: route, 
                                data:{message_id:post_id,_token:csrf_token} 
                            });
                        });
                        // ---------------------------------------------------------

                        // ------------- loves it btn script -----------------------
                        $('.loves_it').on('click', function(event) {
                            
                            var route = "{{ route('loves_it') }}";

                            // get the id of the post
                            var post_id = $(this).parent().attr('id');

                            // get the element clicked
                            var element = $('#' + post_id).children().eq(1);

                            // change element color and title on click
                            if(element.css('color') === gray){
                                element.css('color','red');
                                element.attr('title', "Don't love this anymore?");
                            }else{
                                element.css('color',gray);
                                element.attr('title', 'Love it');
                            }

                            // set the 1st & 3rd children elements to default color
                            $('#' + post_id).children().eq(0).css('color',gray);
                            $('#' + post_id).children().eq(0).attr('title','Like it');
                            $('#' + post_id).children().eq(2).css('color',gray);
                            $('#' + post_id).children().eq(2).attr('title',"It's brilliant!");

                            $.ajax({
                                method:'POST',
                                url: route, 
                                data:{message_id:post_id,_token:csrf_token} 
                            });
                        });
                        // -------------------------------------------------------

                        // ------------- brilliant btn script --------------------
                        $('.brilliant').on('click', function(event) {
                            
                            var route = "{{ route('brilliant') }}";

                            // get the id of the post
                            var post_id = $(this).parent().attr('id');

                            // get the element clicked
                            var element = $('#' + post_id).children().eq(2);

                            // change element color and title on click
                            if(element.css('color') === gray){
                                element.css('color',dark_yellow);
                                element.attr('title', "Don't think is brilliant anymore?");
                            }else{
                                element.css('color',gray);
                                element.attr('title', "It's brilliant!");
                            }

                            // set the 1st & 3rd children elements to default color and title
                            $('#' + post_id).children().eq(0).css('color',gray);
                            $('#' + post_id).children().eq(0).attr('title','Like it');
                            $('#' + post_id).children().eq(1).css('color',gray);
                            $('#' + post_id).children().eq(1).attr('title','Love it');

                            $.ajax({
                                method:'POST',
                                url: route, 
                                data:{message_id:post_id,_token:csrf_token} 
                            });
                        });
                        // -------------------------------------------------------

                        // ------------ delete post script -----------------------
                        $('.delete-post').on('click',function(event){

                            var route = "{{ route('delete_post') }}";

                            // get the id of the post
                            var post_id = $(this).parent().attr('id');

                            Swal.fire({
                                title: 'Are you sure you want to delete this post?',
                                showDenyButton: true,
                                confirmButtonText: `Yes`,
                                DenyButtonText:`No`,
                                }).then((result) => {
            
                                if (result.isConfirmed) {

                                     // send data to controller to delete message from db
                                     $.ajax({
                                                method: 'POST',
                                                url: route,
                                                data: {msg_id: post_id, _token:csrf_token},
                                                success:function() {
                                                    window.location.reload();
                                                }
                                            });

                                }
                            })
                        });
                        // -------------------------------------------------------

                    </script>

                </div>
                <!-- end of guestbook -->
                
                <!-- messages pagination -->
                <div class="mt-3">{{ $posts->links() }}</div>
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

    <script>
        // -- card hover effect -------
        $( ".qr-box" ).hover(
            function() { $(this).addClass('shadow-sm').css('cursor', 'pointer'); }, 
            function() { $(this).removeClass('shadow-sm'); }
        );
        // ----------------------------   
        
        //  -- Change Tooltip Text on mouse enter ----
        $(document).ready(function() {
            $('#qr-box').mouseenter(function () {
                $('#qr-box').attr('title', '{{ $page_owner->wallet_address }}').tooltip('dispose');
                $('#qr-box').tooltip('show');
            });
        });
        // --------------------------------------------

        // ------ Copy address --------        
        function copy_address() {

        var dummy = document.createElement("textarea");
        // to avoid breaking orgain page when copying more words
        // cant copy when adding below this code
        // dummy.style.display = 'none'
        document.body.appendChild(dummy);
        //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
        dummy.value = '{{ $page_owner->wallet_address }}';
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);       

        $('#qr-box').attr('title', 'Copied!').tooltip('dispose');
        $('#qr-box').tooltip('show');
        }
        // ---------------------------     

    </script>

</body>
@endsection