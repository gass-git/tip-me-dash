@extends('layouts.app')
@section('content')
<body>
    <section class="newcomers-activity container mt-5 mx-auto" style="width:600px;">
    
        <div class="title-1">RECENTLY REGISTERED</div>

        @foreach($newcomers->take(100) as $newcomer)

            @if($username = $newcomer->username)
                <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}" >
                    <img id="avatar" src="{{ $newcomer->avatar_url }}" style="width:25px;height:25px;border-radius:50%;"></img>
                    <span class="ml-2" style="text-transform: capitalize; color:#008de4; font-weight:600;">{{ $username }}</span>
                    <span class="float-right">Joined in {{ date('F d,Y', strtotime($newcomer->created_at)) }}</span>
                </a>
            @endif

        @endforeach

    </section>
</body>
@endsection