<!-- Dashboard Page -->
@extends('layouts.app')
@section('title')
  {{$title}}
@endsection
@section('content')

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<x-flash-message></x-flash-message>
		<!--begin::Education-->
		<div class="card card-custom gutter-b">
			<div class="card-body">
				<!--begin::Top-->
				<div class="d-flex">
					<!--begin::Pic-->
					<div class="flex-shrink-0 mr-7">
						<div class="symbol symbol-50 symbol-lg-120">
							@if(isset($patientdata->profile_pic) && Storage::exists($patientdata->profile_pic))
								<img alt="Pic" class="w-100" src="{{Storage::url($patientdata->profile_pic)}}">
							@else
								<img alt="Pic" class="w-100" src="{{asset('assets/media/users/blank.png')}}">
							@endif
						</div>
					</div>
					<!--end::Pic-->
					<!--begin: Info-->
					<div class="flex-grow-1">
						<!--begin::Title-->
						<div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
							<!--begin::User-->
							<div class="mr-3">
								<!--begin::Name-->
								<a class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
								</a>
								<!--end::Name-->
								<!--begin::Contacts-->
								<div class="d-inline-block my-2">
									<a href="javascript:void(0);" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
										<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
											<!--begin::Svg Icon | path:/metronic/theme/html/demo2/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"></rect>
													<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"></path>
													<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"></circle>
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>{{$patientdata->email ? $patientdata->email : 'N/A'}}</a>
									<a href="javascript:void(0);" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
										<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M7.13888889,4 L7.13888889,19 L16.8611111,19 L16.8611111,4 L7.13888889,4 Z M7.83333333,1 L16.1666667,1 C17.5729473,1 18.25,1.98121694 18.25,3.5 L18.25,20.5 C18.25,22.0187831 17.5729473,23 16.1666667,23 L7.83333333,23 C6.42705272,23 5.75,22.0187831 5.75,20.5 L5.75,3.5 C5.75,1.98121694 6.42705272,1 7.83333333,1 Z" fill="#000000" fill-rule="nonzero"/>
											        <polygon fill="#000000" opacity="0.3" points="7 4 7 19 17 19 17 4"/>
											        <circle fill="#000000" cx="12" cy="21" r="1"/>
											    </g>
											</svg>
										</span>{{$patientdata->mobile ? ucfirst($patientdata->mobile) : 'N/A'}}
									</a>
									<!-- <a href="javascript:void(0);" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
										<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24"></rect>
													<path d="M9.82829464,16.6565893 C7.02541569,15.7427556 5,13.1079084 5,10 C5,6.13400675 8.13400675,3 12,3 C15.8659932,3 19,6.13400675 19,10 C19,13.1079084 16.9745843,15.7427556 14.1717054,16.6565893 L12,21 L9.82829464,16.6565893 Z M12,12 C13.1045695,12 14,11.1045695 14,10 C14,8.8954305 13.1045695,8 12,8 C10.8954305,8 10,8.8954305 10,10 C10,11.1045695 10.8954305,12 12,12 Z" fill="#000000"></path>
												</g>
											</svg>
										</span>{{$patientdata->address ? ucfirst($patientdata->address) : 'N/A'}}
									</a> -->
								</div>
								<!--end::Contacts-->
							</div>
							<!--begin::User-->
							<!--begin::Actions-->
							<div class="my-lg-0 my-1">
								<a href="{{route('patient-report.list')}}" class="btn btn-sm btn-primary font-weight-bolder text-uppercase mr-2">Back</a>
							</div>

							<!--end::Actions-->
						</div>
						<!--end::Title-->
						<!--begin::Content-->
						<div class="flex-wrap col-lg-12 row pl-0">
							<!--begin::Description-->
							<div class="col-lg-12">
								<div class="font-weight-bold text-dark-50 py-2 py-lg-">
									<div class="d-flex align-items-center">
										<span class="text-dark-75 font-weight-bolder mr-4">Patient Name :</span>
										<span class="text-muted">{{$patientdata->name ? $patientdata->name." ".$patientdata->last_name : 'N/A'}}</span>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="font-weight-bold text-dark-50 py-2 py-lg-">
									<div class="d-flex align-items-center">
										<span class="text-dark-75 font-weight-bolder mr-4">Birthdate :</span>
										<span class="text-muted">{{$patientdata->dob ? $patientdata->dob : 'N/A'}}</span>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="font-weight-bold text-dark-50 py-2 py-lg-">
									<div class="d-flex align-items-center">
										<span class="text-dark-75 font-weight-bolder mr-4">Gender :</span>
										<span class="text-muted">{{$patientdata->gender ? ($patientdata->gender == 0 ? 'Male' : 'Female') : 'N/A'}}</span>
									</div>
								</div>
							</div>

							@if(isset($patientReport) && $patientReport->rt_pcr == '1')
								<div class="col-lg-12">
									<div class="font-weight-bold text-dark-50 py-2 py-lg-">
										<div class="d-flex align-items-center">
											<span class="text-dark-75 font-weight-bolder mr-4">RT PCR Status :</span>
											@if($patientReport->rt_pcr_status == '0')
												<span class="label label-danger label-dot mr-2"></span>
												<span class="font-weight-bold text-danger">Negative</span>
											@elseif($patientReport->rt_pcr_status == '1')
												<span class="label label-success label-dot mr-2"></span>
												<span class="font-weight-bold text-success">Positive</span>
											@endif
											<button class="ml-5 btn btn-info btn-sm save-rtpcr">Send Report</button>
										</div>
									</div>
								</div>

							@endif

							@if(isset($patientReport) && $patientReport->antigens == '1')
								<div class="col-lg-12">
									<div class="font-weight-bold text-dark-50 py-2 py-lg-">
										<div class="d-flex align-items-center">
											<span class="text-dark-75 font-weight-bolder mr-4">Antigens Status :</span>
											@if($patientReport->antigens_status == '0')
												<span class="label label-danger label-dot mr-2"></span>
												<span class="font-weight-bold text-danger">Negative</span>
											@elseif($patientReport->antigens_status == '1')
												<span class="label label-success label-dot mr-2"></span>
												<span class="font-weight-bold text-success">Positive</span>
											@endif
											<button class="ml-5 btn btn-info btn-sm save-antigens">Send Report</button>
										</div>
									</div>
								</div>
								@if($patientReport->antigens_count > 0)
								<div class="col-lg-12">
									<div class="font-weight-bold text-dark-50 py-2 py-lg-">
										<div class="d-flex align-items-center">
											<span class="text-dark-75 font-weight-bolder mr-4">Antigens Count :</span>
											<span class="font-weight-bold text-info">{{$patientReport->antigens_count}}</span>
										</div>
									</div>
								</div>
								@endif
							@endif
							<div class="col-lg-6">
								<div class="font-weight-bold text-dark-50 py-2 py-lg-">
									<div class="d-flex align-items-center">
										<span class="text-dark-75 font-weight-bolder mr-4">Register At :</span>
										<span class="text-muted">{{$patientdata->created_at ? date('jS F Y', strtotime($patientdata->created_at)) : 'N/A'}}</span>
									</div>
								</div>
							</div>
							<!--end::Description-->
						</div>
						<!--end::Content-->
					</div>
					<!--end::Info-->
				</div>
				<!--end::Top-->
				<!--begin::Separator-->
				<div class="separator separator-solid my-7"></div>

				<!--end::Separator-->
			</div>
		</div>
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
@endsection

