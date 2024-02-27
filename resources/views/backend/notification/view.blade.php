<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Notification Details : {{$notification->title}} </h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td><strong>Notification Title</strong></td>
                                                <td>{{$notification->title ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Notification Body</strong></td>
                                                <td>{!!  $notification->body_title ?? ''!!}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Master Type</strong></td>
                                                <td>{{$notification->type ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Notification Image</strong></td>
                                                <td>
                                                    @if(!empty($media))
                                                        <img src="{{$media ?? ''}}" width="200px" alt="">
                                                    @endif
                                                </td>
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
</section>






