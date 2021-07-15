<section class="stats">
    <div class="container">
        <div class="row pt-2">

            <div class="col-md-6">
                
            <div class="title-1 mb-3" style="color:white;">RECENTLY REGISTERED</div>

                @foreach($newcomers->take(5) as $rookie)
                    @if($username = $rookie->username)
                        <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}" >
                            <img id="avatar" src="{{ $rookie->avatar_url }}"></img>
                            <span class="username ml-2">{{ $username }}</span>
                            <span id="join-date" class="float-right">{{ date('F d, Y', strtotime($rookie->created_at)) }}</span>
                        </a>
                    @endif
                @endforeach

            </div>
            <div class="col-md-6">
                
            <div class="title-1 mb-3" style="color:white;">LEADERBOARD</div>

                @foreach($ranks->take(5) as $rank)
                    @if($username = $rank->username)
                        <a class="list-group-item list-group-item-action" href="{{ route('user_page',$username) }}">
                            <img id="avatar" src="{{ $rank->avatar_url }}"></img>
                            <span class="username ml-2">{{ $username }}</span>
                            <span id="reputation-info" class="float-right">
                            <span id="score">⁂ {{ $rank->points }}</span>
                            </span>
                        </a>
                    @endif
                @endforeach

            </div>
            
        </div>
    </div>
</section>