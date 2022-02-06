<div>
    <!--begin::Top-->
	<div class="header-top">
	  <!--begin::Container-->
	  <div class="container">
	    <!--begin::Left-->
	    <div class="d-none d-lg-flex align-items-center mr-3">
	      <!--begin::Logo-->
	      <a href="{{route('dashboard')}}" class="mr-20">
	        <img alt="Logo" src="{{asset('assets/media/logos/appointment-logo.png')}}" class="max-h-60px" />
	      </a>
	      <!--end::Logo-->
	      <!--begin::Tab Navs(for desktop mode)-->
	      <ul class="header-tabs nav align-self-end font-size-lg" role="tablist">
	        <!--begin::Item-->
	        <li class="nav-item">
	          <a href="{{route('dashboard')}}" class="nav-link py-4 px-6 {{ Request::routeIs('dashboard') ? 'active' : '' }}" >Dashboard</a>
	        </li>
	        <!--end::Item-->
			 <!--begin::Item-->
			 <li class="nav-item mr-3">
				<a href="{{route('patient.list')}}" class="nav-link py-4 px-6 {{ Request::routeIs('patient.*') ? 'active' : '' }}" >Patients</a>
			  </li>
	        <!--begin::Item-->
	        <li class="nav-item">
	          <a href="{{route('patient-report.list')}}" class="nav-link py-4 px-6 {{ (Request::routeIs('patient-report.*') || Request::routeIs('appointment.*')) ? 'active' : '' }}" >Patient Reports / Appointments</a>
	        </li>
	        <!--end::Item-->
	      </ul>
	      <!--begin::Tab Navs-->
	    </div>
	    <!--end::Left-->
	    <!--begin::Topbar-->
	    <div class="topbar bg-primary">
	      	<!--begin::User-->
	      	<div class="topbar-item">
		        <div class="btn btn-icon btn-hover-transparent-white w-sm-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
		          	<div class="d-flex flex-column text-right pr-sm-3">
		            	<!-- <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-sm-inline">{{Auth::user()->name}}</span> -->
		            	<span class="text-white font-weight-bolder font-size-sm d-none d-sm-inline">{{Auth::user()->name}}</span>
		          	</div>
		          	<span class="symbol symbol-35">
			          	@if(isset(Auth::user()->profile_pic) && Storage::exists(Auth::user()->profile_pic))
			          		<div class="symbol-label" style="background-image:url('{{Storage::url(Auth::user()->profile_pic)}}')"></div>
			          	@else
			            	<span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{strtoupper(mb_substr(Auth::user()->name, 0, 1))}}</span>
			            @endif
		          	</span>
		        </div>
	      	</div>
	      	<!--end::User-->
	    </div>
	    <!--end::Topbar-->
	  </div>
	  <!--end::Container-->
	</div>
	<!--end::Top-->
	<!--begin::Bottom-->
	<div class="header-bottom">
	  	<!--begin::Container-->
	  	<div class="container">
		    <!--begin::Header Menu Wrapper-->
		    <div class="header-navs header-navs-left" id="kt_header_navs">
		      	<!--begin::Tab Navs(for tablet and mobile modes)-->
		      	<ul class="header-tabs p-5 p-lg-0 d-flex d-lg-none nav nav-bold nav-tabs" role="tablist">
			        <!--begin::Item-->
			        <li class="nav-item mr-2">
			          	<a href="javascriipt:void(0);" class="nav-link btn btn-clean active" data-toggle="tab" data-target="#dashboard" role="tab">Dashboard</a>
			        </li>
			        <!--end::Item-->

					 <!--begin::Item-->
					<li class="nav-item mr-2">
							<a href="javascriipt:void(0);" class="nav-link btn btn-clean active" data-toggle="tab" data-target="#patient" role="tab">Patients</a>
					</li>
					<!--end::Item-->

			        <!--begin::Item-->
			        <li class="nav-item mr-2">
			          	<a href="javascriipt:void(0);" class="nav-link btn btn-clean active" data-toggle="tab" data-target="#patient-report" role="tab">Patient Reports / Appointments</a>
			        </li>
			        <!--end::Item-->
		      	</ul>
		      	<!--begin::Tab Navs-->
		      	<!--begin::Tab Content-->
		      	<div class="tab-content">
			        <!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('dashboard') ? 'show active' : '' }} " id="dashboard">
			          	<!--begin::Menu-->
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <!--begin::Nav-->
				            <ul class="menu-nav">
				              	<li class="menu-item {{ Request::routeIs('dashboard') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('dashboard')}}" class="menu-link">
					                  	<span class="menu-text">Dashboard</span>
					                </a>
				              	</li>
				            </ul>
				            <!--end::Nav-->
			          	</div>
			          	<!--end::Menu-->
			        </div>
			        <!--begin::Tab Pane-->

					<!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('patient.*') ? 'show active' : '' }} " id="patient">
						<!--begin::Menu-->
						<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
						  <!--begin::Nav-->
						  <ul class="menu-nav">
								<li class="menu-item {{ Request::routeIs('patient.*') ? 'menu-item-active' : '' }}" aria-haspopup="true">
								  <a href="{{route('patient.list')}}" class="menu-link">
										<span class="menu-text">Patients</span>
								  </a>
								</li>
						  </ul>
						  <!--end::Nav-->
						</div>
						<!--end::Menu-->
				  </div>
				  <!--begin::Tab Pane-->

			        <!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ (Request::routeIs('patient-report.*') || Request::routeIs('appointment.*')) ? 'show active' : '' }} " id="patient-report">
			          	<!--begin::Menu-->
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <!--begin::Nav-->
				            <ul class="menu-nav">
				              	<li class="menu-item {{ (Request::routeIs('patient-report.*') || Request::routeIs('appointment.*')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('patient-report.list')}}" class="menu-link">
					                  	<span class="menu-text">Patient Reports / Appointments</span>
					                </a>
				              	</li>
				            </ul>
				            <!--end::Nav-->
			          	</div>
			          	<!--end::Menu-->
			        </div>
			        <!--begin::Tab Pane-->
		      	</div>
		      	<!--end::Tab Content-->
		    </div>
		    <!--end::Header Menu Wrapper-->
	  	</div>
	  	<!--end::Container-->
	</div>
	<!--end::Bottom-->
</div>