<!DOCTYPE html>
<html class="loading" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="MYPCOTINFOTECH">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../backend/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../backend/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../backend/css/components.css">
    <link rel="stylesheet" type="text/css" href="../../../backend/css/style.css">
    <link rel="stylesheet" href="../../../backend/css/pages/authentication.css">
</head>

<body class="vertical-layout vertical-menu 1-column auth-page navbar-sticky blank-page" data-menu="vertical-menu" data-col="1-column">
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
                                            <div class="col-md-12 px-4 py-3" style="width:400px">
                                                <form method="POST" action="{{ route('password.update') }}">
                                                    @csrf
                                                    <h4 class="card-title mb-2 text-center">Reset Password</h4><br>
                                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" value="{{ $request->route('email') }}" readonly><br>
                                                    <input type="password" class="form-control mb-2" placeholder="Password" name="password" required><br>
                                                    <input type="password" class="form-control mb-2" placeholder="Confirm Password" name="password_confirmation"  required>
                                                    <br>
                                                    <div class="text-center">
                                                        <button class="btn btn-primary" type="submit">Reset</button>
                                                    </div><br>
                                                    <p class="text-center">Go Back to <a href="{{url('/webadmin')}}"><u>Login page</u></a></p>
                                                    @if($errors->any())
                                                        <div class="text-center">
                                                            <span style="color:red">{{$errors->first()}}</span><br/>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                    </section>
                    <!--Registration Page Ends-->

                        </section>
                    <!--Registration Page Ends-->

                    </div>
                </div>
            </div>
        </div>
</body>
</html>
