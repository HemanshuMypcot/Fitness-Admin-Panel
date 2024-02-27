<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Course</h5>
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
                                                        <input class="form-control required integer-validation" type="number" pattern="[0-9]*" oninput="validateInteger(event)"  id="sequence" name="sequence"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="type">Frequency<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="course_type" name="type" style="width: 100% !important;">
                                                            <option value="">Choose Course Type</option>
                                                            <option value="one_day">One Day</option>
                                                            <option value="recurring">Recurring</option>
                                                        </select><br/>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label>Course Category<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="course_category_id" name="course_category_id" style="width: 100% !important;">
                                                        <option value="">Choose Course Category</option>
                                                        @foreach($course_categories as $course_cat)
                                                                <option value="{{$course_cat->id}}">{{$course_cat->category_name}}</option>
                                                        @endforeach
                                                        </select><br/>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label>Course Instructor<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="instructor_id" name="instructor_id" style="width: 100% !important;">
                                                            <option value="">Choose Course Instructor</option>
                                                        </select><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course Start Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="text" id="course_start_date" name="date_start"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="course-end-date-div">
                                                            <label>Course End Date<span class="text-danger">*</span></label>
                                                            <input class="form-control required" type="text" id="course_end_date" name="date_end"><br/>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course Start Time<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="time" id="course_start_time" name="time_start"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Course End Time<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="time" id="course_end_time" name="time_end"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Registration Start Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required flatpickr" type="text" id="course_registration_start_date" name="registration_start"><br/>
                                                    </div>
                                                    <br>
                                                    <div class="col-sm-6">
                                                        <label>Registration End Date<span class="text-danger">*</span></label>
                                                        <input class="form-control required flatpickr" type="text" id="course_registration_end_date" name="registration_end"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Seats<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" maxlength="5" pattern="[0-9]*" oninput="validateInteger(event)"  id="capacity" name="capacity"><br/>
                                                        <div class="row">
                                                            <div class="col-md-6 col-lg-12 col-sm-6 text-center file-input-div">

                                                                <label>Image<span class="text-danger">*</span></label>
                                                                <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_image_width')}}X{{config('global.dimensions.course_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                                <div class="shadow bg-white rounded d-inline-block mb-2">
                                                                    <div class="input-file">
                                                                        <label class="label-input-file">Choose Files &nbsp;&nbsp;&nbsp;<i class="ft-upload font-medium-1"></i>
                                                                            <input type="file" name="image" class="cover_image required" id="cover_image" accept=".jpg, .jpeg, .png" onchange="handleFileInputChange('coverImages', 'image')">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <p id="files-area">
                                                                    <span id="cover_image_list">
                                                                        <span id="cover_image_name"></span>
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label>Location<span class="text-danger">*</span></label>
                                                        <select class="required select2 form-control" id="location_id" name="location_id" style="width: 100% !important;">
                                                        <option value="">Choose Course Location</option>
                                                        @foreach($location as $location)
                                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                                        @endforeach
                                                        </select>
                                                        <div class="mt-3"></div>
                                                    <div class="col-sm-6" id="checklist_wrapper">
                                                        <label>Classes On Every :<span class="text-danger">*</span></label>
                                                        <table class="">
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="mondayCheckbox" name="monday">
                                                                </td>
                                                                <td>
                                                                    <label for="mondayCheckbox">Monday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="tuesdayCheckbox" name="tuesday">
                                                                </td>
                                                                <td>
                                                                    <label for="tuesdayCheckbox">Tuesday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="wednesdayCheckbox" name="wednesday">
                                                                </td>
                                                                <td>
                                                                    <label for="wednesdayCheckbox">Wednesday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="thursdayCheckbox" name="thursday">
                                                                </td>
                                                                <td>
                                                                    <label for="thursdayCheckbox">Thursday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="fridayCheckbox" name="friday">
                                                                </td>
                                                                <td>
                                                                    <label for="fridayCheckbox">Friday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="saturdayCheckbox" name="saturday">
                                                                </td>
                                                                <td>
                                                                    <label for="saturdayCheckbox">Saturday</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="sundayCheckbox" name="sunday">
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
                                                        <input class="form-control required allow_numeric" type="number" id="amount" name="amount"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Tax (In Percentage)<span class="text-danger">*</span></label>
                                                        <input class="form-control required allow_numeric" type="number" id="tax" name="tax"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Service Charge (In Percentage)<span class="text-danger">*</span></label>
                                                        <input class="form-control required allow_numeric" type="number" id="service_charge" name="service_charge"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Discount (In Percentage)</label>
                                                        <input class="form-control allow_numeric" type="number" id="discount" name="discount"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Payable Amount<span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" id="total" name="total" readonly><br/>
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
                                                                    <input class="translation_block form-control required" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}">
                                                                </div>
                                                            <?php } ?>

                                                            <?php if($translated_block_fields_value == 'textarea') { ?>
                                                                <div class="col-md-6 mb-3">
                                                                    @if( formatName($translated_block_fields_key) == 'description')
                                                                        <label>Long Description<span class="text-danger">*</span></label>
                                                                    @else
                                                                        <label>{{formatName(str_replace('_',' ',$translated_block_fields_key))}}<span class="text-danger">*</span></label>
                                                                    @endif
                                                                    <textarea class="translation_block form-control required" rows="7" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}"></textarea>
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