@section('pagewise_js')
<script type="text/javascript">
	jQuery(document).ready(function() {
		$('[data-switch=true]').bootstrapSwitch();
    });

	$('.save-rtpcr').click(function(e){
		savetReport('rtpcr');
		$(this).addClass('spinner spinner-white spinner-right');
    });

	$('.save-antigens').click(function(e){
		savetReport('antigens');
		$(this).addClass('spinner spinner-white spinner-right');
    });

	$('.antigens_status').on('change.bootstrapSwitch', function(e) {
		if(e.target.checked)
		{
			$('.antigens_count').show();
		}
		else
		{
			$('.antigens_count').hide();
		}
	});

    function savetReport(type)
	{
		var report_id  = "{{ isset($patientReport->id) ? $patientReport->id : ''}}";

		$.ajax({
			url : "{{route('patient.send-report')}}",
			type: "POST",
			data : {
				"report_id" : report_id,
				"type"      : type,
				"_token"    : "{{ csrf_token() }}"
			},
			dataType: "json",
			success: function(data, textStatus, jqXHR)
			{
				$('.save-rtpcr').removeClass('spinner spinner-white spinner-right');
				$('.save-antigens').removeClass('spinner spinner-white spinner-right');

				if(data.success == true)
				{
					toastr.warning(data.message);

					// setTimeout(function () {
					// 	location.reload();
					// }, 2500);
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{

			}
		});

		}


</script>
@endsection

