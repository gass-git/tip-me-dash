@extends('layouts.app')
@section('content')
<body>

  <section class="settings-section container">

    <div class="wrapper border">
      <!-- acoordion start -->
      <div id="accordion">
        
        <!-- collapse one start -->
        <div class="card">

          <div class="accordion-card-header" id="headingOne">
            <h5 class="mb-0">
              <button class="accordion-header-btn btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Basic information
              </button>
            </h5>
          </div>

          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">

              <form class="main-form" action="{{ route('update_profile') }}" method="post" enctype="multipart/form-data">
              @csrf
                <!-- change avatar start-->
                <div class="change-avatar-box" id="change-avatar">
                  <label class="label">
                  <input type="file" name="avatar" id="avatar">
                    <figure class="avatar-figure">

                      <img src="{{ Auth::user()->avatar_url }}" class="current-avatar">
                      <img src="" class="preview-new-avatar">
                    
                      <figcaption class="personal-figcaption">
                        <img src="{{ asset('images/upload-icon.png') }}">
                      </figcaption>

                    </figure>
                  </label>
                </div>
                @error('avatar')
                <div class="mb-2" style="text-align:center!important;">  
                <span style="color:red; font-size:13px;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                  </span>
                </div>
                @enderror
                <!-- change avatar end -->

                <div class="row">
                  
                  <!-- username input start -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <div class="input-group mb-1">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon3">http://tipmedash.com/</span>
                        </div>
                        <input type="text" class="form-control" name="username" placeholder="{{ Auth::user()->username }}" aria-describedby="basic-addon3" />
                      </div>
                      @error('username')
                          <span class="mt-1" style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                        @enderror
                    </div>
                  </div>
                  <!-------------------------->

                  <!-- email input start -->
                  <div class="col-md-6">
                    <div class="form-group mb-1">
                      <label for="email">Email</label>
                      <input class="form-control" type="text" name="email" placeholder="{{ Auth::user()->email }}" />
                    </div>
                    @error('email')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                      @enderror
                  </div>
                  <!----------------------->

                </div>

                <!-- dash wallet address input -->
                <div class="form-group">
                  <label for="wallet_address">Dash wallet address </label>
                  <div class="input-group mb-1">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon1"><img class="dash-icon" width="20" src="{{ asset('images/blue-dash-icon.png') }}"></span>
                    </div>
                    <input class="form-control" type="text" name="wallet_address" value="{{ Auth::user()->wallet_address }}"/>
                  </div>   
                  @error('wallet_address')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror
                </div>
                <!------------------------------>

                <button type="submit" class="save-changes-btn shadow-none btn btn-outline-primary"><i class="far fa-save mr-2"></i>Save changes</button>
              </form> 

            </div>
          </div>
        </div>
        <!-- collapse one end -->

        <!-- collapse two start -->
        <div class="card" >
          
          <div class="accordion-card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="accordion-header-btn btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                About me
              </button>
            </h5>
          </div>

          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              
              <form class="main-form" action="{{ route('update_profile') }}" method="post" enctype="multipart/form-data" >
              @csrf     
              
                <div class="row">

                  <!-- passionate about input start -->
                  <div class="col-md-6">
                    <div class="form-group mb-1">
                      <label for="passionate_about">Passionate About</label>
                      <input class="form-control" type="text" name="passionate_about" placeholder="{{ Auth::user()->passionate_about }}">
                    </div>  
                    @error('passionate_about')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror        
                  </div>
                  <!------------------------->

                  <!-- location input start -->
                  <div class="col-md-6">
                    <div class="form-group mb-1">
                      <label for="location">Location</label>
                      <input class="form-control" type="text" name="location" placeholder="{{ Auth::user()->location }} "> 
                    </div>
                    @error('location')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror 
                  </div>
                  <!-------------------------->

                </div><!-- end of first row -->

                

                  

                <!-- website input start -->
                  
                  <div class="form-group mt-2 mb-1">
                    <label for="website">Website</label>
                    <input class="form-control" type="text" name="website" placeholder="{{ Auth::user()->website }} ">
                  </div>
                  @error('website')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror     
                  <!---------------------------------->

                <!-- about textarea -->
                <div class="form-group mt-2">
                  <label for="about">About</label>
                  <textarea class="form-control p-2" id="about-textarea" name="about" height="100">{{ Auth::user()->about }}</textarea>
                  
                  <!-- emoji plugin -->
                  <script>
                                $("#about-textarea").emojioneArea({
                                    shortnames: true,
                                    tones:false,
                                    search:false,
                                    pickerPosition: "bottom",
                                    placeholder: "Type something here",
                                    filters: {
                                    flags : false,
                                    animals_nature: false,
                                    activity: false,
                                    travel_places: false,
                                    symbols: false
                                    }
                                });
                            </script>
                            <!------------------>
                </div>
                <!-------------------->

                <button type="submit" class="save-changes-btn shadow-none btn btn-outline-primary"><i class="far fa-save mr-2"></i>Save changes</button>
              </form>
              <!-- end of second form -->

            </div>
          </div>
        </div>
        <!-- collapse two end -->

        <!-- collapse three start -->
        <div class="card">
          
          <div class="accordion-card-header" id="headingThree">
            <h5 class="mb-0">
              <button class="accordion-header-btn btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                For fun
              </button>
            </h5>
          </div>

          <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              
              <form class="main-form" action="{{ route('update_profile') }}" method="post" enctype="multipart/form-data" >
              @csrf     
                <div class="row">

                  <!-- favorite crypto input -->
                  <div class="col-md-6">
                    <div class="form-group mb-1">
                      <label for="favorite_crypto">Favorite Cryptocurrency</label>
                      <input class="form-control" type="text" name="favorite_crypto" placeholder="{{ Auth::user()->favorite_crypto }}">
                    </div>
                    @error('favorite_crypto')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror          
                  </div>
                  <!---------------------------->

                  <!-- desires superpower input -->
                  <div class="col-md-6">
                    <div class="form-group mb-1">
                      <label for="desired_superpower">Desired Superpower</label>
                      <input class="form-control" type="text" name="desired_superpower" placeholder="{{ Auth::user()->desired_superpower }}"> 
                    </div>
                    @error('desired_superpower')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror
                  </div>
                  <!------------------------------>

                </div><!-- end of third row -->

                <button type="submit" class="save-changes-btn shadow-none btn btn-outline-primary"><i class="far fa-save mr-2"></i>Save changes</button>
              </form>
              <!-- end of second form -->

            </div>
          </div>
        </div>
        <!-- collapse two end -->

        <!-- collapse four start -->
        <div class="card" id="cardFour">
          <div class="accordion-card-header" id="headingFour">
            <h5 class="mb-0">
              <button class="accordion-header-btn btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Change password
              </button>
            </h5>
          </div>

          <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body">
              
              <form class="main-form" action="{{ route('change_password') }}" method="post">
              @csrf
                <div class="row">
                  <!-- new password input -->
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">New password</label>
                        <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                    </div>
                    @error('new_password')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror
                  </div>
                  <!------------------------>

                  <!-- comfirm password input -->
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Comfirm password</label>
                        <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                    </div>
                    @error('new_confirm_password')
                          <span style="color:red; font-size:13px;">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                          </span>
                    @enderror
                  </div>  
                  <!---------------------------->

                </div><!-- end of row -->

                <button type="submit" class="save-changes-btn shadow-none btn btn-outline-primary"><i class="far fa-save mr-2"></i>Save changes</button>
                
              </form>

            </div>
          </div>
        </div>
        <!-- collapse four end -->

      </div>
      <!-- accordion end -->
      <script>
       
        $(document).ready(function() {
          var last=Cookies.get('activeAccordionGroup');
          if (last!=null) {
              //remove default collapse settings
              $("#accordion .collapse").removeClass('show');
              //show the last visible group
              $("#"+last).collapse("show");
          }
        });

        //when a group is shown, save it as the active accordion group
        $("#accordion").bind('shown.bs.collapse', function() {
            var active=$("#accordion .show").attr('id');
            Cookies.set('activeAccordionGroup', active);
        });

      </script>

    </div>

  </section>
</body>
@endsection