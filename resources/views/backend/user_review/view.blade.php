<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Review: {{ $user_review->course->course_name ?? '' }} </h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab"
                                                href="#data_details">Details</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="data_details" class="tab-pane fade in active show">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><strong> Course Name</strong></td>
                                                        <td>{{ $user_review->course->course_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong> Instructor Name</strong></td>
                                                        <td>{{ $user_review->instructor->name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong> Rating</strong></td>
                                                        <td>{{ $user_review->rating ?? '' }}</td>
                                                    </tr>
                                                    </tr>
                                                    <tr>
                                                        <td><strong> Review</strong></td>
                                                        <td>{{ $user_review->review ?? '' }}</td>
                                                    </tr>
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
