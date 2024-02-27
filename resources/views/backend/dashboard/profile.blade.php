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
                                            <h4 class="card-title">General Info</h4>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <button id="editProfileBtn" class="btn btn-sm btn-primary px-3 py-1" onclick="toggleEditView()"><i class="fa fa-edit"></i> Edit Profile</button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            	<div class="card-body">
                            		<form method="post" id="profileForm" action="updateProfile">
                                        @csrf
                                        <div class="row">
                                            <dl class="col-sm-4">
                                                <label>Email</label>
                                                <dd class="form-control" readonly>{{ $data->email }}</dd>
                                            </dl>
                                            <div class="col-md-4">
                                                <label>Name<span class="text-danger">*</span></label>
                                                <input class="form-control" maxlength="52" type="text" name="admin_name" value="{{$data->admin_name}}"><br/>
                                            </div>
                                            <dl class="col-sm-4">
                                                <label>Nick Name</label>
                                                <dd class="form-control" readonly>{{ $data->nick_name }}</dd>
                                            </dl>
                                            <dl class="col-sm-4">
                                                <label>Role</label>
                                                <dd class="form-control" readonly>{{ $data->role->role_name }}</dd>
                                            </dl>
                                            <div class="col-md-4">
                                                <label>Contact Number<span class="text-danger">*</span></label>
                                                <input class="form-control" maxlength="10" type="text" name="phone" value="{{$data->phone}}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"><br/>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="pull-left">
                                                    @if ($message = Session::get('success'))
                                                        <div class="successAlert alert text-success">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="pull-right">
                                                    <button type="button" id="updateButtonDiv" class="btn btn-success" onclick="submitForm('profileForm', 'post')" disabled>Update</button>
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
@endsection
