@extends('backend.layouts.app')
@section('content')
<div class="main-content">
    <div class="content-overlay"></div>
        <div class="content-wrapper">
            <section class="users-list-wrapper">
                <div class="users-list-table">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-header">
                                        <h4 class="card-title text-center">Terms and Condition</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="editTerms" method="post" action="terms/update">
                                            @csrf
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <ul class="nav nav-tabs">
                                                        <?php
                                                            $i=0;
                                                            foreach (config('translatable.locales') as $translated_tabs) {
                                                                $sel = '';
                                                                if($i == 0) {
                                                                    $sel = ' active';
                                                                }
                                                        ?>
                                                            <li class="nav-item">
                                                                <a class="nav-link<?php echo $sel ?>" data-toggle="tab" href="#<?php echo $translated_tabs ?>_block_details">{{ config('translatable.locales_name')[$translated_tabs] }}</a>
                                                            </li>
                                                        <?php $i++; } ?>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <?php
                                                        $i=0;
                                                        foreach (config('translatable.locales') as $translated_data_tabs) {
                                                            $sel = '';
                                                            if($i == 0) {
                                                                $sel = ' in active show';
                                                            }
                                                        ?>
                                                            <div id="<?php echo $translated_data_tabs ?>_block_details" class="tab-pane fade<?php echo $sel ?>">
                                                                <div class="row">
                                                                    <?php foreach ($translated_block as $translated_block_fields_key => $translated_block_fields_value) { ?>
                                                                        <div class="col-md-12 mb-3">
                                                                            <label>{{$translated_block_fields_key}}</label>
                                                                            <textarea class="translation_block form-control required" type="text" id="{{$translated_block_fields_key}}_{{$translated_data_tabs}}" name="{{$translated_block_fields_key}}_{{$translated_data_tabs}}">{{$data[$translated_block_fields_key.'_'.$translated_data_tabs] ?? ''}}</textarea>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        <?php $i++; } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="pull-right">
                                                        <button type="button" class="btn btn-success" onclick="submitEditor('editTerms','post')">Save Changes</button>
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
        </div>
    </div>
    <script src="../backend/vendors/ckeditor5/ckeditor.js"></script>
    <script>
        $(document).ready(function () {
            var locales = JSON.parse('<?php echo json_encode(config('translatable.locales')) ?>');
            for(i=0; i < locales.length; i++) {
                loadCKEditor('content_'+locales[i]);
            }
        });
    </script>
@endsection
