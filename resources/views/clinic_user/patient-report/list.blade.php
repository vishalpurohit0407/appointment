<!-- Edit Profile Page -->
@extends('layouts.app')
@section('title')
  {{$title}}
@endsection
@section('content')
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Card-->
        <x-flash-message></x-flash-message>
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Upcoming Appointments
                        <span class="d-block text-muted pt-2 font-size-sm">You can delete & view full details of a particular Appointments.</span>
                    </h3>
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
                            <th>Appointment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->

        <div class="card card-custom mt-10">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Completed Reports
                        <span class="d-block text-muted pt-2 font-size-sm">You can delete & view full details of a particular Patient Report.</span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom " id="kt_datatable_report" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Received Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
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
            searchDelay: 100,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: "{{route('appointment.listdata')}}",
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
                {data: 'appointment_time'},
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
                    width: '150px',
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var urlshow= '{{ route("appointment.show",":id") }}';
                        var urldelete = '{{ route("appointment.destroy",":id") }}';
                        urlshow = urlshow.replace(':id', full.hashid);
                        urldelete = urldelete.replace(':id', full.hashid);
                        return '\
                            <a href="'+urlshow+'" class="btn btn-sm btn-clean btn-icon" title="Show details">\
                                <i class="la la-clipboard-list icon-xl"></i>\
                            </a>\
                            <a href="javascript:;" data-id="'+data+'" class="btn btn-sm btn-clean btn-icon" title="Delete" onClick="destroyFunction(this)">\
                                <i class="la la-trash icon-xl"></i>\
                            </a>\
                            <form id="'+data+'" action="'+urldelete+'" method="POST" style="display: none;"> {{ method_field('delete') }} {{ csrf_field() }} </form>\
                        ';
                    },
                }
            ],
        });

        // Report Listing
        var reportTable = $('#kt_datatable_report');
        // begin first table
        reportTable.DataTable({
            responsive: true,
            searchDelay: 100,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: "{{route('patient-report.listdata')}}",
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
                {data: 'received_date'},
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
                    width: '150px',
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var urlshow= '{{ route("patient-report.show",":id") }}';
                        var urldelete = '{{ route("patient-report.destroy",":id") }}';
                        urlshow = urlshow.replace(':id', full.hashid);
                        urldelete = urldelete.replace(':id', full.hashid);
                        return '\
                            <a href="'+urlshow+'" class="btn btn-sm btn-clean btn-icon" title="Show details">\
                                <i class="la la-clipboard-list icon-xl"></i>\
                            </a>\
                            <a href="javascript:;" data-id="'+data+'" class="btn btn-sm btn-clean btn-icon" title="Delete" onClick="destroyFunction(this)">\
                                <i class="la la-trash icon-xl"></i>\
                            </a>\
                            <form id="'+data+'" action="'+urldelete+'" method="POST" style="display: none;"> {{ method_field('delete') }} {{ csrf_field() }} </form>\
                        ';
                    },
                }
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