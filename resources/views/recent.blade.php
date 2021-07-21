@extends('layouts/app')
    @section('content')
        <div style="min-width: 440px">
            @include('layouts/components/navbar_one')
        </div>

        <div class="container mx-auto mt-5" id="recent" style="max-width:650px;min-width:440px;">

            @if($tips_30_days < 1)
            <center style="color:grey; word-spacing:1px;">
                <span style="font-weight:200">There has not been any tips in the last 30 days.</span>
            </center>
            @endif

            @foreach($recent_tips as $tip)

                    <div class="tip-box mb-4 mt-4 pt-2 pb-2 pl-0 pr-0">
                        <div class="row mb-0">
                            <div class="col-sm-10 mr-0">
                                <div class="d-flex mt-2">
                    
                                    <div class="tip-body">
                                        <p class="d-flex tip-title align-items-lg-center p-2">
                                            <i id="fa-tip-icon" class="fas fa-donate ml-0 mr-1" data-toggle="tooltip" data-placement="top" style="color:var(--dark-yellow);font-size:18px;" title="equivalent to ${{ $tip->usd_equivalent }} usd at the moment of transfer"></i>

                                            @php

                                                /** @abstract 
                                                * 
                                                * SCENARIOUS:
                                                * 
                                                * 1) Tip has sender_id and the tipper has not deleted his acc
                                                * 2) Tip has sender_id and the tipper has deleted his acc
                                                * 3) Tip has NO sender_id but has a tipper name
                                                * 4) Tip has no sender_id and no tipper name (Incognito)
                                                *
                                                */

                                                $recipient = App\User::where('id',$tip->recipient_id)->first();
                                                $registered_tipper = App\User::where('id', $tip->sender_id)->first();

                                                if($registered_tipper){
                                                    $tipper_location = $registered_tipper->location;
                                                    $tipper_username = $registered_tipper->username;
                                                }

                                            @endphp


                                            @if($tip->sender_id AND $registered_tipper)
                    
                                                <a href="/{{ $tipper_username }}" style="text-decoration: none!important;" title="Registered user">
                                                    <span class="ml-1" style="color:var(--light-deep-blue);text-transform:capitalize;">
                                                        {{ $tipper_username }}
                                                    </span>
                                                </a>    

                                            @elseif($tip->sender_id AND $registered_tipper === null)
                                            
                                                    <span class="ml-1" style="color:var(--deep-blue-1);text-transform:capitalize;" title="this user deleted his account">
                                                        {{ $tip->sent_by }}
                                                    </span>
                    
                                            @elseif($tip->sent_by)
                    
                                                <span class="ml-1" style="color:var(--deep-blue-1);text-transform:capitalize;">
                                                    {{ $tip->sent_by }}
                                                </span>
                    
                                            @else
                    
                                                <span class="ml-1" style="color:var(--deep-blue-1);text-transform:capitalize;">
                                                    Incognito
                                                </span>
                    
                                            @endif
                    
                                            <span class="ml-1 mr-1" style="color:#646464">sent </span>
                                            <span>{!! number_format((float)($tip->dash_amount), 5) !!} ᕭ </span>
                                            <span class="ml-1 mr-1" style="color:#646464">to</span>
                                            <a href="/{{ $recipient->username }}" style="text-transform: capitalize;text-decoration:none!important;color:var(--light-deep-blue);">
                                                {{ $recipient->username }}
                                            </a>

                                            <!-- Envelope icon -->
                                            @if($tip->message AND $tip->private_msg === 'yes')
                    
                                                @guest
                                                    
                                                    <span class="show-msg" style="color:rgb(85, 85, 85);cursor:not-allowed;">
                                                        <i class="ml-2 fas fa-envelope" title="private message" style="padding-bottom:0px;"></i>
                                                    </span>
                    
                                                @endguest
                                                
                                                @auth
                                                    
                                                    @if(Auth::user()->id === $tip->recipient_id)
                    
                                                        <span id="{{ $tip->id }}" class="show-msg" style="color:rgb(85, 85, 85)">
                                                            <i id="tip-msg-icon-{{ $tip->id }}" class="ml-2 fas fa-envelope" title="private message" style="padding-bottom:0px;"></i>
                                                        </span>
                    
                                                    @else
                    
                                                        <span class="show-msg" style="color:rgb(85, 85, 85);cursor:not-allowed;">
                                                            <i class="ml-2 fas fa-envelope" title="private message" style="padding-bottom:0px;"></i>
                                                        </span>
                    
                                                    @endif
                    
                                                @endauth
                    
                                            @elseif($tip->message AND $tip->private_msg === null)
                                            
                                                <span id="{{ $tip->id }}" class="show-msg">
                                                    <i id="tip-msg-icon-{{ $tip->id }}" class="ml-2 fas fa-envelope" title="message" style="padding-bottom:0px;"></i>
                                                </span>
                    
                                            @endif
                                            <!-- END of envelope icon -->
                    
                                        </p><!-- END of tip tittle -->
                    
                                        <!---- Message content section ------>
                                        @if($tip->message AND $tip->private_msg === 'yes')
                    
                                            @auth
                    
                                                @if(Auth::user()->id === $tip->recipient_id)
                    
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
                                        <p class="mt-4 mb-0" style="font-size:11px;color:rgb(156, 156, 156);">
                                            
                                            @php
                                                $praise = App\Tip::where('id',$tip->id)->first()->praise;
                                            @endphp
                                                
                                                <span style="font-size:13px"> ― </span> 
                                                <span class="mr-1">Received on {{ \Carbon\Carbon::parse($tip->created_at)->isoFormat('MMM Do YYYY')}} - 
                                                    <span style="cursor:help" data-toggle="tooltip" data-placement="top" title="dash/usd exchange rate at the moment of transfer">DP ${{ $tip->dash_usd }}</span>
                                                </span>
                                            
                                                @if($praise === 'like')
                                                    ⁂<i class="fas fa-thumbs-up ml-2" style="color:var(--dash-blue);font-size:12px;"></i> Thanks! This is great
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

            <div class="d-flex justify-content-center mb-5">{{ $recent_tips->links() }}</div>

            <script>

            $('[data-toggle="tooltip"]').tooltip();   // Toggle bootstrap tooltip 

            /* ----- Show msg ------------------------------- */
            $('.show-msg').click(function(){
                var id = $(this).attr('id');
                var element = $('#tip-msg-icon-'+id);

                $('#tip-msg-'+id).toggle().css('display');

                if( element.attr('class') == 'ml-2 fas fa-envelope' ){
                    element.removeClass('fa-envelope');
                    element.addClass('fa-envelope-open');
                }else{
                    element.removeClass('fa-envelope-open');
                    element.addClass('fa-envelope');
                }

                if(element.css('padding-bottom') == '0px'){
                    element.css('padding-bottom','8px');
                }else{
                    element.css('padding-bottom','0px');
                }
            });
            /* ------------------------------------------------ */
            </script>

        </div>
    @endsection