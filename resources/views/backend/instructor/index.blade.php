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
                                            <h5 class="pt-2">Manage Instructor List</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button class="btn btn-sm btn-outline-danger px-3 py-1 mr-2" id="listing-filter-toggle"><i class="fa fa-filter"></i> Filter</button>
                                            @if($data['instructor_add'])
                                                <a href="instructors/add" class="btn btn-sm btn-outline-primary px-3 py-1 src_data"><i class="fa fa-plus"></i> Add Instructor</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2" id="listing-filter-data" style="display: none;">
                                        <div class="col-md-4">
                                            <label>Instructor Name</label>
                                            <input class="form-control mb-3" type="text" id="search_name" name="search_name" >
                                        </div>
                                        <div class="col-md-4">
                                            <label>Specialist In</label>
                                            <select class="required select2 form-control" id="search_designation" name="search_designation" style="width: 100% !important;">
                                                <option value="">All</option>
                                                @foreach($data['course_categories'] as $course_cat)
                                                    <option value="{{$course_cat->id}}">{{$course_cat->category_name}}</option>
                                                @endforeach
                                            </select>
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
                                            <label>&nbsp;</label><br/>
                                            <input class="btn btn-md btn-primary px-3 py-1 mb-3" id="clear-form-data" type="reset" value="Clear Search">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="instructors/fetch">
                                            <thead>
                                                <tr>
                                                    <th class="sorting_disabled" id="id" data-orderable="false" data-searchable="false">Id</th>
                                                    <th id="name_{{\App::getLocale()}}" data-orderable="false" data-searchable="false">Instructor Name ({{ config('translatable.locales_name')[\App::getLocale()] }})</th>
                                                    <th id="specialist_in" data-orderable="false" data-searchable="false">Specialist In</th>
                                                    <th id="nick_name" data-orderable="false" data-searchable="false">Nick Name</th>
                                                    <th id="sequence" data-orderable="false" data-searchable="false">Sequence</th>
                                                    <th id="rating" data-orderable="false" data-searchable="false">Rating</th>
                                                    @if($data['instructor_status'] || $data['instructor_edit'] || $data['instructor_view'] || $data['instructor_delete'])
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
