<section class="users-list-wrapper">
	<div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Home Collection:
                                        {{$collection['title']}} ({{ config('translatable.locales_name')[\App::getLocale()] }})</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    	<div class="card-body">
                            <form id="updateHomeCollection" method="post" action="home_collection/update">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#data_details">Details</a>
                                            </li>
                                            @foreach (config('translatable.locales') as $translated_tabs)
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#{{ $translated_tabs }}_block_details">{{ config('translatable.locales_name')[$translated_tabs] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            <div id="data_details" class="tab-pane fade in active show">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-2">
                                                        <label>Collection Type<span class="text-danger">*</span></label>
                                                        <select class="form-control select2 required" id="collection_type" name="collection_type">
                                                            <option value="{{$collection['type']}}" >{{\App\Models\HomeCollection::COLLECTION_TYPES[$collection['type']]}}</option>
                                                        </select>
                                                        <input type="hidden" value="{{$collection['id']}}" name="id">
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <label>Sequence<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" id="sequence" name="sequence" value="{{$collection['sequence'] ?? ''}}"><br/>
                                                    </div>
{{--                                                    <div class="col-sm-6 mb-2">--}}
{{--                                                        <label>Display in columns<span class="text-danger">*</span></label>--}}
{{--                                                        <select class="form-control select2 required" id="display_in_column" name="display_in_column">--}}
{{--                                                            @foreach(\App\Models\HomeCollection::DISPLAY_IN_COLUMN as $key => $type)--}}
{{--                                                                <option value="{{$type}}" {{( $collection['display_in_column'] == $type) ? 'selected' :''}}>{{$type}}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select><br/>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-sm-6 mb-4 mt-4">--}}
{{--                                                        <div class="custom-switch">--}}
{{--                                                            <input type="checkbox" class="custom-control-input" id="is_scrollable" name="is_scrollable" {{($collection['is_scrollable'] == 1) ? 'checked' : ''}}>--}}
{{--                                                            <label class="custom-control-label" for="is_scrollable">Is Scrollable</label>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                </div>
                                                @if($collection['type'] == \App\Models\HomeCollection::SINGLE)
                                                    <div class="row div_single">
                                                        <div class="col-sm-6 mb-2">
                                                            <label>Image</label>
                                                            <p style="color:blue;">Note : Upload file size {{config('global.dimensions.home_collection_image_width')}}X{{config('global.dimensions.home_collection_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                            <input type="file" class=" form-control" id="single_image" name="single_image" accept=".jpg,.jpeg,.png"><br/>
                                                            @foreach($singleImage as $image)
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
                                                @endif
                                                @if($collection['type'] == \App\Models\HomeCollection::COURSE)
                                                    <div class="row div_course">
                                                        <div class="col-sm-6 mb-2">
                                                            <label>Course Start Time<span class="text-danger">*</span></label>
                                                            <input class="form-control required" type="time" id="course_start_time" name="time_start" value="{{ date('H:i', strtotime($collection['time_start'])) }}"><br/>
                                                        </div>
                                                        <div class="col-sm-6 mb-2">
                                                            <label>Course End Time<span class="text-danger">*</span></label>
                                                            <input class="form-control required" type="time" id="course_end_time" name="time_end" value="{{ date('H:i', strtotime($collection['time_end'])) }}"><br/>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            @foreach (config('translatable.locales') as $translated_data_tabs)
                                                <div id="{{ $translated_data_tabs }}_block_details" class="tab-pane fade">
                                                    <div class="row">
                                                        @foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value)
                                                            <div class="col-md-6 mb-3">
                                                                @if ($translated_block_fields_value == 'input')
                                                                    <label>{{ $translated_block_fields_key }}<span class="text-danger">*</span></label>
                                                                    <input class="translation_block form-control required" type="text" id="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}" name="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}" value="{{ $collection[$translated_block_fields_key.'_'.$translated_data_tabs] ?? '' }}">
                                                                @elseif ($translated_block_fields_value == 'textarea')
                                                                    <label>{{ $translated_block_fields_key }}</label>
                                                                    <textarea class="translation_block form-control required" rows="7" id="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}" name="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}">{{ $collection[$translated_block_fields_key.'_'.$translated_data_tabs] ?? '' }}</textarea>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('updateHomeCollection','post')">Submit</button>
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
