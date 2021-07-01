@extends('layouts.app')

@section('content')
<body>

    <div class="register-form modal-dialog text-center">
        <div class="col-sm-8 main-section">
            <div class="modal-content">
                <div class="col-12 user-img">
                    <img src="{{ asset('images/avatar-default-2.jpg') }}">
                </div>
                
                <!-- start of register form -->
                <form class="col-12" method="POST" action="{{ route('register') }}">
                @csrf 
                    <div class="form-group" id="user-group">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" onblur="this.placeholder='Username'" onfocus="this.placeholder=''" placeholder="Username" value="{{ old('username') }}" required>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" onblur="this.placeholder='Email'" onfocus="this.placeholder=''" placeholder="Email" value="{{ old('email') }}" required>  
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" onblur="this.placeholder='Password'" onfocus="this.placeholder=''" placeholder="Password" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" onblur="this.placeholder='Repeat Password'" onfocus="this.placeholder=''" placeholder="Repeat Password" required>
                    </div>
                    
                    <button type="submit" class="register-btn btn btn-primary"><i class="fas fa-signature mr-2"></i>{{ __('Sign up') }}</button>
                    <a role="button" class="google-btn btn btn-danger" href="{{ url('login/google') }}"><i class="fab fa-google mr-2"></i>Sign up with Google</a>
                </form>
                <!-- end of register form -->

                
            </div>
        </div>
    </div> 

</body>
@endsection