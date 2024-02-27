<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Course Category: {{$course_category['category_name']}} ({{ config('translatable.locales_name')[\App::getLocale()] }})</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="editMovieCategoryForm" method="post" action="movie_categories/update?id={{$course_category['id']}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
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
                                                        <p style="color:blue;">Note : Upload file size {{config('global.dimensions.course_category_image_width')}}X{{config('global.dimensions.course_category_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php foreach (config('translatable.locales') as $translated_data_tabs) { ?>
                                                <div id="<?php echo $translated_data_tabs ?>_block_details" class="tab-pane fade">
                                                    <div class="row">
                                                        <?php foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value) {
                                                         ?>

                                                            <?php if($translated_block_fields_value == 'input') { ?>
                                                                <div class="col-md-6 mb-3">
                                                                    <label>{{str_replace('_',' ',$translated_block_fields_key)}}<span class="text-danger">*</span></label>
                                                                    <input class="translation_block form-control required" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" value="{{$course_category[$translated_block_fields_key.'_'.$translated_data_tabs] ?? ''}}">
                                                                </div>
                                                            <?php
                                                        } ?>
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
                                            <button type="button" class="btn btn-success" onclick="submitForm('editMovieCategoryForm','post')">Submit</button>
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
