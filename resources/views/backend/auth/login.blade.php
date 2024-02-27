@extends('backend.layouts.applogin')
@section('content')
<div class="wrapper">
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="loader-overlay">
                    <div class="loader mx-auto"></div>
                </div>
                <section id="login" class="auth-height">
                    <div class="row full-height-vh m-0">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="col-lg-5 col-md-6 col-12">
                                <div class="text-center">
                                    <img class="img-fluid" src="{{ asset('backend/img/logo.png ') }}" style="width: 200px;">
                                </div>
                                <div class="card overflow-hidden">
                                    <div class="card-content">
                                        <div class="card-body auth-img">
                                            <div class="row mt-3 mb-3 ml-4 mr-4">
                                                <form class="col-12 px-4 py-3" action="{{ url('webadmin/login') }}" method="POST">
                                                    @csrf
                                                    <h5 class="text-center mb-1">Welcome</h5>
                                                    <h3 class="text-center mb-1 text-danger">Admin <span style="color: #cf9c11;">Team</span></h3>
                                                    <div class="text-center">
                                                        <!-- <img class="img-fluid" src="{{ asset('backend/img/logo.png ') }}" style="width: 250px;"> -->
                                                    </div>
                                                    <br/>
                                                    <div class="mb-3">
                                                        <input type="email" class="form-control" placeholder="Email" required="" name="email">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="password" class="form-control" placeholder="Password" required="" name="password" id="password">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-eye-slash" id="togglePassword"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="d-sm-flex justify-content-between mb-3 font-small-2">
                                                            <div class="remember-me mb-2 mb-sm-0"></div>
                                                            @if(Route::has('password.request'))
                                                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href={{ route('password.request') }}>
                                                                    {{ ('Forgot your password?') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <button class="col-12 btn btn-danger">Login</button>
                                                        </div>
                                                    </div>
                                                    <br/>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-right" id="error-msg">
                                                                @error('msg')
                                                                    <div class="text-danger">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
