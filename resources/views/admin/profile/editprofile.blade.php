<!-- Admin Edit Profile Page -->
@extends('layouts.admin.master')
@section('title')
  {{$title}}
@endsection
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Profile Personal Information-->
        
        <x-admins.flash-message></x-admins.flash-message>
        <div class="d-flex flex-row">
            <!--begin::Aside-->
            <x-admins.admin-profile-side-menu></x-admins.admin-profile-side-menu>
            <!--end::Aside-->
            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-8">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Update your personal informaiton</span>
                        </div>
                        <div class="card-toolbar">
                            <button type="submit" class="btn btn-success mr-2" form="edit-profile-form">Save Changes</button>
                            <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" id="edit-profile-form" action="{{ route('admin.updateprofile') }}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="image-input image-input-outline @if($errors->has('profile_img')) is-invalid @endif" id="kt_profile_avatar" style="background-image: url('{{asset('assets/media/users/blank.png')}}')">
                                        @if(isset(Auth::guard('admin')->user()->profile_img) && Storage::exists(Auth::guard('admin')->user()->profile_img))
                                            <div class="image-input-wrapper" style="background-image: url('{{storage::url(Auth::guard('admin')->user()->profile_img)}}')"></div>
                                        @else
                                            <div class="image-input-wrapper" style="background-image: url('{{asset('assets/media/users/default.jpg')}}')"></div>
                                        @endif

                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="profile_img" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                    </div>
                                    <span class="form-text text-muted">The image must be of file type: jpeg, png, jpg, gif, svg, and may not be greater than 2048 kilobytes.</span>
                                    @error('profile_img')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Name&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid @if($errors->has('name')) is-invalid @endif maxlength" type="text" name="name" value="{{ Auth::guard('admin')->user()->name }}"  maxlength="255" placeholder="Name"/>
                                    @error('name')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Username&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid @if($errors->has('username')) is-invalid @endif maxlength" name="username" type="text" value="{{ Auth::guard('admin')->user()->username }}" maxlength="255" placeholder="Username"/>
                                    @error('username')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid @if($errors->has('email')) is-invalid @endif">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-at"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg form-control-solid @if($errors->has('email')) is-invalid @endif maxlength" name="email" value="{{ Auth::guard('admin')->user()->email }}" placeholder="Email" maxlength="255"/>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
    <!--end::Container-->
</div>
<!--end::Content-->
@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function() {
    $(".maxlength").maxlength({
        alwaysShow: false,
        threshold: 10,
        warningClass: "label label-success label-rounded label-inline",
        limitReachedClass: "label label-danger label-rounded label-inline",
        separator: ' of ',
        preText: 'You have ',
        postText: ' chars remaining.',
        validate: true
    });

    // Mobile offcanvas for mobile mode
    new KTOffcanvas('kt_profile_aside', {
        overlay: true,
        baseClass: 'offcanvas-mobile',
        closeBy: 'kt_user_profile_aside_close',
        toggleBy: 'kt_subheader_mobile_toggle'
    }), new KTImageInput("kt_profile_avatar");
});

</script>
@endsection