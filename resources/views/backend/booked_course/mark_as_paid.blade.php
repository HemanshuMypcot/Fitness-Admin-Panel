<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Update Booking Status : {{$bookedCourse->booking_code}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="updateBookingStatus" method="post" action="update_booking_status">
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <div class="row">
                                            <dt class="col-sm-6 text-left">Booking Code</dt>
                                            <dd class="col-sm-6">{{$bookedCourse->booking_code}}</dd>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="row">
                                            <dt class="col-sm-6 text-left">User Name</dt>
                                            <dd class="col-sm-6">{{$bookedCourse->user->name ?? ''}}</dd>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="row">
                                            <dt class="col-sm-6 text-left">Course Name</dt>
                                            <dd class="col-sm-6">{{$bookedCourse->details['course_name'] ?? ''}}</dd>
                                        </div>
                                    </div>
                                    @if(!empty($bookedCourse->details['start_date']))
                                    <div class="col-sm-6 mb-3">
                                        <div class="row">
                                            <dt class="col-sm-6 text-left">Course Start Date</dt>
                                            <dd class="col-sm-6">{{\Carbon\Carbon::parse($bookedCourse->details['start_date'])->format('d-m-y') ?? ''}}</dd>
                                        </div>
                                    </div>
                                     @endif
                                    <div class="col-sm-6 mb-3">
                                        <div class="row">
                                            <dt class="col-sm-6 text-left">Payment Status</dt>
                                            <dd class="col-sm-6">{{ucfirst($bookedCourse->payment_status ?? '')}}</dd>
                                        </div>
                                    </div>
                                    @if(isset($bookedCourse->details['total']))
                                        <div class="col-sm-6 mb-3">
                                            <div class="row">
                                                <dt class="col-sm-6 text-left">Pending Amount</dt>
                                                <dd class="col-sm-6">{{$bookedCourse->details['total']}}</dd>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-6">
                                        <label>Payment Status<span
                                                    style="color:#ff0000">*</span></label>
                                        <select class="form-control mb-3 select2 required "
                                                name="payment_status" id="payment_status" style="width: 100% !important;">
                                            <option value="paid">Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Pending Amount<span
                                                    style="color:#ff0000">*</span></label>
                                        <input class="form-control required" type="number" disabled value="{{$bookedCourse->details['total'] ?? 0}}" id="pending_amount" name="pending_amount">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            @csrf
                                            <input type="hidden" value="{{$bookedCourse->id}}" name="booking_id" id="booking_id">
                                            <button type="button" class="btn btn-success" onclick="submitForm('updateBookingStatus','post')">Submit</button>
                                            <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
