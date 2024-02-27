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
                                    <div class="row">
                                        <div class="col-12 col-sm-7">
                                            <h5 class="pt-2">Manage Course List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['course_add'])
                                                <a href="course/add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Course</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Course Name</label>
                                            <input class="form-control mb-3" type="text" id="search_name" name="search_name" >
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Course Category<span class="text-danger">*</span></label>
                                            <select class="required form-control select2" id="search_category" name="search_category" style="width: 100% !important;">
                                                <option value="">All</option>
                                                @foreach($data['course_categories'] as $course_cat)
                                                    <option value="{{$course_cat->id}}">{{$course_cat->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Frequency<span class="text-danger">*</span></label>
                                            <select class="required form-control select2" id="search_frequency" name="search_frequency" style="width: 100% !important;">
                                                <option value="">All</option>
                                                @foreach($data['course_frequency'] as $freq)
                                                    <option value="{{$freq}}">{{ ucwords(str_replace('_', ' ', $freq)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Start Date</label>
                                            <input class="form-control mb-3 date_change_inp flatpickr" type="text" id="search_start_date" name="search_start_date" >
                                        </div>
                                        <div class="col-md-4">
                                            <label>Status</label>
                                            <select class="form-control mb-3 select2" type="text" id="search_status" name="search_status" style="width: 100% !important;">
                                                <option value="">All</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Course Status</label>
                                            <select class="form-control mb-3 select2" type="text" id="search_course_status" name="search_course_status" style="width: 100% !important;">
                                                <option value="">Active</option>
                                                <option value="0">Expired</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="course/fetch">
                                            <thead>
                                                <tr>
                                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="course_name_{{\App::getLocale()}}" data-orderable="false" data-searchable="false">Course Name ({{ config('translatable.locales_name')[\App::getLocale()] }})</th>
                                                    <th id="type" data-orderable="false" data-searchable="false">Frequency</th>
                                                    <th id="capacity" data-orderable="false" data-searchable="false">Seats</th>
                                                    <th id="date_start" data-orderable="false" data-searchable="false">Start Date</th>
                                                    @if($data['course_status'] || $data['course_edit'] || $data['course_view'] || $data['course_delete'])
                                                        <th id="action" data-orderable="false" data-searchable="false" width="130px">Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
