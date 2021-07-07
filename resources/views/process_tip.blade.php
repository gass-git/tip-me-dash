@extends('layouts/app')
@section('content')
@include('layouts/navbar_two')
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
                    <div id="qr_box">
                        
                        <img 
                            data-toggle="tooltip" 
                            data-placement="right" 
                            title="Address: {{$page_owner->wallet_address}}"
                            src="data:image/png;base64, {{ base64_encode(QrCode::color(1, 32, 96)->
                                  format('png')->
                                  errorCorrection('H')->
                                  size(350)->
                                  merge('images/qr-logo.png',0.22,true)->
                                  generate($QRstring)) }}">

                    </div>
                    <!------------------>        

                    <script>
                        $(function () {
                            $('[data-toggle="tooltip"]').tooltip()
                        })
                    </script>

                    <div class="note p-3">
                        <b>Note:</b> this window will close once the tip has been found on the blockchain. Do not
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

            $('#modal_QR').modal('show'); // Show the modal as soon as the page loads

            const token = 'Dp6I6sXtcnpYiKZtvr5RlDw3WsBW8GQS';
            const txs_api = 'https://api.chainrider.io/v1/dash/main/txs?address={{ $page_owner->wallet_address }}&token=' + token;
            
            var id = @json($tip_id);                              // Tip id
            var requests_delay = 20000;                           // Timout before sending API requests (ms)
            var requests_interval = 10000;                        // Interval between API requests (ms)
            var seconds = 180;                                    // Time that the user has to send the tip  
            var min = (seconds/60).toFixed(1);                    // Convert seconds to minutes           
            var timer_interval = setInterval(timer, 1000);        // Timer interval                                            
            var usd_amount = @json($usd_amount);                  // USD amount entered
            var dash_toSend = @json($dash_toSend);                // DASH equivalent to send to recipient
            var dash_usd = @json($dash_usd);                      // DASH exchange rate in USD

            $('#timer').html(min); // Add the starting time inmidiately after showing modal

            /**@abstract
             * 
             * The reason this Timeout is set is to use as minimum of requests
             * as possible. There is a delay in between the QR code been showed and
             * the user sending the amount.
             * 
             */             
            
            setTimeout(function(){

                var interval = setInterval(process, requests_interval);       // Set API requests interval                        

                /**@abstract
                 * 
                 * Check all amounts received and sent by the address
                 * in the last 10 transactions.
                 * 
                 * Run process() only one time inmidiately before the 
                 * interval starts. If this is not done, the function
                 * will have an starting delay equal to the time of the interval.
                 * 
                 */

                process(); 

                function process(){

                    fetch(txs_api).then(response => response.json()).then(function(data) {
                            
                        var array_length_one = data.txs.length;

                        for(var A = 0; A < array_length_one; A++){
                            
                            var array_length_two = data.txs[A].vout.length;
                            
                            for(var B = 0; B < array_length_two; B++){

                                var amount = data.txs[A].vout[B].value;

                                /**@abstract
                                 * 
                                 *  Check if the amount entered by the user equals any of the
                                 *  latest amounts received by the destination address and if
                                 *  so, change the status of the tip to CONFIRMED.
                                 * 
                                 *  Then: 
                                 *  1) Clear process() interval.
                                 *  2) Redirect back to the recipient user page.
                                 * 
                                 */

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
                    }) // End of dash_txs_api fetch 

                    /**@abstract
                     * 
                     * If the user has run out of time:
                     * 1) Change status to "Not validated".
                     * 2) Clear timer() and process() intervals.
                     * 3) Redirect back to the recipient user page.
                     * 
                     */
                
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

                } // END of process function 

            }, requests_delay) // END of setTimeOut function 

            /**@abstract
             * 
             * This function shows the user the amount of 
             * time available to scan the QR code and send the 
             * amount.
             * 
             */ 

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
            } // END of timer function

        }) // END of windows on load jQuery method

</script>
@endsection