@extends('layouts/app')
@section('content')
@include('layouts/components/navbar_two')
@include('layouts/user_page_body')


    <!-- Start of modal QR -->
    <div class="modal fade" id="modal_QR" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    
                    <div id="title">
                        <span>Scan to send <b>{{ $page_owner->username }}</b> {{ $dash_toSend }}</span> 
                        <img height="25" src="{{ asset('images/white-dash-logo.png') }}"> 
                    </div> 
                
                    <!-------- QR ------->
                    <div id="qr_box" 
                         onclick="copyToClipboard('{{ $page_owner->wallet_address }}')"  
                         data-toggle="tooltip" 
                         data-placement="right"  
                         title="ⓘ If you are using a desktop wallet click here to copy the address and send {{ $page_owner->username }} the exact amount.">
                        
                        <img src="data:image/png;base64, {{ base64_encode(QrCode::color(1, 32, 96)->
                                  format('png')->
                                  errorCorrection('H')->
                                  size(350)->
                                  merge('images/qr-logo.png',0.22,true)->
                                  generate($QRstring)) }}">                 

                        <script>
                            function copyToClipboard(address) {
                                var dummy = document.createElement("textarea");  
                                document.body.appendChild(dummy);
                                dummy.value = address;
                                dummy.select();
                                document.execCommand("copy");
                                document.body.removeChild(dummy);

                                var text = 'ⓘ If you are using a desktop wallet click here to copy the address and send {{ $page_owner->username }} the exact amount.';
                                let box = $('#qr_box');
                                box.attr('data-original-title','Address copied!');
                                box.tooltip('show');
                                setTimeout(function(){ box.tooltip('hide') }, 1000);
                                setTimeout(function(){ box.attr('data-original-title', text) }, 1200);
                            }
                        </script>
                    </div>
                    <!------------------>        

                    <script>
                        $(function () {
                            $('[data-toggle="tooltip"]').tooltip()
                        })
                    </script>

                    <div class="note p-3">
                        This window will close once the tip has been found on the blockchain. Do not
                        close or reload the page until then, doing so will prevent registering the tip.
                    </div> 
                    
                    <!-- Time remaining -->
                    <div class="mt-1" style="font-size:20px;">
                        <i id="clock" class="far fa-clock mr-2"></i>~ <b id="timer"></b> min left
                    </div>
                    <!-------------------->

                    <!-- Progress bar -->
                    <div class="progress">
                        <div id="bar" 
                             class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 100%" 
                             aria-valuenow="180" 
                             aria-valuemin="0" 
                             aria-valuemax="180">
                        </div>
                    </div>
                    <!------------------------>

                </div>
            </div>
        </div>
    </div>
    <!-- END of modal QR --> 

<script>
$(window).on('load',(e)=>{e.preventDefault();$('#modal_QR').modal('show');var id = @json($tip_id);var requests_delay = 20000;var requests_interval = 10000;var seconds = 180;var min = (seconds/60).toFixed(1);var timer_interval = setInterval(timer, 1000);var usd_amount = @json($usd_amount);var dash_toSend = @json($dash_toSend);var dash_usd = @json($dash_usd);const txs = 'https://api.chainrider.io/v1/dash/main/txs?address={{ $page_owner->wallet_address }}&token={{ env('CHAIN_RIDER_TOKEN') }}';function timer(){seconds--,min=(seconds/60).toFixed(1);var t="width:"+seconds/180*100+"%;";$("#bar").attr("style",t),min>0&&min<.6?($("#timer").html("0.5"),$("#clock").toggleClass("toggle-yellow")):min>=.6&&min<1.1?$("#timer").html("1.0"):min>=1.1&&min<1.7?$("#timer").html("1.5"):min>=1.7&&min<2.2?$("#timer").html("2.0"):min>=2.2&&min<2.6&&$("#timer").html("2.5")}$("#timer").html(min),setTimeout(function(){var t=setInterval(e,requests_interval);function e(){fetch(txs).then(t=>t.json()).then(function(e){for(var n=e.txs.length,o=0;o<n;o++)for(var r=e.txs[o].vout.length,i=0;i<r;i++){if(e.txs[o].vout[i].value==dash_toSend){var s=e.txs[o].txid;$.ajax({method:"POST",url:"{{route('confirm_tip')}}",async:!1,data:{_token:"{{ csrf_token() }}",tip_id:id,transaction_id:s},success:function(){clearInterval(t),window.location.href="/{{ $page_owner->username }}"},error:function(){console.log("AJAX error")}})}}}),seconds<=5&&$.ajax({type:"post",async:!1,url:"{{ route('unconfirmed') }}",data:{_token:"{{ csrf_token() }}",tip_id:id},success:function(){clearInterval(t),window.location.href="/{{ $page_owner->username }}"},error:function(){console.log("AJAX error")}})}e()},requests_delay);})
</script>

@endsection