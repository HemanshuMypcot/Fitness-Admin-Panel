<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Instructor: {{$instructor['name']}} ({{ config('translatable.locales_name')[\App::getLocale()] }})</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{URL::previous()}}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
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
                                        <?php foreach (config('translatable.locales') as $translated_tabs) { ?>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#<?php echo $translated_tabs ?>_block_details">{{ config('translatable.locales_name')[$translated_tabs] }}</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="data_details" class="tab-pane fade in active show">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><strong>Instructor Since</strong></td>
                                                        <td>{{ $instructor->instructor_since->format('d-m-Y') ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Specialist In</strong></td>
                                                        <td>{{ $instructor->specialist->category_name ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nick  Name</strong></td>
                                                        <td>{{ $instructor->nick_name ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sequence</strong></td>
                                                        <td>{{ $instructor->sequence ?? ''}}</td>
                                                    </tr>
                                                    {{-- @if(isset($media))
                                                    <tr>
                                                        <td><strong>Instructor Image</strong></td>
                                                        <td><img src="{{$media ?? ''}}" width="150px" height="auto" alt=""></td>
                                                    </tr>
                                                    @endif --}}
                                                    <tr>
                                                        <td><strong>Rating</strong></td>
                                                        <td>{{ $instructor->rating ?? ''}}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php foreach (config('translatable.locales') as $translated_data_tabs) { ?>
                                            <div id="<?php echo $translated_data_tabs ?>_block_details" class="tab-pane fade">
                                                <div class="row">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered">
                                                            <?php foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value) { ?>
                                                                    <tr>
                                                                        <td><strong>{{ucfirst(str_replace('_',' ',$translated_block_fields_key))}}</strong></td>
                                                                        <td><?php echo $instructor[$translated_block_fields_key.'_'.$translated_data_tabs] ?? '' ?></td>
                                                                    </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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

