@extends('layouts.app')
@section('content')
<body>
    <section class="community-activity container mt-5 mx-auto" style="max-width:800px;">
    
        <!-- loop -->
        @foreach($community_activity as $event)

            @php
                $from = App\User::where('id',$event->from_user_id)->first();
                $to = App\User::where('id',$event->to_user_id)->first();
            @endphp

            <!-- does $from have a username? -->
            @if($from->username)

                <!-- condition to see if it's a msg or a boost -->
                @if($event->is_msg)
                    <div class="card mb-3 mx-auto" style="width: 80%">
                        <div class="card-header">
                            <i class="fas fa-pencil-alt mr-2" style="color:#0268a8;"></i> 
                            <a class="visitor-username" href="{{ route('user_page',$from->username) }}">
                                {{ $from->username }}
                            </a> 
                            wrote a message to 
                            <a class="visitor-username" href="{{ route('user_page',$to->username) }}">
                            {{ $to->username }}
                            </a>
                        </div>
                        <div class="card-body text-info">
                            <p class="card-text">{{ $event->msg }}</p>
                        </div>
                    </div>
                @else

                <div class="alert alert-light mx-auto border" role="alert" style="width:80%;">
                    <i class="fas fa-star mr-2" style="color:#CCCC00;"></i> 
                    <a class="visitor-username" href="{{ route('user_page',$from->username) }}">
                        {{ $from->username }}
                    </a>
                    boosted 
                    <a class="visitor-username" href="{{ route('user_page',$to->username) }}">
                        {{ $to->username }} 
                    </a>
                    reputation.
                </div>
                    
                @endif

            @else <!-- $from does not have a username -->

                <!-- condition to see if it's a msg or a boost -->
                @if($event->is_msg)
                    <div class="card mb-3 mx-auto" style="width: 80%">
                        <div class="card-header">
                            <i class="fas fa-pencil-alt mr-2" style="color:#0268a8;"></i> 
                            <span class="visitor-username">
                                Someone
                            </span> 
                            wrote a message to 
                            <a class="visitor-username" href="{{ route('user_page',$to->username) }}">
                                {{ $to->username }}
                            </a>
                        </div>
                        <div class="card-body text-info">
                            <p class="card-text">{{ $event->msg }}</p>
                        </div>
                    </div>
                @else

                <div class="alert alert-light mx-auto border" role="alert" style="width:80%;">
                    <i class="fas fa-star mr-2" style="color:#CCCC00;"></i> <span style="color:#008de4">
                        Someone
                    </span>
                    boosted 
                    <a class="visitor-username" href="{{ route('user_page',$to->username) }}">
                        {{ $to->username }} 
                    </a>
                    reputation.
                </div>
                    
                @endif
            @endif
        @endforeach
        <!-- end of loop -->

        <!-- pagination -->
        <div>{{ $community_activity->links() }}</div>
        <!---------------->

    </section>
</body>
@endsection