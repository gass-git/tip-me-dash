@extends('layouts/app')
@section('content')
@include('layouts/navbar_two')
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">    
            <div class="col-md-7">

                @if(session('resent'))
                    <div class="alert alert-success" role="alert">
                                    A fresh verification link has been sent to your email address.
                    </div>
                @endif
                
                <div class="card">

                    <div class="card-body">  
                    
                        <h5 class="card-title"><i class="fas fa-at" style="color:#008de4;"></i> Please verify your email</h5>

                        <p class="card-text">
                            <form class="d-inline" style="color:#c4a699;" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                Before proceeding, please check your email for a verification link. If you did not receive the email
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
