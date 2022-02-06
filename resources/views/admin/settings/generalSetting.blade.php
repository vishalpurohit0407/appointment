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
                            <h3 class="card-label font-weight-bolder text-dark">Settings</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Basic settings and more</span>
                        </div>
                        <div class="card-toolbar">
                            <button type="submit" class="btn btn-success mr-2" form="settings-form">Save Changes</button>
                            <a href="{{route('admin.dashboard')}}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form" id="settings-form" action="{{ route('admin.save.settings') }}" method="post" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Section-->
                            <div>
                                <h5 class="font-weight-bold mb-3">Basic settings</h5>
                                <div class="form-group mb-0 row align-items-center">
                                    <label class="col-8 col-form-label"><i class="icon-sm fas fa-sync"></i>&nbsp;Table Script Synchronization Status:</label>
                                    @if($tables_sync_val && $tables_sync_val->value == '2')
                                        <div class="col-4 d-flex justify-content-end">
                                            <input data-switch="true" type="checkbox" checked value="2" name="tables_sync_val" data-handle-width="90" data-label-width="10" data-on-text="Processing..." data-on-color="warning" readonly />
                                        </div>
                                    @else
                                        <div class="col-4 d-flex justify-content-end">
                                            <input data-switch="true" type="checkbox" value="1" name="tables_sync_val" data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="primary" @if($tables_sync_val && $tables_sync_val->value == '1') checked @endif/>
                                        </div>
                                    @endif
                                </div>
                                <!-- <p class="font-size-sm pl-5"><strong class="text-warning">NOTE:&nbsp;</strong></p> -->
                            </div>
                            <!--end::Section-->
                            <!-- <div class="separator separator-dashed my-6"></div> -->
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
    $('[data-switch=true]').bootstrapSwitch();
});
</script>
@endsection