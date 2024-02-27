@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <section class="users-list-wrapper">
                <div class="users-list-table">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12 col-sm-7">
                                                <h5 class="pt-2">Manage Instructor Reviews</h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- <hr class="mb-0"> -->
                                    <div class="card-body">
                                        <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                            <div class="col-sm-4">
                                                <label>Course Name<span class="text-danger">*</span></label>
                                                <select class="required form-control select2" id="course_name" name="course_name" style="width: 100% !important;">
                                                    <option value="">All</option>
                                                    @foreach($data['course_data'] as $course_data)
                                                        <option value="{{$course_data->id}}">{{$course_data->course_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Instructor Name<span class="text-danger">*</span></label>
                                                <select class="required form-control select2" id="instructor_name" name="instructor_name" style="width: 100% !important;">
                                                    <option value="">All</option>
                                                    @foreach($data['instructor_data'] as $instructor_data)
                                                        <option value="{{$instructor_data->id}}">{{$instructor_data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Rating</label>

                                                <select class="form-control mb-3 select2" id="rating" name="rating" style="width: 100% !important;">
                                                    <option selected value="">Select Rating</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>

                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label><br/>
                                                <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="user_review/fetch">
                                                <thead>
                                                <tr>
                                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="user_name" data-orderable="false" data-searchable="false">User Name</th>
                                                    <th id="course_name" data-orderable="false" data-searchable="false">Course Name</th>
                                                    <th id="instructor_name" data-orderable="false" data-searchable="false">Instructor Name</th>
                                                    <th id="rating" data-orderable="false" data-searchable="false">Rating</th>
                                                    @if($data['user_review_view'])
                                                        <th id="action" data-orderable="false" data-searchable="false" width="130px">Action</th>
                                                    @endif
                                                </tr>
                                                </thead>
                                            </table>
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
    <script>
        $('.select2').select2();
    </script>
@endsection
