<!DOCTYPE html>
<html class="loading" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="MYPCOTINFOTECH">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/img/logo_fevicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/mypcot.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/feather/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/prism.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/switchery.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/themes/layout-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/plugins/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendors/css/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/apexcharts.css') }}">
    <!-- added by nikunj -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/flatpickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/vendors/css/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/daterangepicker.css') }}">
    <script src="{{ asset('backend/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('backend/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/js/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/js/bootbox.min.js') }}"></script>
    <!-- added by nikunj -->
    <script src="{{ asset('backend/js/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('backend/js/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/js/flatpickr.min.js') }}"></script>
</head>
<body class="vertical-layout vertical-menu 2-columns" data-menu="vertical-menu" data-col="2-columns" id="container">
    <div class="loader-overlay">
        <div class="loader mx-auto"></div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light header-navbar navbar-fixed mt-2">
        <div class="container-fluid navbar-wrapper">
            <div class="navbar-header d-flex pull-left">
                <div class="navbar-toggle menu-toggle d-xl-none d-block float-left align-items-center justify-content-center" data-toggle="collapse"><i class="ft-menu font-medium-3"></i></div>
                <li class="nav-item mr-2 d-none d-lg-block">
                    {{-- <a class="nav-link apptogglefullscreen" id="navbar-fullscreen" href="javascript:;">
                        <i class="ft-maximize font-medium-3" style="color:black !important"></i>
                    </a> --}}
                </li>
                <h5 class="translateLable padding-top-sm padding-left-sm pt-1"  data-translate="welcome_to_admin_panel">Welcome {{session('data')['name']}}</h5>
            </div>
            <div class="navbar-container pull-right">
                <div class="collapse navbar-collapse d-block" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <div class="d-none d-xl-block">
                            <div class="col-sm-12">
                                <a href="profile" class="mr-1"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Manage Profile" class="fa fa-user-circle-o fa-lg" style="color:brown;"></i></a>

                                <a href="updatePassword"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Change Password" class="fa fa-key fa-lg" style="color:brown;"></i></a>

                                <a href="logout"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Logout" class="fa fa-power-off fa-lg" style="color:brown;"></i></a>
                            </div>
                        </div>
                        <li class="dropdown nav-item d-xl-none d-block"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i>
                            <div class="dropdown-menu text-left dropdown-menu-right m-0 pb-0 dropdownBasic3Content" aria-labelledby="dropdownBasic2">
                                <a class="dropdown-item" href="">
                                    <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Edit Profile</span></div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="">
                                    <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Update Password</span></div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout">
                                    <div class="d-flex align-items-center"><i class="ft-power mr-2"></i><span>Logout</span></div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <div class="app-sidebar menu-fixed" style="background-image: url({{ asset('backend/img/side_nav_bg.png') }}); background-size: cover; background-position: top left;">
            <div class="sidebar-header">
                <div class="logo clearfix">
                    <a class="logo-text float-left" href="dashboard">
                        <div class="logo-img">
                            <img class="sidelogo" src="{{ asset('backend/img/logo.png') }}" alt="Logo"/>
                            <img class="sidelogosmall" src="{{ asset('backend/img/small_logo.png') }}" alt="Logo" style="display: none; width: 40px;" />
                        </div>
                    </a>
                    <a class="nav-toggle d-none d-lg-none d-xl-block is-active" id="sidebarToggle" href="javascript:;"><i class="toggle-icon ft-toggle-right" data-toggle="collapsed"></i></a>
                    <a class="nav-close d-block d-lg-block d-xl-none" id="sidebarClose" href="javascript:;"><i class="ft-x"></i></a>
                </div>
            </div>
            <div class="sidebar-content main-menu-content scroll">
                @php
                //$lastParam =  last(request()->segments());
                //GET OATH :: Request::path()
                    $lastParam =  Request::segment(2); //Doubt_aala
                    // print_r($lastParam);exit;
                    $permissions = Session::get('permissions');
                    $count = count($permissions);
                    $permission_array = array();
                @endphp
                @for($i=0; $i<$count; $i++)
                    @php
                        $permission_array[$i] = $permissions[$i]->codename;
                    @endphp
                @endfor
                <div class="nav-container">
                    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                        <li class="nav-item {{ Request::path() ==  'dashboard' ? 'active' : ''  }}">
                            <a href="dashboard"><i class="ft-home"></i><span class="menu-title" data-i18n="Documentation">Dashboard</span></a>
                        </li>
                        @if(session('data')['role_id'] == 1  ||
                        in_array('instructor', $permission_array) ||
                        in_array('course_category', $permission_array)||
                        in_array('course', $permission_array)||
                        in_array('home_collection', $permission_array)
                       )
                        <li class="has-sub nav-item">
                            <a href="javascript:;" class="dropdown-parent"><i class="fa fa-cogs"></i><span data-i18n="" class="menu-title">Master</span></a>
                            <ul class="menu-content">
                                @if(session('data')['role_id'] == 1  ||
                                in_array('course_category', $permission_array))
                                <li class="{{ $lastParam ==  'course_categories' ? 'active' : '' }}">
                                    <a href="course_categories" class="menu-item"><i class="fa fa-list" aria-hidden="true"></i>Course Category</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('instructor', $permission_array))
                                <li class="{{ $lastParam ==  'instructors' ? 'active' : '' }}">
                                    <a href="instructors" class="menu-item"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Instructor</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('location', $permission_array))
                                <li class="{{ $lastParam ==  'location' ? 'active' : '' }}">
                                    <a href="location" class="menu-item"><i class="fa fa-building-o" aria-hidden="true"></i>Location</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('course', $permission_array))
                                <li class="{{ $lastParam ==  'course' ? 'active' : '' }}">
                                    <a href="course" class="menu-item"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i>Course</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('home_collection', $permission_array))
                                <li class="{{ $lastParam ==  'home_collection' ? 'active' : '' }}">
                                    <a href="home_collection" class="menu-item"><i class="fa fa-list-alt"></i>Home Collection</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('movie_category', $permission_array))
                                <li class="{{ $lastParam ==  'movie_category' ? 'active' : '' }}">
                                    <a href="movie_categories" class="menu-item"><i class="fa fa-film"></i>Movie Category</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(session('data')['role_id'] == 1  ||
                            in_array('customer', $permission_array) ||
                            in_array('notification', $permission_array)||
                            in_array('booked_course', $permission_array)
                           )

                        <li class="has-sub nav-item">
                            <a href="javascript:;" class="dropdown-parent"><i class="fa fa-users"></i><span data-i18n="" class="menu-title">App Users</span></a>
                            <ul class="menu-content">
                                @if(session('data')['role_id'] == 1  ||
                                in_array('customer', $permission_array))
                                <li class="{{ $lastParam ==  'customer' ? 'active' : '' }}">
                                    <a href="customer" class="menu-item"><i class="fa fa-list"></i>List</a>
                                </li>
                                @endif
                                <!-- <li class="{{ $lastParam ==  'enquiries' ? 'active' : '' }}">
                                    <a href="enquiries" class="menu-item"><i class="fa fa-question-circle"></i>Enquiries</a>
                                </li> -->
                                @if(session('data')['role_id'] == 1  ||
                                in_array('booked_course', $permission_array))
                                <li class="{{ $lastParam ==  'booked_course' ? 'active' : '' }}">
                                    <a href="booked_course" class="menu-item"><i class="fa fa-check-circle" aria-hidden="true"></i>Bookings</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                    in_array('user_review', $permission_array)
                                    )
                                    <li class="nav-item {{ $lastParam ==  'user_review' ? 'active' : ''  }}">
                                        <a href="user_review"><i class="fa ft-star"></i><span class="menu-title">Instructor Reviews</span></a>
                                    </li>
                                @endif
                                    @if(session('data')['role_id'] == 1  ||
                              in_array('contact', $permission_array))
                                        <li class="{{ $lastParam ==  'enquiries' ? 'active' : '' }}">
                                            <a href="enquiries" class="menu-item"><i class="fa fa-question-circle"></i>Enquiries</a>
                                        </li>
                                    @endif
                                {{-- @if(session('data')['role_id'] == 1  ||
                                    in_array('notification', $permission_array)
                                    )
                                    <li class="nav-item {{ $lastParam ==  'notification' ? 'active' : ''  }}">
                                        <a href="notification"><i class="fa ft-bell"></i><span class="menu-title">FCM Notification</span></a>
                                    </li>
                                @endif --}}
                            </ul>
                        </li>
                        @endif
                        @if(session('data')['role_id'] == 1 )
                        <li class="has-sub nav-item">
                            <a href="javascript:;" class="dropdown-parent"><i class="fa fa-file-excel-o"></i><span data-i18n="" class="menu-title">Reports</span></a>
                            <ul class="menu-content">
                                @if(session('data')['role_id'] == 1)
                                <li class="{{ $lastParam ==  'customer_report' ? 'active' : '' }}">
                                    <a href="customer_report" class="menu-item"><i class="fa fa-file"></i>Customer Data</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1)
                                <li class="{{ $lastParam ==  'booked_course_report' ? 'active' : '' }}">
                                    <a href="booked_course_report" class="menu-item"><i class="fa fa-file"></i>Booked Course Data</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if(session('data')['role_id'] == 1  ||
                            in_array('role', $permission_array) ||
                            in_array('staff', $permission_array)
                           )
                        <li class="has-sub nav-item">
                            <a href="javascript:;" class="dropdown-parent"><i class="icon-user-following"></i><span data-i18n="" class="menu-title">Staff</span></a>
                            <ul class="menu-content">
                                @if(session('data')['role_id'] == 1  ||
                                in_array('role', $permission_array))
                                <li class="{{ $lastParam ==  'roles' ? 'active' : '' }}">
                                    <a href="roles" class="menu-item"><i class="fa fa-circle fs_i"></i>Manage Roles</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('staff', $permission_array))
                                <li class="{{ $lastParam ==  'staff' ? 'active' : '' }}">
                                    <a href="staff" class="menu-item"><i class="fa fa-circle fs_i"></i>Manage Staff</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if(session('data')['role_id'] == 1  ||
                            in_array('general_settings', $permission_array) ||
                            in_array('about_us', $permission_array) ||
                            in_array('terms', $permission_array)||
                            in_array('privacy_policy', $permission_array)||
                            in_array('cancellation_policy', $permission_array)||
                            in_array('refund_policy', $permission_array)

                        )
                        <li class="has-sub nav-item">
                            <a href="javascript:;" class="dropdown-parent"><i class="fa fa-wrench"></i><span data-i18n="" class="menu-title">General Settings</span></a>
                            <ul class="menu-content">
                                @if(session('data')['role_id'] == 1  ||
                                in_array('general_settings', $permission_array))
                                <li class="{{ $lastParam ==  'general_settings' ? 'active' : '' }}">
                                    <a href="general_settings" class="menu-item"><i class="fa fa-cog" aria-hidden="true"></i>Settings</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('about_us', $permission_array))
                                <li class="{{ $lastParam ==  'about_us' ? 'active' : '' }}">
                                    <a href="about_us" class="menu-item"><i class="fa fa-info-circle"></i>About Us</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('terms', $permission_array))
                                <li class="{{ $lastParam ==  'terms' ? 'active' : '' }}">
                                    <a href="terms" class="menu-item"><i class="fa fa-file-text-o"></i>Terms and Condition</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('privacy_policy', $permission_array))
                                <li class="{{ $lastParam ==  'privacy_policy' ? 'active' : '' }}">
                                    <a href="privacy_policy" class="menu-item"><i class="fa fa-shield"></i>Privacy Policy</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('cancellation_policy', $permission_array))
                                <li class="{{ $lastParam ==  'cancellation_policy' ? 'active' : '' }}">
                                    <a href="cancellation_policy" class="menu-item"><i class="fa fa-times"></i>Cancellation Policy</a>
                                </li>
                                @endif
                                @if(session('data')['role_id'] == 1  ||
                                in_array('refund_policy', $permission_array))
                                <li class="{{ $lastParam ==  'refund_policy' ? 'active' : '' }}">
                                    <a href="refund_policy" class="menu-item"><i class="fa fa-undo" aria-hidden="true"></i></i>Refund Policy</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item {{ $lastParam ==  'logout' ? 'active' : ''  }}">
                            <a href="logout"><i class="fa fa-power-off"></i><span class="menu-title" >Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="main-panel">
            @yield('content')
            <footer class="footer">
                <p class="clearfix text-muted m-0"><span>Copyright &copy; 2023 &nbsp;</span><span class="d-none d-sm-inline-block"> All rights reserved.</span></p>
            </footer>
            <button class="btn btn-primary scroll-top" type="button"><i class="ft-arrow-up"></i></button>
        </div>
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>
    </div>
</body>
<script src="{{ asset('backend/vendors/js/switchery.min.js') }}"></script>
<script src="{{ asset('backend/js/core/app-menu.js') }}"></script>
<script src="{{ asset('backend/js/core/app.js') }}"></script>
<script src="{{ asset('backend/js/notification-sidebar.js') }}"></script>
<script src="{{ asset('backend/js/customizer.js') }}"></script>
<script src="{{ asset('backend/js/scroll-top.js') }}"></script>
<script src="{{ asset('backend/js/scripts.js') }}"></script>
<script src="{{ asset('backend/js/ajax-custom.js') }}"></script>
<script src="{{ asset('backend/js/mypcot.min.js') }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/vendors/js/apexcharts.min.js') }}"></script>
</html>
