@extends('backend.layouts.apppassword')
@section('title', 'Login')
@section('content')
<div class="wrapper">
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <section id="login" class="auth-height">
                    <div class="row full-height-vh m-0">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="card-body auth-img">
                                        <div class="row m-0">
                                            <div class="col-lg-12 col-12 px-4 py-3" style="width:500px">
                                                <form method="post" id="ForceTochangePwd" action="force_reset_password">
                                                    <h4 class="mb-3 text-center text-primary d-none d-lg-block d-md-block">Your password is expired, Kindly update it.</h4>
                                                    <h5 class="mb-2 text-center text-primary d-block d-lg-none d-md-none">Your password is expired, Kindly update it.</h5>
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12 mb-2 d-none" id="errorMessages">
                                                            <span class="text-danger d-flex justify-content-center"></span>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label>Current Password<span class="text-danger">*</span></label>
                                                            <input class="form-control" required type="password" name="old_password"><br>
                                                            @error('msgOldPass')
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="text-center mb-3">
                                                                        <div class="text-danger">{{$message}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @enderror
                                                            <input class="form-control" type="hidden" name="id" value="{{$data['id']}}">
                                                        </div>  
                                                        <div class="col-md-12">
                                                            <label>New Password<span class="text-danger">*</span></label>
                                                            <input class="form-control" type="password" minlenght="8" maxlength="28" name="new_password"  required><br>
                                                        </div>  
                                                        <div class="col-md-12">
                                                            <label>Confirm Password<span class="text-danger">*</span></label>
                                                            <input class="form-control" type="password" minlenght="8" maxlength="28" name="confirm_password"  required><br>

                                                        </div>
                                                        @error('msgMatchPass')
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="text-center mb-3">
                                                                    <div class="text-danger">{{$message}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @enderror
                                                        @error('change_password')
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="text-center mb-3">
                                                                    <div class="text-danger">{{$message}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @enderror

                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-right">
                                                            <button type="button" id="sbtn" class="btn btn-success" >Reset Password</button>
                                                                <!-- <button type="submit" class="btn btn-success btn-sm">Reset Password</button> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="text-center mb-3">
                                                    @error('msg')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
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
    <script>
    $('#sbtn').click(function () {
        
        var form = $('#ForceTochangePwd');
        var url = form.attr('action');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            window.location.href = "{{ route('login') }}";    
                        } else {
                            $('#errorMessages span').text(response.error);
                            $('#errorMessages').removeClass('d-none');
                        }
                    },
                    error: function (xhr, status, error) {
                    }   
                });
            });
    </script>
@endsection
