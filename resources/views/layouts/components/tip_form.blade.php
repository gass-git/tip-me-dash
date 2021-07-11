<form class="main-form" 
        action="{{ url('process_tip/' . $page_owner->username) }}" 
        method="post" 
        enctype="multipart/form-data" >
        @csrf 

    <div class="border form-wrapper">

        <!-- Start of form box 1 -->
        <div class="box1 pb-0">
            <!-- name input -->
            @auth
                <input style="text-transform: capitalize;" 
                        name="name" 
                        type="text" 
                        value="{{ Auth::user()->username }}" 
                        class="form-control"
                        readonly/>
            @endauth

            @guest
                <input name="name" type="text" class="form-control" onfocus="this.placeholder =''" onblur="this.placeholder = 'Name'" placeholder="Name"  " value="{{ old('name') }}">
                @error('name')
                    <span style="color:red; font-size:13px;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </span>
                @enderror
            @endguest
            <!--- END of name input --->

            <!-- amount input -->
            <div class="input-group mb-0 p-0">
                @if(old('amount_entered'))
                    <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" value="{{ old('amount_entered') }}" />
                @else
                    <input name="amount_entered" type="number" style="color:#4a569c;" step=".01" class="form-control" value="5.00" />
                @endif
            </div>
            <!--- END of amount input ---->

            <center><div style="font-size: 11px;color:grey;">USD</center>
        
        </div>
        <!-- END of form box 1 -->

        <!-- Message textarea -->
        <div class="box2">
            <textarea name="msg" class="custom-textarea" id="msg" placeholder="Optional message">{{ old('msg') }}</textarea>
            @error('msg')
                <span style="color:red; font-size:13px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </span>
            @enderror 
        </div>
        <!-- END of message textarea -->

        <!-- Lock icon --->
        <div id="lock-style" style="position: absolute;display:flex;right:125px;top:183px; cursor:pointer;z-index:3">
            <i id="lock" class="fas fa-lock-open" title="private message option"></i>
            <input id="lock-checkbox" name="lock" type="checkbox" style="display:none;" />
        </div>
        <!---------------->

        <!-- submit tip button -->
        <div class="box3">
            <button class="tip-btn" type="submit">Tip</button>
        </div> 
        <!----------------------->

    </div>
</form>