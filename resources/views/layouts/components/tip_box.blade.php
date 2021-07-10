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