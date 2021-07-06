@extends('layouts/app')
@section('content')
@include('layouts/user_page_body')

<script>

    // -------- Change cover image ------
    $("#input").change(function(){
        readURL(this);
    });

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
    // --------------------------------


    // ------ Toggle bootstrap tooltip ---------
    $('[data-toggle="tooltip"]').tooltip();   
    // ----------------------------------------- 


    // ----- Show msg ----------------
    $('.show-msg').click(function(){
        id = $(this).attr('id');
        $('#tip-msg-'+id).toggle().css('display');
        $('#tip-msg-icon-'+id).toggleClass('fa-envelope-open');
        if($('#tip-msg-icon-'+id).css('padding-bottom') == '0px'){
            $('#tip-msg-icon-'+id).css('padding-bottom','8px');
        }else{
            $('#tip-msg-icon-'+id).css('padding-bottom','0px');
        }
    });
    // -------------------------------

    // ------- global variables ------------
    var csrf_token = '{{ csrf_token() }}';
    var grey = 'rgb(175, 175, 175)';
    var dark_yellow = 'rgb(238, 204, 13)';
    var beer_yellow = '#FFA900';
    var dash_blue = '#008de4';
    var route = 'praise';   
    // -------------------------------------

    // --------- like btn script -------------------------------
    $('.like').on('click', function(event) {   
        
        var tip_id = $(this).parent().attr('id');               // get the id of the post
        var element = $('#like-' + tip_id);                     // get the element clicked

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
            url: route,
            data:{ id: tip_id, _token: csrf_token, praise:"like"}
        });

    });
    // ---------------------------------------------------------

    // ------------- loves it btn script -----------------------
    $('.love').on('click', function(event) {
                    
        var tip_id = $(this).parent().attr('id');            // get the id of the post 
        var element = $('#love-' + tip_id);                     // get the element clicked
        
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
            url: route,
            data:{ id: tip_id, _token: csrf_token, praise:"love"}
        });

    });
    // ------------------------------------------------------

    // ------------- loves it btn script -----------------------
    $('.brilliant').on('click', function(event) {
                    
        var tip_id = $(this).parent().attr('id');            // get the id of the post 
        var element = $('#brilliant-' + tip_id);                     // get the element clicked
        
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
            url: route,
            data:{ id: tip_id, _token: csrf_token, praise:"brilliant"}
        });

    });
    // ------------------------------------------------------

    // ------------- Cheers btn script -----------------------
    $('.cheers').on('click', function(event) {
                    
        var tip_id = $(this).parent().attr('id');            // get the id of the post 
        var element = $('#cheers-' + tip_id);                     // get the element clicked
        
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
            url: route,
            data:{ id: tip_id, _token: csrf_token, praise:"cheers"}
        });

    });
    // ------------------------------------------------------

</script>    


@endsection