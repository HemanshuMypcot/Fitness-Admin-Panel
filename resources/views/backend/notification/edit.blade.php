<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Notification : {{ $notification->title ?? '' }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                        <div class="card-body">
                            <form id="saveNotification" method="post" action="notification/update">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label>Notification Title<span style="color:#ff0000">*</span></label>
                                        <input class="form-control required " type="text" id="title" name="title" value="{{ $notification->title ?? '' }}">
                                        <input type="hidden" value="{{$notification->id}}" name="id">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label>Notification Body<span style="color:#ff0000">*</span></label>
                                        <textarea class="form-control required" type="text" id="body" name="body">{{ $notification->body ?? '' }}</textarea>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <label>Master Type<span class="text-danger">*</span></label>
                                        <select class="form-control select2 required" id="notification_master_type" name="type">
                                            @foreach($notificationsType as $value => $type)
                                                <option value="{{$value}}" {{  ($notification->type == $value) ? 'selected' : ''}}>{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        @php
                                        $type = $notification->type;
                                        @endphp
                                        <label>Master Value<span class="text-danger">*</span></label>
                                        <select class="form-control select2 required" id="master_value" name="selected_id">
                                            @if($type == \App\Models\Notification::CATEGORY && isset($category))
                                                @foreach($category as $categories)
                                                    <option value="{{$categories->id}}" {{($categories->id == $notification->selected_id) ? 'selected' : ''}}>{{$categories->category_name}}</option>
                                                @endforeach
                                            @endif
                                                @if($type == \App\Models\Notification::COURSE && isset($course))
                                                    @foreach($course as $courses)
                                                        <option value="{{$courses->id}}" {{($courses->id == $notification->selected_id) ? 'selected' : ''}}>{{$courses->name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 text-center my-2">
                                        <p class="font-weight-bold">Notification Image </p>
                                        <div class="shadow bg-white rounded d-inline-block mb-2">
                                            <div class="input-file">
                                                <label class="label-input-file">Choose Files &nbsp;&nbsp;&nbsp;<i class="ft-upload font-medium-1"></i><input type="file" name="image" class="notification-images " id="notificationImages" accept=".jpg, .jpeg, .png">
                                                </label>
                                            </div>
                                        </div>
                                        <p id="files-area">
                                                            <span id="notificationImagesLists">
                                                                <span id="notification-images-names"></span>
                                                            </span>
                                        </p>
                                        <p style="color:blue;">Note : Upload file size {{config('global.dimensions.notification_width')}}X{{config('global.dimensions.notification_height')}} pixel and .jpg, .png, or jpeg format image</p>
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
                                </div>
                                <hr>
                                <h4><i class="ft-info mr-1"></i> Replacement Options:</h4>
                                <p>$$user_name$$ - Replacement for user name</p>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('saveNotification','post')">Submit</button>
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
    $(document).ready(function() {
        addNotification ()
    });
</script>
