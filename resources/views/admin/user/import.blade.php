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
                            <h3 class="card-label font-weight-bolder text-dark"> Import Clinic User </h3>

                        </div>
                        <div class="card-toolbar">
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                  <form class="form" name="submit"  action="{{ route('admin.user.importdata') }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        <!--Begin::Body-->
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label>Click below button to download Sample CSV File:</label>
                                        <div></div>
                                        <div class="custom-file">
                                            <a href="{{asset('assets/sample-csv/sample_clinicuser.csv')}}"><i class="icon-2x text-info flaticon-download-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">

                                        <label>Choose CSV File</label>
                                        <div></div>
                                        @if (count($errors) > 0)
                                            <div class="row">
                                                <div class="col-md-8 col-md-offset-1">
                                                  <div class="alert alert-danger alert-dismissible">
                                                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                      <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                                      @foreach($errors->all() as $error)
                                                            {{ $error }} <br>
                                                      @endforeach
                                                  </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input " id="customFile" name="csv_file">

                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                    <p class="text-primary">Note: Please add records as per the sample file and don't change any header field of CSV otherwise it will not import any records.</p>

                                </div>
                            </div>

                        </div>
                        <!--end::Body-->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
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