@extends('layouts.app')

@section('content')
<body>

    <div class="login-form modal-dialog text-center" style="background-color:transparent;">
        <div class="col-sm-8 main-section">
        @if(session()->has('message'))
            <div class="alert alert-success mb-5">
            {{ session()->get('message') }}
            </div>
        @endif
            <div class="border modal-content">
                <div class="col-12 user-img">
                    <img src="{{ asset('images/default-profile-pic1.jpg') }}">
                </div>
                
                <!-- start of login form -->
                <form class="col-12" action="{{ route('login') }}" method="POST">
                @csrf 
                    <div class="form-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" onblur="this.placeholder='Email'" onfocus="this.placeholder=''" placeholder="Email" autocomplete="email" required>
                        <span class="email-icon"><img src="{{ asset('images/email-icon.png') }}"></span>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror"  onblur="this.placeholder='Password'" onfocus="this.placeholder=''" placeholder="Password" autocomplete="current-password" required>
                        <span class="password-icon"><img src="{{ asset('images/lock-icon-2.png') }}"></span>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary login-btn mt-0"><i class="fas fa-sign-in-alt mr-2"></i> Log in</button>
                    <a role="button" class="btn btn-info google-btn" href="{{ url('login/google') }}"><i class="fab fa-google mr-2"></i>Log in with Google</a>
                </form>
                <!-- end of login form -->

                @if(Route::has('password.request'))
                <div class="col-12 forgot-password">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
                @endif
            </div>
        </div>
    </div> 

</body>
@endsection
