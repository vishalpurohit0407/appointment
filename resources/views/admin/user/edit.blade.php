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
        <div class="row">
            <!--begin::Content-->
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Clinic User Information</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">{{$title}} informaiton</span>
                        </div>
                        <div class="card-toolbar">

                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" action="{{ route('admin.user.update',$userdata->hashid) }}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        {{ csrf_field() }}
                        <!--Begin::Body-->
                        <div class="card-body">
                            <!-- <h4 class="font-size-h4 text-dark font-weight-bold mb-6">1. Personal Details :</h4> -->
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label><span class="text-danger">*</span>&nbsp;Provider Name :</label>
                                    <input type="text" class="form-control @if($errors->has('provider_name')) is-invalid @endif maxlength" maxlength="255" name="provider_name" placeholder="Provider Name" value="{{old('provider_name',$userdata->provider_name)}}">
                                    @error('provider_name')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label><span class="text-danger">*</span>&nbsp;Full Name :</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif maxlength" maxlength="255" name="name" placeholder="Full Name" value="{{old('name',$userdata->name)}}">
                                    @error('name')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label><span class="text-danger">*</span>&nbsp;Email :</label>
                                    <input type="text" class="form-control @if($errors->has('email')) is-invalid @endif maxlength" maxlength="255" name="email" placeholder="Email" value="{{old('email',$userdata->email)}}">
                                    @error('email')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label>Password :</label>
                                    <div class="input-group @if($errors->has('password')) is-invalid @endif">
                                        <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif maxlength" maxlength="255" name="password" placeholder="Password">
                                        <div class="input-group-append showOrHide" style="cursor: pointer;">
                                            <span class="input-group-text">
                                              <i class="icon-lg far fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Status :</label>
                                    <div>
                                        <input data-switch="true" name="status" type="checkbox" data-on-text="Active" data-handle-width="70" data-off-text="Inactive" data-on-color="success" data-off-color="danger" value="1" {{$userdata->status == '1' ? 'checked' : ''}}/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label><span class="text-danger"></span>&nbsp;Mobile :</label>
                                    <input class="form-control @if($errors->has('mobile')) is-invalid @endif" placeholder="Enter Mobile" name="mobile" id="mobile" type="text" maxlength="10" value="{{old('mobile',$userdata->mobile)}}" />
                                    @error('mobile')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    <a href="{{route('admin.user.list')}}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
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
    $('[data-switch=true]').bootstrapSwitch();
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
    $("#mobile").inputmask("mask", {
        "mask": "9999999999",
        "clearIncomplete": true
    });
    // Mobile offcanvas for mobile mode
    new KTImageInput("profile_img");

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