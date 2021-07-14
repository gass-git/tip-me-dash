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
        
    @php
        $tips = App\Tip::where('recipient_id',$page_owner->id)         // Page owner tips
                    ->where('status','confirmed')
                    ->orderBy('id','DESC')
                    ->paginate(5);
    
        $supporters = App\Tip::where('recipient_id',$page_owner->id)   // Amount of people that have supported the page owner with tips
                            ->where('status','confirmed')
                            ->distinct('sender_ip')
                            ->count();
            
        $people_tipped = App\Tip::where('sender_id',$page_owner->id)   // Amount of people tipped by a page owner
                            ->where('status','confirmed')
                            ->distinct('recipient_id')
                            ->count();
                
        $tips_sent = App\Tip::where('sender_id',$page_owner->id)       // Five most recent tips sent to different people   
                    ->where('status','confirmed')
                    ->distinct('recipient_id')
                    ->latest()->take(5)->get();

        $biggest_tip = App\Tip::where('recipient_id',$page_owner->id)  // Biggest tip received by the page owner
                    ->where('status','confirmed')
                    ->orderBy('usd_equivalent','DESC')
                    ->first();
    @endphp 
    
    @include('layouts/components/cover')

    <div class="user-page container">
        
        <!-- END of main row -->
        <div class="row">
            
            <!-- Left column -->
            <div class="col-md-3 p-0">
                @include('layouts/components/profile_box')
            </div>
            <!----------------->

            <!-- Right column -->
            <div class="right-col col-md-9">

                <div class="d-flex">

                    <!-- Responsive avatar -->
                    <div><img id="mobile-avatar" class="mr-2" src="{{ $page_owner->avatar_url }}"></div>

                    @include('layouts/components/username_and_location')
                    

                </div>    

                @include('layouts/components/tip_form')

                <!-- If user has not received tips show this -->
                @if($supporters === 0)
                    <center style="color:grey; word-spacing:1px;">
                        <b style="text-transform:capitalize;color:var(--light-deep-blue);">{{ $page_owner->username }}</b> 
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

                    @include('layouts/components/tip_box')
                
                @endforeach
                <!--------------------------------------------> 

                <div class="mt-5">{{ $tips->links() }}</div>

            </div> 
            <!-- END of right column -->

        </div>
        <!-- END of main row -->

    </div>
    <!-- END of user-page container -->

</section>
</body>

