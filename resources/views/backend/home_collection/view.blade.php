<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Home Collection: {{ $collection->translations[0]->title ?? '' }} ({{ config('translatable.locales_name')[\App::getLocale()] }})</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i
                                                class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <tr>
                                                                <td><strong>Collection Type</strong></td>
                                                                <td>{{\App\Models\HomeCollection::COLLECTION_TYPES[$collection->type]}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Sequence</strong></td>
                                                                <td>{{$collection->sequence ?? ''}}</td>
                                                            </tr>
{{--                                                            <tr>--}}
{{--                                                                <td><strong>Display In Column</strong></td>--}}
{{--                                                                <td>{{$collection->display_in_column ?? ''}}</td>--}}
{{--                                                            </tr>--}}
{{--                                                            <tr>--}}
{{--                                                                <td><strong>Is Scrollable</strong></td>--}}
{{--                                                                <td>{{$collection->is_scrollable ? 'Yes':'No'}}</td>--}}
{{--                                                            </tr>--}}
                                                            <tr>
                                                                <td><strong>Collection Status</strong></td>
                                                                <td>{{($collection->status) ? 'Active' : 'In-Active'}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach (config('translatable.locales') as $translated_data_tabs)
                                            <div id="{{ $translated_data_tabs }}_block_details" class="tab-pane fade">
                                                @php
                                                    $data  = $collection->translations()->where('locale',$translated_data_tabs)->first();
                                                @endphp
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered">
                                                                <tr>
                                                                    <td><strong> Title</strong></td>
                                                                    <td>{{ $data->title ?? '' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Description</strong></td>
                                                                    {{-- <td>{{ $data->description ?? '' }}</td> --}}
                                                                    <td><?php echo  $data->description ?? '' ?></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
