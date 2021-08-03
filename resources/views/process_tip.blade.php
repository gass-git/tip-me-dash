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
    
        $(window).on('load', (e) => { 
            e.preventDefault();
            $('#modal_QR').modal('show'); 
            const txs_api = "https://api.chainrider.io/v1/dash/main/txs?address={{ $page_owner->wallet_address }}&token={{ env('CHAIN_RIDER_TOKEN') }}";
            var id = @json($tip_id);                             
            var requests_delay = 20000;                       
            var requests_interval = 10000;                       
            var seconds = 180;                                   
            var min = (seconds/60).toFixed(1);                    
            var timer_interval = setInterval(timer, 1000);                                    
            var usd_amount = @json($usd_amount);                  
            var dash_toSend = @json($dash_toSend);                
            var dash_usd = @json($dash_usd);                      
            $('#timer').html(min);
                     
            setTimeout(function(){
                var interval = setInterval(process, requests_interval);               

                process(); 
                function process(){
                    fetch(txs_api).then(response => response.json()).then(function(data) {
                            
                        var array_length_one = data.txs.length;
                        for(var A = 0; A < array_length_one; A++){
                            
                            var array_length_two = data.txs[A].vout.length;
                            
                            for(var B = 0; B < array_length_two; B++){
                                var amount = data.txs[A].vout[B].value;
                                
                                if(amount == dash_toSend){
                                    /* Get transaction id */
                                    var txid = data.txs[A].txid;
                                    $.ajax({
                                        method: 'POST',
                                        url: "{{route('confirm_tip')}}",
                                        async: false,
                                        data: {
                                            _token: '{{ csrf_token() }}', tip_id: id, transaction_id: txid
                                        },
                                        success:function(){
                                            clearInterval(interval);
                                            window.location.href = '/{{ $page_owner->username }}';
                                        },
                                        error:function(){console.log('AJAX error')}
                                    })
                                }
                            }
                        }
                    })
                
                    if(seconds <= 5){ 
                        $.ajax({
                            type:'post',
                            async: false,
                            url:"{{ route('unconfirmed') }}",
                            data:{
                                _token: "{{ csrf_token() }}", tip_id: id
                            },
                            success:function(){
                                clearInterval(interval);
                                window.location.href = '/{{ $page_owner->username }}';
                            },
                            error:function(){console.log('AJAX error')}
                        })
                    }        
                } 
            }, requests_delay) 
           
            function timer(){
                seconds--;
                min = (seconds/60).toFixed(1);
                var percentage = ((seconds/180)*100);
                var width = 'width:' + percentage + '%;'
                $('#bar').attr('style',width);
                if(min > 0 && min < 0.6){$('#timer').html('0.5');  $("#clock").toggleClass('toggle-yellow')}
                else if(min >= 0.6 && min < 1.1){$('#timer').html('1.0')}
                else if(min >= 1.1 && min < 1.7){$('#timer').html('1.5')}
                else if(min >= 1.7 && min < 2.2){$('#timer').html('2.0')}
                else if(min >= 2.2 && min < 2.6){$('#timer').html('2.5')}        
            } 
        })
</script>
@endsection