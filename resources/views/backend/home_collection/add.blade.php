<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Add Home Collection</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="addHomeCollection" method="post" action="home_collection/save" enctype="multipart/form-data">
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
                                                            @foreach($collection_types as $key => $type)
                                                                <option value="{{$key}}">{{\App\Models\HomeCollection::COLLECTION_TYPES[$key]}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <label>Sequence<span class="text-danger">*</span></label>
                                                        <input class="form-control required integer-validation" type="number" id="sequence" name="sequence"><br/>
                                                    </div>
{{--                                                    <div class="col-sm-6 mb-2">--}}
{{--                                                        <label>Display in columns<span class="text-danger">*</span></label>--}}
{{--                                                        <select class="form-control select2 required display_in_column" id="display_in_column" name="display_in_column">--}}
{{--                                                            @foreach(\App\Models\HomeCollection::DISPLAY_IN_COLUMN as $key => $type)--}}
{{--                                                                <option value="{{$type}}">{{$type}}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select><br/>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-sm-6 mb-4 mt-4">--}}
{{--                                                        <div class="custom-switch">--}}
{{--                                                            <input type="checkbox" class="custom-control-input" id="is_scrollable" name="is_scrollable">--}}
{{--                                                            <label class="custom-control-label" for="is_scrollable">Is Scrollable</label>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                </div>
                                                <div class="row div_single">
                                                    <div class="col-sm-6 mb-2">
                                                        <label>Image <span class="text-danger">*</span></label>
                                                        <p style="color:blue;">Note : Upload file size {{config('global.dimensions.home_collection_image_width')}}X{{config('global.dimensions.home_collection_image_height')}} pixel and .jpg, .png, or jpeg format image</p>
                                                        <input type="file" class=" form-control required single-type-filed" id="single_image" name="single_image" accept=".jpg,.jpeg,.png"><br/>
                                                    </div>
                                                </div>
                                                <div class="row div_course">
                                                    <div class="col-sm-6 mb-2">
                                                        <label>Start Time<span class="text-danger">*</span></label>
                                                        <input class="form-control start-time" type="time" id="start_time" name="time_start"><br/>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <label>End Time<span class="text-danger">*</span></label>
                                                        <input class="form-control end-time" type="time" id="end_time" name="time_end"><br/>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach (config('translatable.locales') as $translated_data_tabs)
                                                <div id="{{ $translated_data_tabs }}_block_details" class="tab-pane fade">
                                                    <div class="row">
                                                        @foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value)
                                                            <div class="col-md-6 mb-3">
                                                                @if ($translated_block_fields_value == 'input')
                                                                    <label>{{ $translated_block_fields_key }}<span class="text-danger">*</span></label>
                                                                    <input class="translation_block form-control required" type="text" id="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}" name="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}">
                                                                @endif
                                                                @if ($translated_block_fields_value == 'textarea')
                                                                    <label>{{ $translated_block_fields_key }}<span class="text-danger">*</span></label>
                                                                    <textarea class="translation_block form-control required" rows="7" type="text" id="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}" name="{{ $translated_block_fields_key }}_{{ $translated_data_tabs }}"></textarea>
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
                                            <button type="button" class="btn btn-success" onclick="submitForm('addHomeCollection','post')">Submit</button>
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
    AddHomeCollectionFunctionality();
</script>
