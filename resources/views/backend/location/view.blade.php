<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Location: {{$location['name']}} ({{ config('translatable.locales_name')[\App::getLocale()] }})</h5>
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
                                                        <td><strong>Google Address</strong></td>
                                                        <td>{{$location['google_address']}}</td>
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
                                                                    @if ($translated_block_fields_key == 'title')
                                                                        <tr>
                                                                            <td><strong>Address</strong></td>
                                                                            <td><?php echo $location[$translated_block_fields_key.'_'.$translated_data_tabs] ?? '' ?></td>
                                                                        </tr>
                                                                    @elseif ($translated_block_fields_key == 'name')
                                                                        <tr>
                                                                            <td><strong>Title</strong></td>
                                                                            <td><?php echo $location[$translated_block_fields_key.'_'.$translated_data_tabs] ?? '' ?></td>
                                                                        </tr>
                                                                    @endif
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
