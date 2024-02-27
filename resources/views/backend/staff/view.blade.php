<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">View Staff</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i
                                            class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade mt-2 show active" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <td><strong>Admin Name</strong></td>
                                                    <td>{{$data->admin_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Nick Name</strong></td>
                                                    <td>{{$data->nick_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Role</strong></td>
                                                    <td>{{$data->role->role_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email Id</strong></td>
                                                    <td>{{$data->email}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone</strong></td>
                                                    <td><span><span> {{ $data->phone }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Address</strong></td>
                                                    <td><span><span> {{ $data->address }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Staff Status</strong></td>
                                                    <td>{{displayStatus($data->status)}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Added At</strong></td>
                                                    <td>{{date('d-m-Y H:i A', strtotime($data->updated_at)) }}</td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
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
