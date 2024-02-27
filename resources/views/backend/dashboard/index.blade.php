@extends('backend.layouts.app')
@section('content')
<div class="wrapper">
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <!-- content -->
                <section id="minimal-statistics">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h1 class="content-header">
                                <b>Fitness Studio Admin Panel</b>
                            </h1>
                            <hr style="border: none; border-bottom: 1px solid black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content" style="height:150px;">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-1 warning">{{ $users_total }}</h3>
                                                <span>Users</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="ft-users warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content" style="height:150px;">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-1 warning">{{ $instructor_total }}</h3>
                                                <span>Instructors</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-hand-o-left warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content" style="height:150px;">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-1 warning">{{ $course_total }}</h3>
                                                <span>Courses</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-cog warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content" style="height:150px;">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-1 warning">{{ $booked_course_total }}</h3>
                                                <span>Booked Courses</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-cart-plus warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- Bar Chart starts -->
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center"><b>Fitness Studio's App Users</b></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div id="line-chart2" class="d-flex justify-content-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     <!-- Bar Chart ends -->
                    </div>

                    <div class="row">
                        <!-- Line Chart starts -->
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center"><b>Fitness Studio's Booked Courses</b></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div id="line-chart3" class="d-flex justify-content-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     <!-- Line Chart ends -->
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        barChartDashboard();
        lineChartDashboard();

    });
    </script>
@endsection
