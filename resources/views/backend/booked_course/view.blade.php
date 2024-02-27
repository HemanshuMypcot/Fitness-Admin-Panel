<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Bookings: {{$data['course']['booking_code']}} </h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="data_details" class="tab-pane fade in active show">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    @foreach ($data as $value)
                                                    <tr>
                                                        <td><strong>User Name</strong></td>
                                                        <td>{{ $value->user->name ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Course Category</strong></td>
                                                        <td>{{ $value->details['course_category'] ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Course Name</strong></td>
                                                        <td>{{ $value->details['course_name'] ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Instructor Name</strong></td>
                                                        <td>{{ $value->details['instructor_name'] ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Start Date</strong></td>
                                                        <td>{{ date('d-m-Y', strtotime($value->details['start_date'])) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>End Date</strong></td>
                                                        <td>{{ date('d-m-Y', strtotime($value->details['end_date'])) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Start Time</strong></td>
                                                        <td>{{ \Carbon\Carbon::parse($value->course->time_start)->format('H:i') ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>End Time</strong></td>
                                                        <td>{{ \Carbon\Carbon::parse($value->course->time_end)->format('H:i') ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Total Amount</strong></td>
                                                        <td>{{ $value->details['total'] ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Booking Status</strong></td>
                                                        <td>{{ $value->status ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Payment Mode</strong></td>
                                                        <td>{{ $value->payment_mode ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Payment Status</strong></td>
                                                        <td>{{ $value->payment_status ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Payment Date</strong></td>
                                                        <td>{{ $value->updated_at->format('d-m-Y') ?? ''}}</td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

