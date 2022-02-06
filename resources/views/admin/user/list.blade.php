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
        <!--begin::Card-->
        <x-admins.flash-message></x-admins.flash-message>
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                <h3 class="card-label">{{$title}}
                    <span class="d-block text-muted pt-2 font-size-sm">You can add, edit, delete & view full details of a particular Clinic User.</span>
                </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{route('admin.user.create')}}" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>New Record</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <a href="{{route('admin.user.import')}}" class="btn btn-primary font-weight-bolder ml-2">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Import Recoard</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom " id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created At</th>
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
        var table = $('#kt_datatable');
        // begin first table
        table.DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: "{{route('admin.user.listdata')}}",
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    "_token": "{{ csrf_token() }}",
                },
            },
            columns: [
                {data: 'srno'},
                {data: 'name'},
                {data: 'email'},
                {data: 'status'},
                {data: 'created_at'},
                {data: 'actions', responsivePriority: -1},
            ],
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    "title": "Sr No.",
                    render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    width: '250px',
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return '<div class="d-flex align-items-center">\
                                    <div class="symbol symbol-40 flex-shrink-0">\
                                        <img src="'+full.profile_pic+'" alt="photo">\
                                    </div>\
                                    <div class="ml-3">\
                                        <span class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">'+full.name+'</span>\
                                    </div>\
                                </div>';
                    },
                },
                {
                    width: '120px',
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var urledit = '{{ route("admin.user.edit",":id") }}';
                        var urlshow= '{{ route("admin.user.show",":id") }}';
                        var urldelete = '{{ route("admin.user.destroy",":id") }}';
                        urlshow = urlshow.replace(':id', full.hashid);
                        urledit = urledit.replace(':id', full.hashid);
                        urldelete = urldelete.replace(':id', full.hashid);
                        return '\
                            <a href="'+urlshow+'" class="btn btn-sm btn-clean btn-icon" title="Show details">\
                                <i class="la la-clipboard-list icon-xl"></i>\
                            </a>\
                            <a href="'+urledit+'" class="btn btn-sm btn-clean btn-icon" title="Edit details">\
                                <i class="la la-edit icon-xl"></i>\
                            </a>\
                            <a href="javascript:;" data-id="'+data+'" class="btn btn-sm btn-clean btn-icon" title="Delete" onClick="destroyFunction(this)">\
                                <i class="la la-trash icon-xl"></i>\
                            </a>\
                            <form id="'+data+'" action="'+urldelete+'" method="POST" style="display: none;"> {{ method_field('delete') }} {{ csrf_field() }} </form>\
                        ';
                    },
                },
                {
                    width: '75px',
                    targets: -3,
                    render: function(data, type, full, meta) {
                        var status = {
                            0: {'title': 'Inactive', 'state': 'danger'},
                            1: {'title': 'Active', 'state': 'success'},
                        };
                        if (typeof status[data] === 'undefined') {
                            return data;
                        }
                        return '<span class="label label-' + status[data].state + ' label-dot mr-2"></span>' +
                            '<span class="font-weight-bold text-' + status[data].state + '">' + status[data].title + '</span>';
                    },
                },
            ],
        });
    });

    function destroyFunction(e){
        var id = $(e).attr('data-id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won\'t be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then(function(result) {
            if (result.value) {
                document.getElementById(id).submit();
            }
        });
    }
</script>
@endsection