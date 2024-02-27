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
                                                <h5 class="pt-2">Booked Course Report</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form id="bookedCourseReport" method="post" action="booked_course_report_export">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label>Booking Date</label>
                                                    <input type="text" class="form-control" id="date_range_picker" name="course_date" value="">
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label>Booking Status</label>
                                                    <select class="form-control mb-3 select2" type="text" id="status" name="status">
                                                        <option value="">Choose Booking Status</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="upcoming">Upcoming</option>
                                                        <option value="ongoing">Ongoing</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label>Payment Status</label>
                                                    <select class="form-control mb-3 select2" type="text" id="payment_status" name="payment_status">
                                                        <option value="">Choose Payment Status</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="paid">Paid</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label>Payment Method</label>
                                                    <select class="form-control mb-3 select2" type="text" id="payment_mode" name="payment_mode">
                                                        <option value="">Choose Payment Method</option>
                                                        <option value="COD">Cash On Delivery</option>
                                                        <option value="Online">Online</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label>Course</label>
                                                    <select class="form-control mb-3 select2" type="text" id="course_ids" name="course_ids[]" multiple>
                                                        @foreach($courses as $course)
                                                            <option value="{{$course->id}}">{{$course->sku_code}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="pull-right">
                                                        <button type="submit" class="btn btn-success export">Export</button>
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
            </section>
        </div>
    </div>
<script>
$('#date_range_picker').daterangepicker({
    startDate: moment().subtract(1, 'months'), // Set start date to 1 month before current moment
    endDate: moment(),
    locale: {
        format: 'DD/MM/YYYY'
    }
});
</script>
@endsection
