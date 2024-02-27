<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Copy Course : {{$course['course_name']}} ({{ config('translatable.locales_name')[\App::getLocale()] }}) - {{$course['sku_code']}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="savecoursecategory" method="post" action="course/save">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#payment">Payments</a>
                                            </li>
                                            <?php foreach (config('translatable.locales') as $translated_tabs) { ?>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#<?php echo $translated_tabs ?>_block_details">{{ config('translatable.locales_name')[$translated_tabs] }}</a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="data_details" class="tab-pane fade in active show">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>SEQUENCE<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" pattern="[0-9]*" value="{{$course['sequence']}}" oninput="validateInteger(event)"  id="sequence" name="sequence"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Frequency<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="course_type" name="type" style="width: 100% !important;">
                                                            <option value="one_day">One Day</option>
                                                            <option @if ($course['type']=="recurring") selected @endif  value="recurring">Recurring</option>
                                                        </select><br/>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label>Course Category<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="course_category_id" name="course_category_id" style="width: 100% !important;">
                                                            <option value="">Choose Course Category</option>
                                                            @foreach($course_categories as $course_cat)
                                                                <option @if ($course['course_category_id']==$course_cat->id) selected @endif  value="{{$course_cat->id}}">{{$course_cat->category_name}} </option>
                                                            @endforeach
                                                        </select>
                                                        <br/>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label>Course Instructor<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="instructor_id" name="instructor_id" style="width: 100% !important;">
                                                            <option value="">Choose Course Instructor</option>
                                                            @foreach($instructors as $course_cat)
                                                                <option @if ($course['instructor_id']==$course_cat->id) selected @endif value="{{$course_cat->id}}">{{$course_cat->name}} </option>
                                                            @endforeach
                                                        </select><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course Start Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="text" value="{{ \Carbon\Carbon::parse($course['date_start'])->format('d-m-Y') }}" id="course_start_date" name="date_start"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="course-end-date-div {{ $course['type']=="one_day" ? 'd-none' : '' }}">
                                                            <label>Course End Date<span class="text-danger">*</span></label>
                                                            <input class="form-control required" type="text"  value="{{ \Carbon\Carbon::parse($course['date_end'])->format('d-m-Y')}}" id="course_end_date" name="date_end">
                                                        </div>
                                                        <br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course Start Time<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="time" value="{{ date('H:i', strtotime($course['time_start'])) }}" id="course_start_time" name="time_start"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course End Time<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="time" value="{{ date('H:i', strtotime($course['time_end'])) }}" id="course_end_time" name="time_end"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Registration Start Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required flatpickr" type="text" id="course_registration_start_date" value="{{\Carbon\Carbon::parse($course['registration_start'])->format('d-m-Y')}}" name="registration_start"><br/>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <label>Registration End Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required flatpickr" type="text" id="course_registration_end_date" value="{{\Carbon\Carbon::parse($course['registration_end'])->format('d-m-Y')}}" name="registration_end"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Seats<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" value="{{$course['capacity']}}" pattern="[0-9]*" oninput="validateInteger(event)"  id="capacity" name="capacity"><br/>
                                                        <div class="row">
                                                            <div class="col-md-6 col-lg-12 col-sm-6 text-center file-input-div">
                                                                <label>Image <span class="text-danger">*</span></label><br>
                                                                <div class="shadow bg-white rounded d-inline-block mb-2">
                                                                    <div class="input-file">
                                                                        <label class="label-input-file">Choose Files <i class="ft-upload font-medium-1"></i>
                                                                            <input class="form-control required" accept=".jpg,.jpeg,.png" type="file" id="cover_image" name="image" onchange="handleFileInputChange('cover_image')"><br/>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p id="files-area">
                                                                    <span id="cover_image_list">
                                                                        <span id="cover_image_name"></span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            @if(!empty($media_file_name))
                                                                <div class="d-flex mb-1  media-div-{{$media_id}}">
                                                                    <input type="text"
                                                                           class="form-control input-sm bg-white document-border"
                                                                           value="{{ $media_file_name ?? '' }}"
                                                                           readonly style="color: black !important;">
                                                                    <a href="{{ $media_image }}"
                                                                       class="btn btn-primary mx-2 px-2" target="_blank"><i
                                                                                class="fa ft-eye"></i></a>
                                                                </div>
                                                            @endif
                                                            <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_image_width')}}X{{config('global.dimensions.course_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Location<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" type="text" id="location_id" name="location_id" style="width: 100% !important;">
                                                            <option value="">Choose Course Location</option>
                                                        @foreach($location as $location)
                                                            <option @if ($course['location_id']==$location->id) selected @endif  value="{{$location->id}}">{{$location->name}}</option>
                                                        @endforeach
                                                        </select>
                                                        <div class="mt-3"></div>
                                                        <div class="col-sm-6 {{ ($course['type'] =='one_day') ? 'd-none' : '' }}" id="checklist_wrapper">
                                                            <label>Classes On Every :<span class="text-danger">*</span></label>
                                                            @php
                                                                $opensAtData = $course['opens_at'];
                                                            @endphp
                                                            <table class="">
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="mondayCheckbox" name="monday" @if ($opensAtData['monday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="mondayCheckbox">Monday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="tuesdayCheckbox" name="tuesday" @if ($opensAtData['tuesday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="tuesdayCheckbox">Tuesday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="wednesdayCheckbox" name="wednesday" @if ($opensAtData['wednesday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="wednesdayCheckbox">Wednesday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="thursdayCheckbox" name="thursday" @if ($opensAtData['thursday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="thursdayCheckbox">Thursday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="fridayCheckbox" name="friday" @if ($opensAtData['friday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="fridayCheckbox">Friday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="saturdayCheckbox" name="saturday" @if ($opensAtData['saturday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="saturdayCheckbox">Saturday</label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left: 20px" class="toggle-button text-center">
                                                                        <input type="checkbox" id="sundayCheckbox" name="sunday" @if ($opensAtData['sunday'] === 'on') checked @endif>
                                                                    </td>
                                                                    <td>
                                                                        <label for="sundayCheckbox">Sunday</label>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>

                                            <div id="payment" class="tab-pane fade">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Sub Total<span class="text-danger">*</span></label>
                                                        <input class="form-control required allow_numeric" type="number" id="amount" value="{{$course['amount']}}" name="amount"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Tax (In Percentage)<span class="text-danger">*</span></label>
                                                        <input class="form-control required allow_numeric" type="number" id="tax" value="{{$course['tax']}}" name="tax"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Service Charge (In Percentage)<span class="text-danger">*</span></label>
                                                        <input class="form-control required allow_numeric" type="number" id="service_charge" value="{{$course['service_charge']}}" name="service_charge"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Discount (In Percentage)</label>
                                                        <input class="form-control allow_numeric" type="number" id="discount" name="discount" value="{{$course['discount']}}"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Payable Amount<span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" id="total" value="{{$course['total']}}" name="total"readonly><br/>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php foreach (config('translatable.locales') as $translated_data_tabs) { ?>
                                            <div id="<?php echo $translated_data_tabs ?>_block_details" class="tab-pane fade">
                                                <div class="row">
                                                    <?php foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value) { ?>
                                                    <?php if($translated_block_fields_value == 'input') { ?>
                                                    <div class="col-md-6 mb-3">
                                                        <label>{{str_replace('_',' ',$translated_block_fields_key)}}<span class="text-danger">*</span></label>
                                                        <input class="translation_block form-control required" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" value="{{$course[$translated_block_fields_key.'_'.$translated_data_tabs] ?? ''}}">
                                                    </div>
                                                    <?php } ?>

                                                    <?php if($translated_block_fields_value == 'textarea') { ?>
                                                    <div class="col-md-6 mb-3">
                                                        @if( formatName($translated_block_fields_key) == 'description')
                                                            <label>Long Description<span class="text-danger">*</span></label>
                                                        @else
                                                            <label>{{formatName(str_replace('_',' ',$translated_block_fields_key))}}<span class="text-danger">*</span></label>
                                                        @endif
                                                        <textarea class="translation_block form-control required" rows="7" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}">{{$course[$translated_block_fields_key.'_'.$translated_data_tabs] ?? ''}}</textarea>
                                                    </div>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('savecoursecategory','post')">Submit</button>
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
    const imageData = new DataTransfer();
    handleCoverImagesAttachmentChange('cover_image',imageData);
    courseRegistrationDate ();
</script>

