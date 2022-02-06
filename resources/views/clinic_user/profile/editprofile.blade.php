<!-- Admin Edit Profile Page -->
@extends('layouts.app')
@section('title')
  {{$title}}
@endsection
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Profile Personal Information-->

        <x-flash-message></x-flash-message>
        <div class="d-flex flex-row">
            <!--begin::Aside-->
            <x-profile-side-menu></x-profile-side-menu>
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
                            <a href="{{route('dashboard')}}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" id="edit-profile-form" action="{{ route('updateprofile') }}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Provider Name&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid @if($errors->has('provider_name')) is-invalid @endif maxlength" name="provider_name" type="text" value="{{ Auth::user()->provider_name }}" maxlength="255" placeholder="Provider Name"/>
                                    @error('provider_name')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Full Name&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid @if($errors->has('name')) is-invalid @endif maxlength" type="text" name="name" value="{{ Auth::user()->name }}"  maxlength="255" placeholder="Full Name"/>
                                    @error('name')
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
                                        <input type="text" class="form-control form-control-lg form-control-solid @if($errors->has('email')) is-invalid @endif maxlength" name="email" value="{{ Auth::user()->email }}" placeholder="Email Address" maxlength="255"/>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Mobile&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid @if($errors->has('mobile')) is-invalid @endif maxlength" type="text" name="mobile" value="{{ Auth::user()->mobile }}"  maxlength="255" placeholder="Mobile"/>
                                    @error('mobile')
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