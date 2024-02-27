@extends('backend.layouts.app')
@section('content')
<div class="wrapper">
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <section id="minimal-statistics">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h1 class="content-header">
                                <b>Fitness Studio Staff Panel</b>
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
                                                <i class="fa fa-graduation-cap warning font-large-2 float-right"></i>
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
                                                <i class="fa fa-caret-square-o-right warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content" style="height:150px;">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="media-body text-left">
                                                <h3 class="mb-1 warning">{{ $books_total }}</h3>
                                                <span>Total Books</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-book warning font-large-2 float-right"></i>
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
                                                <h3 class="mb-1 warning">{{ $audios_total }}</h3>
                                                <span>Total Audio</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-file-audio-o warning font-large-2 float-right"></i>
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
                                                <h3 class="mb-1 warning">{{ $videos_total }}</h3>
                                                <span>Total Video</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-film warning font-large-2 float-right"></i>
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
                                                <h3 class="mb-1 warning">{{ $locations_total }}</h3>
                                                <span>Total Locations</span><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-map-marker warning font-large-2" aria-hidden="true"></i>
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
                                                <h3 class="mb-1 warning">{{ $shlok_total?? '0'}}</h3>
                                                <span>Total Shlokas</span><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-bars warning font-large-2" aria-hidden="true"></i>
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
                                                <h3 class="mb-1 warning">{{ $mantras_total }}</h3>
                                                <span>Total Mantras</span><br><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-server warning font-large-2 float-right"></i>
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
                                                <h3 class="mb-1 warning">{{ $greetings_total }}</h3>
                                                <span>Total Quote</span><br><br>
                                            </div>
                                            <div class="media-right align-self-center">
                                                <i class="fa fa-newspaper-o font-large-2 warning" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
