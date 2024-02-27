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
                                    <h4 class="card-title text-center">Manage General Settings</h4>
                                </div>
                                <!-- <hr class="mb-0"> -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mt-3">
                                            <!-- Nav tabs -->
                                            <ul class="nav flex-column nav-pills" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                                                        <i class="ft-settings mr-1 align-middle"></i>
                                                        <span class="align-middle">General</span>
                                                    </a>
                                                </li>
                                                {{-- <li class="nav-item">
                                                    <a class="nav-link" id="about_us-tab" data-toggle="tab" href="#about_us" role="tab" aria-controls="about_us" aria-selected="false">
                                                        <i class="ft-info mr-1 align-middle"></i>
                                                        <span class="align-middle">About Us</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="false">
                                                        <i class="ft-command mr-1 align-middle"></i>
                                                        <span class="align-middle">Terms and Condition</span>
                                                    </a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link" id="privacy-tab" data-toggle="tab" href="#privacy" role="tab" aria-controls="privacy" aria-selected="false">
                                                        <i class="ft-globe mr-1 align-middle"></i>
                                                        <span class="align-middle">Privacy Policy</span>
                                                    </a>
                                                </li> --}}
                                                {{-- <li class="nav-item">
                                                    <a class="nav-link" id="social-links-tab" data-toggle="tab" href="#social-links" role="tab" aria-controls="social-links" aria-selected="false">
                                                        <i class="ft-twitter mr-1 align-middle"></i>
                                                        <span class="align-middle">Social Links</span>
                                                    </a>
                                                </li> --}}
                                                <li class="nav-item">
                                                    <a class="nav-link" id="app_link-tab" data-toggle="tab" href="#app_link" role="tab" aria-controls="app_link" aria-selected="false">
                                                        <i class="ft-link mr-1 align-middle"></i>
                                                        <span class="align-middle">App Link</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="app_version-tab" data-toggle="tab" href="#app_version" role="tab" aria-controls="app_version" aria-selected="false">
                                                        <i class="ft-play mr-1 align-middle"></i>
                                                        <span class="align-middle">App Version</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-9">
                                            <!-- Tab panes -->
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body pt-0">
                                                        <div class="tab-content">
                                                            <!-- General Tab -->
                                                            <div class="tab-pane active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                                <form id="generalForm" method="post" action="updateSettingInfo?param=general">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="system_email">System E-mail<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="email" id="system_email" name="system_email" class="form-control required" placeholder="E-mail" value="{{$data['system_email']}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="system_contact_no">System Contact No<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="text" id="system_contact_no" maxlength="14" name="system_contact_no" class="form-control required " placeholder="+91 0000000000" oninput="this.value = this.value.replace(/[^0-9\s+\s-]/g, '')" value="{{$data['system_contact_no']}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-success mb-1" onclick="submitForm('generalForm','post')">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- App Link Tab -->
                                                            <div class="tab-pane" id="app_link" role="tabpanel" aria-labelledby="app_link-tab">
                                                                <form id="appLinkForm" method="post" action="updateSettingInfo?param=appLink">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="android_url">Android<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="text" id="android_url" name="android_url" class="form-control required" placeholder="" value="{{$data['android_url']}}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="ios_url">IOS<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="text" id="ios_url" name="ios_url"  class="form-control required" placeholder="" aria-invalid="false" value="{{$data['ios_url']}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-success mb-1" onclick="submitForm('appLinkForm','post')">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                             <!-- App Version Tab -->
                                                            <div class="tab-pane" id="app_version" role="tabpanel" aria-labelledby="app_version-tab">
                                                                <form id="appVersionForm" method="post" action="updateSettingInfo?param=appVersion">
                                                                @csrf
                                                                    <div class="row">
                                                                        <div class="col-12 form-group">
                                                                            <label for="android_version">Android (Format-> ["va1","val2","val3"])<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="text" id="android_version" name="android_version" class="form-control required" placeholder="" value="{{$data['android_version']}}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 form-group">
                                                                            <label for="ios_version">IOS (Format-> ["va1","val2","val3"])<span class="text-danger">*</span></label>
                                                                            <div class="controls">
                                                                                <input type="text" id="ios_version" name="ios_version"  class="form-control required" placeholder="" aria-invalid="false" value="{{$data['ios_version']}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                                            <button type="button" class="btn btn-success mb-1" onclick="submitForm('appVersionForm','post')">Save Changes</button>
                                                                            {{-- <button type="reset" class="btn btn-danger mb-1">Cancel</button> --}}
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
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
