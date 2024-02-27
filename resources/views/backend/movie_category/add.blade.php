<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Movie Category</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                    		<form id="savemoviecategory" method="post" action="movie_categories/save">
                    			@csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content">
                                            <div id="data_details" class="tab-pane fade in active show">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label>Category<span class="text-danger">*</span></label>
                                                        <select name="category" id="category" class="required select2 form-control">
                                                            <option value="" selected>Select Category</option>
                                                            <option value="Movie">Movie</option>
                                                            <option value="Web Series">Web Series</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Name<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="text" id="name" oninput="validateNameInput(this)" name="name" required><br/>
                                                    </div>
                                                    <div class="col-sm-6" id="season">
                                                        <label>SEASON<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" id="season1" name="season" required><br/>
                                                    </div>
                                                    <div class="col-sm-6" id="yt_link">
                                                        <label>YOUTUBE LINK<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="url" id="yt_link1" name="yt_link" ><br/>
                                                    </div>
                                                    <div class="col-sm-6" id="checklist_wrapper">
                                                        <label>Genre :<span class="text-danger">*</span></label>
                                                        <table class="">
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="actionCheckbox" name="action">
                                                                </td>
                                                                <td>
                                                                    <label for="comedyCheckbox">Action</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="ComedyCheckbox" name="comedy">
                                                                </td>
                                                                <td>
                                                                    <label for="ComedyCheckbox">Comedy</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="emotionalCheckbox" name="emotional">
                                                                </td>
                                                                <td>
                                                                    <label for="emotionalCheckbox">Emotional</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="ThrillerCheckbox" name="thriller">
                                                                </td>
                                                                <td>
                                                                    <label for="ThrillerCheckbox">Thriller</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="/-" name="romance">
                                                                </td>
                                                                <td>
                                                                    <label for="RomanceCheckbox">Romance</label>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-6 col-lg-12 col-sm-6 text-center file-input-div">

                                                            <label>Image<span class="text-danger">*</span></label>
                                                            <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_category_image_width')}}X{{config('global.dimensions.course_category_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                            <div class="shadow bg-white rounded d-inline-block mb-2">
                                                                <div class="input-file">
                                                                    <label class="label-input-file">Choose Files &nbsp;&nbsp;&nbsp;<i class="ft-upload font-medium-1"></i>
                                                                        <input type="file" name="image" class="cover_image required" id="cover_image" accept=".jpg, .jpeg, .png" onchange="handleFileInputChange('cover_image', 'image')">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        		<hr>
                        		<div class="row">
                        			<div class="col-sm-12">
                        				<div class="pull-right">
                        					<button type="submit" class="btn btn-success" onclick="submitForm('savemoviecategory','post')">Submit</button>
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
</script>
