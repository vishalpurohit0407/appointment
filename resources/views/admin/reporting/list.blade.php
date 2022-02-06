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
                <h3 class="card-label">{{$title}}
                    <span class="d-block text-muted pt-2 font-size-sm">You can view list of all session.</span>
                </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <!-- <a href="#" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                    </span>New Record</a> -->
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Search Form-->
                    <div class="row mb-6">
                        <div class="col-lg-3 mb-lg-0 mb-6">
                            <div class="row">
                                <div class="col">
                                    <label>Select Date:</label>
                                    <div class='input-group' id='kt_daterangepicker_4'>
                                        <input type='text' class="form-control" readonly="readonly" id="date" name="date" placeholder="Select date &amp; time range" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6">
                            <label>Clinic User:</label>
                            <select class="form-control select2" id="clinic_user_id" name="clinic_user_id[]" multiple>
                                <option label="Label"></option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-lg-0 mb-6">
                            <label>Patient:</label>
                            <select class="form-control select2" id="patient_id" name="patient_id[]" multiple>
                                <option label="Label"></option>
                            </select>
                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" id="search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Search</span>
                                    </span>
                                </button>&#160;&#160;
                                <button class="btn btn-secondary btn-secondary--icon" id="reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Reset</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                <!--begin: Datatable-->
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom " id="kt_datatable" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Clinic User</th>
                            <th>Session id</th>
                            <th>Patient ERP ID</th>
                            <th>Total Product</th>
                            <th>Date Time</th>
                            <th>Details</th>
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
        fetchData();
        $('#search').click(function() {
            var patient_id = $('#patient_id').val();
            var date = $("#date").val();
            var clinic_user = $("#clinic_user_id").val();
            var valStatus = false;
            if (patient_id != '') {
                valStatus=true;
            }
            if (clinic_user != '') {
                valStatus=true;
            }
            if (date != '') {
                valStatus=true;
            }
            if (valStatus == true) {
                $('#kt_datatable').DataTable().destroy();
                $('#kt_datatable').html('');
                fetchData();
            }else{
                Swal.fire({
                    text: "Any one field is required. date & time range OR patient",
                    icon: "warning",
                })
            }
        });

        $('#reset').click(function() {

            $('#kt_datatable').DataTable().destroy();
            $('#kt_datatable').html('');
            $('#patient_id').empty();
            $('#clinic_user_id').empty();
            $("#date").val('');
            fetchData();
        });

        $("#patient_id").select2({
            placeholder: "Search patient using name, email and custmer ID",
            ajax: {
                url: "{{route('admin.reporting.get.patient')}}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

        $("#clinic_user_id").select2({
            placeholder: "Search sales person using name, email and custmer ID",
            ajax: {
                url: "{{route('admin.reporting.get.clinic_user')}}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
    });
    function fetchData() {

        $('#kt_daterangepicker_4').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: true,
            maxDate: new Date(),
            locale: {
                format: 'YYYY-MM-DD h:mm:s'
            }
        }, function(start, end, label) {
         $('#kt_daterangepicker_4 .form-control').val( start.format('YYYY-MM-DD h:mm:s') + ' / ' + end.format('YYYY-MM-DD h:m:s'));
        });

        var table = $('#kt_datatable').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: "{{route('admin.reporting.list.data')}}",
                type: 'POST',
                data: {
                    clinic_user_id:$('#clinic_user_id').val(),
                    patient_id:$('#patient_id').val(),
                    start_date:$("#date").val().split('/')[0],
                    end_date:$("#date").val().split('/')[1],
                    "_token": "{{ csrf_token() }}",
                },
            },
            columns: [
                {data: 'srno'},
                {data: 'clinic_user'},
                {data: 'session_id'},
                {data: 'cust_erp_id'},
                {data: 'product_count'},
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
                    "title": "Clinic User",
                    render: function(data, type, full, meta) {
                        return '<div class="d-flex align-items-center">\
                                    <div class="ml-3">\
                                        <span class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">'+full.clinic_user+'</span>\
                                    </div>\
                                </div>';
                    },
                },
                {
                    targets: 2,
                    "title": "Session ID",
                },
                {
                    targets: 3,
                    "title": "Patient ERP ID",
                    orderable: false,
                },
                {
                    targets: 4,
                    "title": "Total Product",
                    orderable: false,
                },
                {
                    targets: 5,
                    "title": "Date Time",
                    orderable: false,
                },
                {
                    width: '120px',
                    targets: -1,
                    title: 'Details',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var urlproductlist= '{{ route("admin.reporting.product.list",[":id","cust_id","clinic_user_id","start_date","end_date"]) }}';
                        urlproductlist = urlproductlist.replace(':id', full.session_id);
                        urlproductlist = urlproductlist.replace('cust_id',full.cust_id);
                        urlproductlist = urlproductlist.replace('clinic_user_id',full.salesman_id);
                        urlproductlist = urlproductlist.replace('start_date',$("#date").val().split('/')[0] || null);
                        urlproductlist = urlproductlist.replace('end_date',$("#date").val().split('/')[1] || null);
                        return '\
                            <a href="'+urlproductlist+'" class="btn btn-sm btn-light btn-hover-primary" title="View Details">\
                                View Details\
                            </a>\ ';
                    },
                },
            ],
        });
    }
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
    // loading remote data

    function formatRepo(repo) {
        if (repo.loading) return repo.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'><h6>" + repo.name + "</h6></div>"+
        "<div class='select2-result-repository__statistics'>"+
            "<div class='select2-result-repository__forks'> Email: "+ repo.email+"</div>"+
            "<div class='select2-result-repository__stargazers'>ERP ID: "+ repo.erp_id+"</div>"+
        "</div>"+
        "<input type='hidden' class='select2-result-repository__db_id' value='"+repo.id+"'>";
        return markup;
    }

    function formatRepoSelection(repo){
        return repo.name || repo.text;
    }

</script>
@endsection