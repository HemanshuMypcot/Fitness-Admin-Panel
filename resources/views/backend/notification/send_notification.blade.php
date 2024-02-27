<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Send Notification : {{$notification->title}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                        <div class="card-body">
                            <form id="sendNotification" method="post" action="notification/send">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6 mb-2">
                                        <p><b>Notification Title : </b>{{$notification->title}}</p>
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 mb-2">
                                        <p><b>Notification Body : </b>{{$notification->body}}</p>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 my-2">
                                        <p class="font-weight-bold">Notification Image </p>
                                        <div class="mt-2">
                                            @foreach($images as $image)
                                                <div class="d-flex mb-1  cover-image-div-{{$image->id}}">
                                                    <input type="text"
                                                           class="form-control input-sm bg-white document-border"
                                                           value="{{ $image->file_name }}"
                                                           readonly style="color: black !important;">
                                                    <a href="{{ $image->getFullUrl() }}"
                                                       class="btn btn-primary mx-2 px-2" target="_blank"><i
                                                                class="fa ft-eye"></i></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4 mb-2">
                                        <label><b>Trigger Date</b><span style="color:#ff0000">*</span></label>
                                        <input type="date" class="form-control required" id="date_time" name="date_time"  min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}"><br/>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 mb-2">
                                        <label><b>Trigger Time (Hours)</b><span style="color:#ff0000">*</span></label>
                                        <select class="form-control select2 required" id="trigger_time" name="trigger_time">
                                            @for($i=0;$i<=23;$i++)
                                                <option  value="{{$i}}" {{ (!empty($currentHour && $i > $currentHour) ? '' : 'disabled') }}>{{($i <= 9 ) ? '0'.$i  : $i }} : 00</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 mb-2">
                                        <label>user<span class="text-danger">*</span></label>
                                        <select class="form-control select2 required" id="notification_user_ids" name="user_ids[]" multiple>
                                            <option value="all">All</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('sendNotification','post')">Send Notification</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1">Cancel</a>
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
<script>
    $('.select2').select2();
    $(document).ready(function() {

        
    });
</script>
