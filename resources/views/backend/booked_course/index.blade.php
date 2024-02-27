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
                                            <h5 class="pt-2">Manage Bookings</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2" id="  " style="display: none;">
                                        <div class="col-md-4">
                                            <label>User Name</label>
                                            <input class="form-control mb-3" type="text" id="search_name" name="search_name" >
                                        </div>
                                        <div class="col-md-4">
                                            <label>Booking Code</label>
                                            <input class="form-control mb-3" type="text" id="search_booking_code" name="search_booking_code" >
                                        </div>
                                        <div class="col-sm-4">
                                                <label>Course Name<span class="text-danger">*</span></label>
                                                <select class="required form-control select2" id="search_course_name" name="search_course_name" style="width: 100% !important;">
                                                    <option value="">All</option>
                                                    @foreach($data['course_data'] as $course)
                                                        <option value="{{$course->id}}">{{$course->course_name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Booking Status</label>
                                            <select class="form-control mb-3 select2" type="text" id="search_booking_status" name="search_booking_status" style="width: 100% !important;">
                                                <option value="">All</option>
                                                <option value="pending">Pending</option>
                                                <option value="upcoming">Upcoming</option>
                                                <option value="ongoing">Ongoing</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Payment Status</label>
                                            <select class="form-control mb-3 select2" type="text" id="search_payment_status" name="search_payment_status" style="width: 100% !important;">
                                                <option value="">All</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Paid</option>
                                                <option value="free">Free</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="booked_course/fetch">
                                            <thead>
                                                <tr>
                                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="booking_code" data-orderable="false" data-searchable="false">Booking Code</th>
                                                    <th id="name" data-orderable="false" data-searchable="false">User Name</th>
                                                    <th id="course_name" data-orderable="false" data-searchable="false">Course Name</th>
                                                    <th id="instructor_name" data-orderable="false" data-searchable="false">Instructor Name</th>
                                                    <th id="status" data-orderable="false" data-searchable="false">Booking Status</th>
                                                    <th id="payment_mode" data-orderable="false" data-searchable="false">Payment Mode</th>
                                                    <th id="payment_status" data-orderable="false" data-searchable="false">Payment Status</th>
                                                    @if($data['booked_course_view'])
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
@endsection
