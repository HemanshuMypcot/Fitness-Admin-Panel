<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Change Password</h5>
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
                                            <form method="post" id="changePwdForm" action="customer/changePassword?id={{$customer['id']}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>New Password<span class="text-danger">*</span></label>
                                                        <input class="form-control required" minlength="8" maxlength="28" type="password" name="new_password">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Confirm Password<span class="text-danger">*</span></label>
                                                        <input class="form-control required" minlength="8" maxlength="28" type="password" name="confirm_password">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="pull-right">
                                                        <button type="button" class="btn btn-success" onclick="submitForm('changePwdForm','post')">Reset Password</button>
                                                            <!-- <button type="submit" class="btn btn-success btn-sm">Reset Password</button> -->
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
                </div>
            </div>
        </div>
    </div>
</section>
