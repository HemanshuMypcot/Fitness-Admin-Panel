<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Movie Category: </h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body">
                            {{-- <form id="editCourseCategoryForm" method="post" action="course_categories/update?id={{$course_category['id']}}">
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
                                                        <label>Nick Name<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="text" id="nick_name" oninput="validateNameInput(this)" name="nick_name" maxlength="3" value="{{$course_category->nick_name ?? ''}}"><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Sequence<span class="text-danger">*</span></label>
                                                        <input class="form-control required  integer-validation" type="number" id="sequence" name="sequence" value="{{$course_category->sequence}}" ><br/>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="col-md-6 col-lg-12 col-sm-6 text-center file-input-div">
                                                            <label>Image <span class="text-danger">*</span></label><br>
                                                            <div class="shadow bg-white rounded d-inline-block mb-2">
                                                                <div class="input-file">
                                                                    <label class="label-input-file">Choose Files <i class="ft-upload font-medium-1"></i>
                                                                        <input class="form-control" accept=".jpg,.jpeg,.png" type="file" id="cover_image" name="image" onchange="handleFileInputChange('cover_image')"><br/>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <p id="files-area">
                                                                <span id="cover_image_list">
                                                                    <span id="cover_image_name"></span>
                                                                </span>
                                                            </p>
                                                        </div>
                                                        
                                                        <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_category_image_width')}}X{{config('global.dimensions.course_category_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
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
                                            <button type="button" class="btn btn-success" onclick="submitForm('editCourseCategoryForm','post')">Submit</button>
                                            <a href="{{URL::previous()}}" class="btn btn-danger px-3 py-1">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form> --}}
                            <form id="savemoviecategory" method="post" action="movie_categories/update?id={{$movie_category['id']}}">
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
                                                            <option value="Movie" @if ($movie_category['category'] == "Movie") selected
                                                                @endif>Movie</option>
                                                            <option value="Web Series" @if ($movie_category['category'] == "Web Series") selected
                                                            @endif>Web Series</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Name<span class="text-danger">*</span></label>
                                                        <input class="form-control required" type="text" id="name" oninput="validateNameInput(this)" name="name" value="{{$movie_category->name ?? ''}}"><br/>
                                                    </div>
                       
                                                        <div class="col-sm-6 {{ $movie_category['category']=="Movie" ? 'd-none' : '' }}" id="season">
                                                            <label>SEASON<span class="text-danger">*</span></label>
                                                            <input class="form-control required integer-validation" type="number" id="season1" name="season" value="{{$movie_category->season ?? ''}}"><br/>
                                                        </div>
                                                        <div class="col-sm-6 {{ $movie_category['category']=="Web Series" ? 'd-none' : '' }}" id="yt_link">
                                                            <label>YOUTUBE LINK<span class="text-danger">*</span></label>
                                                            <input class="form-control required" type="url" id="yt_link1" name="yt_link" value="{{$movie_category->yt_link ?? ''}}"><br/>
                                                        </div>
                                                    <div class="col-sm-6" id="checklist_wrapper">
                                                        <label>Genre :<span class="text-danger">*</span></label>
                                                        @php
                                                                $opensAtData = json_decode($movie_category['genre'], true);
                                                            @endphp
                                                        <table class="">
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="actionCheckbox" name="action" @if ($opensAtData['Action'] === 'on') checked @endif>
                                                                </td>
                                                                <td>
                                                                    <label for="comedyCheckbox">Action</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="ComedyCheckbox" name="comedy" @if ($opensAtData['Comedy'] === 'on') checked @endif>
                                                                </td>
                                                                <td>
                                                                    <label for="ComedyCheckbox">Comedy</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="emotionalCheckbox" name="emotional" @if ($opensAtData['Emotional'] === 'on') checked @endif>
                                                                </td>
                                                                <td>
                                                                    <label for="emotionalCheckbox">Emotional</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="ThrillerCheckbox" name="thriller" @if ($opensAtData['Thriller'] === 'on') checked @endif>
                                                                </td>
                                                                <td>
                                                                    <label for="ThrillerCheckbox">Thriller</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left: 20px" class="toggle-button text-center">
                                                                    <input type="checkbox" id="/-" name="romance" @if ($opensAtData['Romance'] === 'on') checked @endif>
                                                                </td>
                                                                <td>
                                                                    <label for="RomanceCheckbox">Romance</label>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-6 col-lg-12 col-sm-6 text-center file-input-div">

                                                            {{-- <label>Image<span class="text-danger">*</span></label> --}}
                                                            
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

                                                        @if(!empty($media_file_name))
                                                            <div class="d-flex mb-1  media-div-{{$media_id}}" style="width: 100%;">
                                                                <input type="text"
                                                                        class="form-control bg-white document-border"
                                                                        value="{{ $media_file_name ?? '' }}"
                                                                        readonly style="color: black !important;">
                                                                <a href="{{ $media_image }}"
                                                                    class="btn btn-primary mx-2 px-2" target="_blank"><i
                                                                            class="fa ft-eye"></i></a>
                                                            </div>
                                                        @endif
                                                        <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_category_image_width')}}X{{config('global.dimensions.course_category_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
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
                        					<button type="button" class="btn btn-success" onclick="submitForm('savemoviecategory','post')">Submit</button>
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
