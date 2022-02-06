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
                <!--begin::Form-->
                <form class="form" action="{{ route('updatechangepass') }}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <!--begin::Card-->
                    <div class="card card-custom card-stretch">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
                            </div>
                            <div class="card-toolbar">
                                <button type="Submit" class="btn btn-success mr-2">Save Changes</button>
                                <a href="{{route('dashboard')}}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                        <!--end::Header-->
                    
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Current Password&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg form-control-solid {{ $errors->has('currentpass') ? 'is-invalid' : '' }} maxlength" maxlength="255" placeholder="Current password" name="currentpass">
                                        <div class="input-group-append showOrHide" style="cursor: pointer;">
                                            <span class="input-group-text">
                                              <i class="icon-xl far fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('currentpass')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">New Password&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg form-control-solid {{ $errors->has('newpass') ? 'is-invalid' : '' }} maxlength" maxlength="255" value="" placeholder="New password" name="newpass">
                                        <div class="input-group-append showOrHide" style="cursor: pointer;">
                                            <span class="input-group-text">
                                              <i class="icon-xl far fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('newpass')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Verify Password&nbsp;<span class="text-danger font-weight-bold">*</span></label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg form-control-solid {{ $errors->has('newpass_confirmation') ? 'is-invalid' : '' }} maxlength" maxlength="255" value="" placeholder="Verify password" name="newpass_confirmation">
                                        <div class="input-group-append showOrHide" style="cursor: pointer;">
                                            <span class="input-group-text">
                                              <i class="icon-xl far fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('newpass_confirmation')
                                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->

                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
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
    }),

    $('.showOrHide').click(function(e){
        var target = e.currentTarget
        $(target).hasClass('show')?hidePassword($(target)):showPassword($(target))
    });

});

function hidePassword(e){
      
    e.removeClass('show').addClass('hide');
    e.prev('input').attr('type','password');
    e.find('.icon-xl').removeClass('fa-eye-slash');
    e.find('.icon-xl').addClass('fa-eye');
}

function showPassword(e){
    e.removeClass('hide').addClass('show');
    e.prev('input').attr('type','text');
    e.find('.icon-xl').removeClass('fa-eye');
    e.find('.icon-xl').addClass('fa-eye-slash');
}
</script>
@endsection 