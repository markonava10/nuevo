<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <title>@yield('page_title', setting('admin.title') . " - " . setting('admin.description'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="assets-path" content="{{ route('voyager.voyager_assets') }}"/>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
    
    @yield('css_before')
    <link rel="stylesheet" id="css-main" href="{{ ('/css/oneui.css') }}">
    <!-- Favicon -->

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ voyager_asset('css/app.css') }}">

    @yield('css')
    @if(__('voyager::generic.is_rtl') == 'true')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="{{ voyager_asset('css/rtl.css') }}">
    @endif

    <!-- Few Dynamic Styles -->
    <style type="text/css">
        .voyager .side-menu .navbar-header {
            background:{{ config('voyager.primary_color','#22A7F0') }};
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary{
            border-color:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .widget .btn-primary:focus, .widget .btn-primary:hover, .widget .btn-primary:active, .widget .btn-primary.active, .widget .btn-primary:active:focus{
            background:{{ config('voyager.primary_color','#22A7F0') }};
        }
        .voyager .breadcrumb a{
            color:{{ config('voyager.primary_color','#22A7F0') }};
        }
    </style>

    @if(!empty(config('voyager.additional_css')))<!-- Additional CSS -->
        @foreach(config('voyager.additional_css') as $css)<link rel="stylesheet" type="text/css" href="{{ asset($css) }}">@endforeach
    @endif

    @yield('head')
</head>


<div class="bg-image" style="background: #ccc">
  <div class="hero-static bg-white-50">
      <div class="content">
          <div class="row justify-content-center">
              <div class="col-md-8 col-lg-6 col-xl-4">
                  <!-- Sign In Block -->
                  <div class="block block-themed block-fx-shadow mb-0">
                      <div class="block-header">
                          <h3 class="block-title">INICIAR  SESION</h3>
                          <!--<div class="block-options">
                              <a class="btn-block-option font-size-sm" href="route('password.reset')">Forgot Password?</a>
                              </a>
                          </div>-->
                      </div>
                    
                      <div class="block-content">
                          <div class="p-sm-3 px-lg-4 py-lg-5">
                              <h1 class="fa fa-circle-notch text-primary">UniPago</h1>
                              <h3>Welcome, please login.</h3>
                              
                              <form action="{{ route('voyager.login') }}" method="POST">
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Email address</label>
                                  <input type="email" name="email"  class="form-control" id="email" aria-describedby="emailHelp" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}" required>
                                </div>
                    
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Password</label>
                                  <input type="password" name="password" id="password"  class="form-control" aria-describedby="emailHelp" placeholder="{{ __('voyager::generic.password') }}" required>
                                </div>

                                <div class="form-group">
                                  <p class="text-center">By signing up you accept our <a href="#">Terms Of Use</a></p>
                                </div>

                                <div class="col-md-12 text-center ">
                                  <button type="submit" id="btn-validar" class=" btn btn-block mybtn btn-primary tx-tfm">
                                    <span class="signingin hidden"><span class="voyager-refresh"></span> {{ __('voyager::login.loggingin') }}...</span>
                                    <span class="signin">{{ __('voyager::generic.login') }}</span>
                                  </button>
                                  
                                </div>
                            </form>
                    
                            <div style="clear:both"></div>
                    
                            @if(!$errors->isEmpty())
                                <div class="alert alert-red">
                                    <ul class="list-unstyled">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                  <!-- END Sign In Block -->
              </div>
          </div>
    </div>
      <div class="content content-full font-size-sm text-muted text-center">
          <strong>UniPago</strong> &copy; <span data-toggle="year-copy">2020</span>
      </div>
  </div>
</div>


@section('post_js')

    <script>
        var btn = document.querySelector('button[type="submit"]');
        var form = document.forms[0];
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        btn.addEventListener('click', function(ev){
            if (form.checkValidity()) {
                btn.querySelector('.signingin').className = 'signingin';
                btn.querySelector('.signin').className = 'signin hidden';
            } else {
                ev.preventDefault();
            }
        });
        email.focus();
        document.getElementById('emailGroup').classList.add("focused");

        // Focus events for email and password fields
        email.addEventListener('focusin', function(e){
            document.getElementById('emailGroup').classList.add("focused");
        });
        email.addEventListener('focusout', function(e){
            document.getElementById('emailGroup').classList.remove("focused");
        });

        password.addEventListener('focusin', function(e){
            document.getElementById('passwordGroup').classList.add("focused");
        });
        password.addEventListener('focusout', function(e){
            document.getElementById('passwordGroup').classList.remove("focused");
        });

    </script>
@endsection




<div class="col-md-12 ">
  <div class="login-or">
     <hr class="hr-or">
     <span class="span-or">or</span>
  </div>
</div>
<div class="col-md-12 mb-3">
  <!--<p class="text-center">
     <a href="javascript:void();" class="google btn mybtn"><i class="fa fa-google-plus">
     </i> Signup using Google
     </a>
  </p>-->
</div>
<div class="form-group">
  <p class="text-center">Don't have account? <a href="#" id="signup">Sign up here</a></p>
</div>