@extends('layouts/app')
@section('content')

    @include('layouts/components/navbar_two')
    @include('layouts/user_page_body')
    
    <script>

        /* ------- Global variables ----------------- */
        var csrf_token = '{{ csrf_token() }}';
        var grey = 'rgb(190, 190, 190)';
        var dark_yellow = 'rgb(238, 204, 13)';
        var beer_yellow = '#FFA900';
        var dash_blue = '#008de4';
        var route_one = 'praise';   
        /* ------------------------------------------- */

        /* -------- Emoji plugin --------------------- */
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
                travel_places: false
            }
        });
        /* ------------------------------------------- */

        /* -------- Change cover image --------------- */
        $("#input").change(function(){ readURL(this) });

        function readURL(input){

            if (input.files && input.files[0]) {
                
                var reader = new FileReader();

                reader.onload = function (e) {
                    console.log(e.target.result)
                    $('.header-img').css('background-image','url('+e.target.result+')');
                    $('#input').css('display','none');
                    $('#cancel-btn').removeAttr('style');
                    $('#save-btn').removeAttr('style');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        /* ------------------------------------------ */

        /* ------- Change username color ------------ */
        $('#username').click(function(){

            if( $(this).css('color') == 'rgba(17, 25, 33, 0.8)' ){ $(this).css('color','white') }
            else{ $(this).css('color','rgba(17, 25, 33, 0.8)') }

            var rgba = $(this).css('color');

            $.ajax({
                method: 'post',
                url: 'username_color',
                data: {_token: csrf_token, color: rgba}
            });
        });
        /* ----------------------------------------- */

        /** @abstract
        * 
        * RANDOM COVER IMAGE
        * 
        * Important: gif covers enumaration must start after the last jpg cover.
        * 
        * Reminder: when adding or removing covers, the variables 'total_jpgs' and
        * 'total_gifs' should be updated. 
        * 
        */
        const memory = [];            
        var click_number = 0;

        $('.fa-random').click(function(){

            $('#loader').css('display','none');

            setTimeout(function(){$('#loader').css('display','block') }, 100);

            let repeated = true;
            var total_jpgs = 29;
            var total_gifs = 3;
            var total_covers = total_jpgs + total_gifs;
            let format = '.jpg';
            var rand = null;

            // Reset array and click_number once all covers are shown    
            if(click_number >= total_covers){
                click_number = 0;
                memory.length = 0;
            }

            while(repeated){
                
                rand = Math.floor((Math.random() * total_covers) + 1);

                // Has this random cover been shown before?
                for(let i = 0; i <= click_number; i++){
                    
                    if(memory[i] == rand){ 
                        repeated = true;
                        i = click_number;
                    }else{
                        repeated = false;   // If it's not been shown before escape the while
                    }

                }

            }
            
            memory[click_number] = rand; // Add this random number to memory array to avoid showing it again

            click_number++;

            JSON.stringify(memory)
            console.log(memory)

            if(rand > total_jpgs){  format = '.gif' }

            var src = "http://tipmedash.com/images/covers/"+rand+format;
            var img_src = "url(http://tipmedash.com/images/covers/"+rand+format+")";

            $('.header-img').css('background-image',img_src);
            $('#rand-cover-input').attr('value',src);
            $('#cancel-btn').removeAttr('style');
            $('#save-btn').removeAttr('style');
        });
        /* ----------------------------------------- */

        /* ------- Set the header_img_url to null ------- */
        $('.fa-trash-alt').click(function(){
            $.ajax({   
                method: 'post',
                url: 'delete_cover',
                data: { _token: csrf_token },
                success:function(){ location.reload() }
            });
        });
        /* ---------------------------------------------- */

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

        /* ------- Lock icon ------------------------------ */
        $('#lock').click(function(e){ 

            $('#lock-checkbox').click();

            if( $(this).attr('class') == 'fas fa-lock-open' ){
                $(this).removeClass('fa-lock-open');
                $(this).addClass('fa-lock');
                $('#lock-style').css('right','129px');
                document.getElementById('lock-sound').play();
            }else{
                $(this).removeClass('fa-lock');
                $(this).addClass('fa-lock-open');
                $('#lock-style').css('right','125px');
                document.getElementById('lock-sound').play();
            }
        });
        /* ------------------------------------------------ */

        /* --------- like btn script ---------------------- */
        $('.like').on('click', function(event) {   
            
            var tip_id = $(this).parent().attr('id');         // get the id of the post
            var element = $('#like-' + tip_id);               // get the element clicked

            // change element color and title on click
            if(element.css('color') === grey){
                element.css('color',dash_blue);
                element.attr('title', 'Unlike this?');
            }else{
                element.css('color',grey);
                element.attr('title', 'Like it');
            }
            
            $('#love-' + tip_id).css('color',grey);
            $('#love-' + tip_id).attr('title','Love it');
            $('#brilliant-' + tip_id).css('color',grey);
            $('#brilliant-' + tip_id).attr('title',"Brilliant");
            $('#cheers-' + tip_id).css('color',grey);
            $('#cheers-' + tip_id).attr('title','Cheers!');
            
            $.ajax({
                method:'post',
                url: route_one,
                data:{ id: tip_id, _token: csrf_token, praise:"like"}
            });
        });
        /* --------------------------------------------------- */

        /* ------------- loves it btn script ----------------- */
        $('.love').on('click', function(event) {
                        
            var tip_id = $(this).parent().attr('id');          // get the id of the post 
            var element = $('#love-' + tip_id);                // get the element clicked
            
            // change element color and title on click
            if(element.css('color') === grey){
                element.css('color','red');
                element.attr('title', "Don't love this anymore?");
            }else{
                element.css('color',grey);
                element.attr('title', 'Love it');
            }

            $('#like-' + tip_id).css('color',grey);
            $('#like-' + tip_id).attr('title','Like it');
            $('#brilliant-' + tip_id).css('color',grey);
            $('#brilliant-' + tip_id).attr('title',"Brilliant");
            $('#cheers-' + tip_id).css('color',grey);
            $('#cheers-' + tip_id).attr('title','Cheers!');
            
            $.ajax({
                method:'post',
                url: route_one,
                data:{ id: tip_id, _token: csrf_token, praise:"love"}
            });

        });
        /* --------------------------------------------------- */

        /* ------------- Brilliant btn script ---------------- */
        $('.brilliant').on('click', function(event) {
                        
            var tip_id = $(this).parent().attr('id');            // get the id of the post 
            var element = $('#brilliant-' + tip_id);             // get the element clicked
            
            // change element color and title on click
            if(element.css('color') === grey){
                element.css('color',dark_yellow);
                element.attr('title', "Don't think is brilliant anymore?");
            }else{
                element.css('color',grey);
                element.attr('title', "It's brilliant!");
            }

            $('#like-' + tip_id).css('color',grey);
            $('#like-' + tip_id).children().eq(0).attr('title','Like it');
            $('#love-' + tip_id).css('color',grey);
            $('#love-' + tip_id).attr('title','Love it');
            $('#cheers-' + tip_id).css('color',grey);
            $('#cheers-' + tip_id).attr('title','Cheers!');

            $.ajax({
                method:'post',
                url: route_one,
                data:{ id: tip_id, _token: csrf_token, praise:"brilliant"}
            });
        });
        /* ---------------------------------------------------- */

        /* ------------- Cheers btn script -------------------- */
        $('.cheers').on('click', function(event) {
                        
            var tip_id = $(this).parent().attr('id');            // get the id of the post 
            var element = $('#cheers-' + tip_id);                // get the element clicked
            
            // change element color and title on click
            if(element.css('color') === grey){
                element.css('color',beer_yellow);
                element.attr('title', "No cheers now?");
            }else{
                element.css('color',grey);
                element.attr('title', "Cheers!");
            }

            $('#like-' + tip_id).css('color',grey);
            $('#like-' + tip_id).attr('title','Like it');
            $('#love-' + tip_id).css('color',grey);
            $('#love-' + tip_id).attr('title','Love it');
            $('#brilliant-' + tip_id).css('color',grey);
            $('#brilliant-' + tip_id).attr('title','brilliant');

            $.ajax({
                method:'post',
                url: route_one,
                data:{ id: tip_id, _token: csrf_token, praise:"cheers"}
            });
        });
        /* ---------------------------------------------------- */

    </script>    
    
@endsection