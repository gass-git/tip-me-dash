<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Receive tips instantly from followers directly to your pocket, without third parties involved. Give fans a new way to show appreciation for your work.">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/pin-32.png') }}">

        <!-- cookies -->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
        <!-- app js -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <!-- popper js -->
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <!-- bootstrap js -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <!-- emoji js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>
        <!-- sweet alert 2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <!-- emoji css  -->    
        <link rel="stylesheet" href="{{asset('css/emojionearea.min.css')}}">
        <!-- bootstrap css -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <!-- main css -->
        <link rel="stylesheet" href="{{ asset('css/main.css?58') }}">
        <!-- font awesome css -->
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <audio id="lock-sound" src="sounds/lock.mp3"></audio>

    </head>

    @yield('content')
    @include('sweetalert::alert')
    
</html>
