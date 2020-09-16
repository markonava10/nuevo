@extends('layouts.simple')
@section('content')
<!-- Page Content -->
<div class="bg-image" style="background: #ccc">
    <div class="hero-static bg-white-50">
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <!-- Sign In Block -->
                    <div class="block block-themed block-fx-shadow mb-0">
                        <div class="block-header">
                            <h3 class="block-title">Sign In</h3>
                            <div class="block-options">
                                <a class="btn-block-option font-size-sm" href="{{route('password.reset')}}">Forgot Password?</a>
                                </a>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="p-sm-3 px-lg-4 py-lg-5">
                                <h1 class="fa fa-circle-notch text-primary">Morpheus</h1>
                                <p>Welcome, please login.</p>

                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js) -->
                                <!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                
                                <form id="contacto_form" action="{{ route('voyager.login') }}" method="POST">
                                    @csrf
                                    <div class="py-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-alt form-control-lg" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('voyager::generic.email') }}">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-alt form-control-lg" id="password" name="password" placeholder="{{ __('voyager::generic.password') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="login-remember" name="login-remember">
                                                <label class="custom-control-label font-w400" for="login-remember">Remember Me</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 col-xl-5">
                                            <button type="submit" id="btn-validar" class="btn btn-block btn-primary">
                                            <span class="signingin hidden"><span class="voyager-refresh"></span> </span>
                                            <span class="signin">Sign in </span>
                                            </button>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    @if(!$errors->isEmpty())
                                        @foreach($errors->all() as $err)
                                        <div class="alert alert-danger alert-important" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            {{ $err }}
                                        </div>
                                        @endforeach
                                    @endif
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" style="clear:both"></div>
                    
                    <!-- END Sign In Block -->
                </div>
            </div>
        </div>
        <div class="content content-full font-size-sm text-muted text-center">
            <strong>Morpheus</strong> &copy; <span data-toggle="year-copy">2019</span>
        </div>
    </div>
</div>
<!-- END Page Content -->

</main>
<!-- END Main Container -->
</div>
<!-- END Page Container -->
@endsection