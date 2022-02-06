<!-- Admin Edit Profile Page -->
@extends('layouts.admin.master')
@section('title')
  {{$title}}
@endsection
@section('content')
<!--begin::Entry-->
<style type="text/css">

</style>
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Card-->
        <x-admins.flash-message></x-admins.flash-message>
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                <h3 class="card-label">{{$title}} Of #{{$session_id}}
                    <span class="d-block text-muted pt-2 font-size-sm">You can view list product of a particular session.</span>
                </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{route('admin.reporting.list')}}" class="btn btn-light-primary font-weight-bolder px-8 font-size-sm">
                    <span class="svg-icon">
                        <!--begin::Svg Icon | path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Files/File-done.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
                                <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Back</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom " id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Description</th>
                            <th>Barcode</th>
                            <th>Part Num</th>
                            <th>Date Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Content-->
@endsection
@section('pagewise_js')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#kt_datatable').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: "{{route('admin.reporting.product.list.data')}}",
                type: 'POST',
                data: {
                    session_id:'{{$session_id}}',
                    cust_id:'{{$cust_id}}',
                    clinic_user_id:'{{$clinic_user_id}}',
                    start_date:'{{$start_date}}',
                    end_date:'{{$end_date}}',
                    "_token": "{{ csrf_token() }}",
                },
            },
            columns: [
                {data: 'srno'},
                {data: 'part_description'},
                {data: 'product_barcode'},
                {data: 'part_num'},
                {data: 'date_time'},
                {data: 'actions', responsivePriority: -1},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    "title": "Sr No.",
                    render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    width: '250px',
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return '<div class="d-flex align-items-center">\
                                    <div class="ml-3">\
                                        <span class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">'+data+'</span>\
                                    </div>\
                                </div>';
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                },
                {
                    width: '120px',
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var urlshow= '{{ route("admin.reporting.product.details",":id") }}';
                        urlshow = urlshow.replace(':id', full.id);
                        return '\
                            <a href="'+urlshow+'" class="btn btn-sm btn-light btn-hover-primary" title="View Details">\
                                View Details\
                            </a>\ ';
                    },
                },
            ],
        });
    });
</script>
@endsection