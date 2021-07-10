@guest
    <div style="color:{{ $page_owner->username_color }}">
@endguest
    
@auth
    @if (Auth::user()->id === $page_owner->id)
        <div id="username" style="color:{{ $page_owner->username_color }};cursor:pointer;" data-toggle="tooltip" data-placement="top" title="change color">
    @else
        <div style="color:{{ $page_owner->username_color }}">
    @endif
@endauth

        <p class="username ml-2">{{ $page_owner->username }}</p>
        <span class="location ml-2">
        @if($location = $page_owner->location)
        {{ $location }}
        @else
        Location N/A
        @endif
        </span>
    </div>