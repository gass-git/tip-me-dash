@extends('layouts/app')
@section('content')
@include('layouts/user_page_body')

<script>

    // ------- global variables ------------
    var csrf_token = '{{ csrf_token() }}';
    var grey = 'rgb(211, 211, 211)';
    var dark_yellow = 'rgb(238, 204, 13)';
    var beer_yellow = '#FFA900';
    var dash_blue = '#008de4';
    var route = 'praise';   
    // -------------------------------------

    // --------- like btn script -------------------------------
    $('.like').on('click', function(event) {   
        
        var tip_id = $(this).parent().attr('id');                // get the id of the post
        var element = $('#' + tip_id).children().eq(0);          // get the element clicked
        
        // change element color and title on click
        if(element.css('color') === grey){
            element.css('color',dash_blue);
            element.attr('title', 'Unlike this?');
        }else{
            element.css('color',grey);
            element.attr('title', 'Like it');
        }
        
        // set the 2nd & 3rd children elements to default color and title
        $('#' + tip_id).children().eq(1).css('color',grey);
        $('#' + tip_id).children().eq(1).attr('title','Love it');
        $('#' + tip_id).children().eq(2).css('color',grey);
        $('#' + tip_id).children().eq(2).attr('title',"It's brilliant!");
        $('#' + tip_id).children().eq(3).css('color',grey);
        $('#' + tip_id).children().eq(3).attr('title','Cheers!');
        
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
        var element = $('#' + tip_id).children().eq(1);      // get the element clicked
        
        // change element color and title on click
        if(element.css('color') === grey){
            element.css('color','red');
            element.attr('title', "Don't love this anymore?");
        }else{
            element.css('color',grey);
            element.attr('title', 'Love it');
        }

        // set the 1st & 3rd children elements to default color
        $('#' + tip_id).children().eq(0).css('color',grey);
        $('#' + tip_id).children().eq(0).attr('title','Like it');
        $('#' + tip_id).children().eq(2).css('color',grey);
        $('#' + tip_id).children().eq(2).attr('title',"It's brilliant!");
        $('#' + tip_id).children().eq(3).css('color',grey);
        $('#' + tip_id).children().eq(3).attr('title','Cheers!');
        
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
        var element = $('#' + tip_id).children().eq(2);      // get the element clicked
        
        // change element color and title on click
        if(element.css('color') === grey){
            element.css('color',dark_yellow);
            element.attr('title', "Don't think is brilliant anymore?");
        }else{
            element.css('color',grey);
            element.attr('title', "It's brilliant!");
        }

         // set the 1st & 3rd children elements to default color and title
        $('#' + tip_id).children().eq(0).css('color',grey);
        $('#' + tip_id).children().eq(0).attr('title','Like it');
        $('#' + tip_id).children().eq(1).css('color',grey);
        $('#' + tip_id).children().eq(1).attr('title','Love it');
        $('#' + tip_id).children().eq(3).css('color',grey);
        $('#' + tip_id).children().eq(3).attr('title','Cheers!');

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
        var element = $('#' + tip_id).children().eq(3);      // get the element clicked
        
        // change element color and title on click
        if(element.css('color') === grey){
            element.css('color',beer_yellow);
            element.attr('title', "No cheers now?");
        }else{
            element.css('color',grey);
            element.attr('title', "Cheers!");
        }

            // set the 1st & 3rd children elements to default color and title
        $('#' + tip_id).children().eq(0).css('color',grey);
        $('#' + tip_id).children().eq(0).attr('title','Like it');
        $('#' + tip_id).children().eq(1).css('color',grey);
        $('#' + tip_id).children().eq(1).attr('title','Love it');
        $('#' + tip_id).children().eq(2).css('color',grey);
        $('#' + tip_id).children().eq(2).attr('title','It is brilliant!');

        $.ajax({
            method:'post',
            url: route,
            data:{ id: tip_id, _token: csrf_token, praise:"cheers"}
        });

    });
    // ------------------------------------------------------

</script>    


@endsection