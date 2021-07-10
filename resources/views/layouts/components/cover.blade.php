@guest

    @if ($page_owner->header_img_url)
        <div class="header-img w-100" style="background-image:url({{ $page_owner->header_img_url }})"></div>
    @else  
        <div class="header-img w-100" style="background-image:var(--blue-gradient-1)"></div>
    @endif  

@endguest

@auth

    @if(Auth::user()->id === $page_owner->id) <!-- The logged user is the page owner -->    

        <form class="main-form" action="{{ url('upload_header_img') }}" method="post" enctype="multipart/form-data">
        @csrf

            @if ($page_owner->header_img_url)
                <div class="header-img w-100 d-flex align-items-end pr-2"  style="background-image:url({{ $page_owner->header_img_url }});">
            @else  
                <div class="header-img w-100 d-flex align-items-end pr-2"  style="background-image:var(--blue-gradient-1)">
            @endif  

                <div id="loader" style="position:absolute;z-index:-1;left: 50%;top:125px;display:none;">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;color:rgb(0,0,0,0.2);">
                    </div>
                </div>

                <div class="cover-icons">
                    @if(Auth::user()->header_img_url)
                        <i class="far fa-trash-alt mr-1" style="cursor:pointer" title="delete cover"></i>
                    @endif
                    <i class="fas fa-random" style="cursor:pointer" title="random cover"></i>
                </div>

                @if(Auth::user()->header_img_url)
                    <div class="ml-auto mr-5" style="z-index:2">
                        <label class="btn btn-sm btn-outline-light mr-2" for="input" id="input-btn" type="file" name="input">Upload cover</label>
                        <input id="input"  type="file" name="image" style="display:none">
                        <input id="rand-cover-input" name="rand_cover" style="display: none" value="">
                        <button id="save-btn" class="btn btn-sm btn-success mr-2 mb-2" type="submit" style="display:none;">save</button>
                        <a id="cancel-btn" class="btn btn-sm btn-danger mr-2 mb-2" style="display:none;" href="/{{ $page_owner->username }}">Cancel</a>
                    </div>
                @else
                    <div class="ml-auto mr-4" style="z-index:2">
                        <label class="btn btn-sm btn-outline-light mr-2" for="input" id="input-btn" type="file" name="input">Upload cover</label>
                        <input id="input"  type="file" name="image" style="display:none">
                        <input id="rand-cover-input" name="rand_cover" style="display: none" value="">
                        <button id="save-btn" class="btn btn-sm btn-success mr-2 mb-2" type="submit" style="display:none;">save</button>
                        <a id="cancel-btn" class="btn btn-sm btn-danger mr-2 mb-2" style="display:none;" href="/{{ $page_owner->username }}">Cancel</a>
                    </div>
                @endif

            </div>
        </form>  

        @error('image')
            @php
                Alert::toast($message, 'info');
            @endphp
        @enderror
    
    @else <!-- The logged visitor is not the page owner -->    

        @if ($page_owner->header_img_url)
            <div class="header-img w-100" style="background-image:url({{ $page_owner->header_img_url }})">
            </div>
        @else  
            <div class="header-img w-100" style="background-image:var(--blue-gradient-1)">
            </div>
        @endif  

    @endif

@endauth