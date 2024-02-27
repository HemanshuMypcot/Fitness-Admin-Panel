<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Course : {{ $course['course_name'] }}
                                        ({{ config('translatable.locales_name')[\App::getLocale()] }}) - {{$course['sku_code']}}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab"
                                                href="#data_details">Details</a>
                                        </li>
                                        <?php foreach (config('translatable.locales') as $translated_tabs) { ?>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab"
                                                href="#<?php echo $translated_tabs; ?>_block_details">{{ config('translatable.locales_name')[$translated_tabs] }}</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="data_details" class="tab-pane fade in active show">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered">
                                                    <tr>
                                                        <td><strong>Course Category</strong></td>
                                                        <td>{{ $course->category_name ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Course Instructor</strong></td>
                                                        <td>{{ $course->instructor->name ?? '' }}</td>
                                                    </tr>
                                                    @if(isset($media))
                                                    <tr>
                                                        <td><strong>Course Image</strong></td>
                                                        <td><img src="{{$media ?? ''}}" width="150px" height="auto" alt=""></td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td><strong>Start Date</strong></td>
                                                        <td>{{ $course->date_start->format('d-m-Y') ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>End Date</strong></td>
                                                        <td>{{ $course->date_end->format('d-m-Y') ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Start Time</strong></td>
                                                        <td>{{date('h:i a', strtotime( $timeStart ?? '')) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>End Time</strong></td>
                                                        <td>{{date('h:i a', strtotime( $timeEnd ?? '')) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Seats</strong></td>
                                                        <td>{{ $course->capacity ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Sequence</strong></td>
                                                        <td>{{ $course->sequence ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Amount</strong></td>
                                                        <td>{{ $course->amount ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tax (In Percentage)</strong></td>
                                                        <td>{{ $course->tax ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Service Charge (In Percentage)</strong></td>
                                                        <td>{{ $course->service_charge ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Classes On Every</strong></td>
                                                        <td>
                                                            <!-- @foreach (($course->opens_at) as $day => $status)
                                                                @if ($status === 'on')
                                                                {{ ucfirst($day) }},
                                                                @endif
                                                            @endforeach -->
                                                            <?php
                                                            echo implode(', ', array_keys($course->opens_at, "on" ));
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Registration Start Date</strong></td>
                                                        <td>{{ $course->registration_start ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Registration End Date</strong></td>
                                                        <td>{{ $course->registration_end ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Frequency</strong></td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $course->type)) ?? '' }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <?php foreach (config('translatable.locales') as $translated_data_tabs) { ?>
                                        <div id="<?php echo $translated_data_tabs; ?>_block_details" class="tab-pane fade">
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <?php foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value) { ?>
                                                        <tr>
                                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $translated_block_fields_key)) }}</strong>
                                                            </td>
                                                            <td><?php echo $course[$translated_block_fields_key . '_' . $translated_data_tabs] ?? ''; ?></td>
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
