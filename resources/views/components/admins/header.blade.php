<div>
    <!--begin::Top-->
	<div class="header-top">
	  <!--begin::Container-->
	  <div class="container">
	    <!--begin::Left-->
	    <div class="d-none d-lg-flex align-items-center mr-3">
	      <!--begin::Logo-->
	      <a href="{{route('admin.dashboard')}}" class="mr-20">
	        <img alt="Logo" src="{{asset('assets/media/logos/appointment-logo.png')}}" class="max-h-60px" />
	      </a>
	      <!--end::Logo-->
	      <!--begin::Tab Navs(for desktop mode)-->
	      <ul class="header-tabs nav align-self-end font-size-lg" role="tablist">
	        <!--begin::Item-->
	        <li class="nav-item">
	          <a href="{{route('admin.dashboard')}}" class="nav-link py-4 px-6 {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" >Dashboard</a>
	        </li>
	        <!--end::Item-->
	        <!--begin::Item-->
	        <li class="nav-item mr-3">
	          <a href="{{route('admin.user.list')}}" class="nav-link py-4 px-6 {{ Request::routeIs('admin.user.*') ? 'active' : '' }}" >Clinic User</a>
	        </li>
	        <!--end::Item-->
	        <!--begin::Item-->
	        <li class="nav-item mr-3">
	          <a href="{{route('admin.patient.list')}}" class="nav-link py-4 px-6 {{ Request::routeIs('admin.patient.*') ? 'active' : '' }}" >Patients</a>
	        </li>
	        <!--end::Item-->
	        <!--begin::Item-->
	        <li class="nav-item mr-3">
	          <a href="{{route('admin.reporting.list')}}" class="nav-link py-4 px-6 {{ Request::routeIs('admin.reporting.*') ? 'active' : '' }}" >Testing Reports</a>
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
		            	<!-- <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-sm-inline">{{Auth::guard('admin')->user()->name}}</span> -->
		            	<span class="text-white font-weight-bolder font-size-sm d-none d-sm-inline">{{Auth::guard('admin')->user()->name}}</span>
		          	</div>
		          	<span class="symbol symbol-35">
			          	@if(isset(Auth::guard('admin')->user()->profile_img) && Storage::exists(Auth::guard('admin')->user()->profile_img))
			          		<div class="symbol-label" style="background-image:url('{{Storage::url(Auth::guard('admin')->user()->profile_img)}}')"></div>
			          	@else
			            	<span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{strtoupper(mb_substr(Auth::guard('admin')->user()->name, 0, 1))}}</span>
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
			          <a href="javascriipt:void(0);" class="nav-link btn btn-clean" data-toggle="tab" data-target="#user_tab" role="tab">Clinic User</a>
			        </li>
			        <!--end::Item-->
			        <!--begin::Item-->
			        <li class="nav-item mr-2">
			          <a href="javascriipt:void(0);" class="nav-link btn btn-clean" data-toggle="tab" data-target="#patient_tab" role="tab">Patients</a>
			        </li>
			        <!--end::Item-->
			        <!--begin::Item-->
			        <li class="nav-item mr-2">
			          <a href="javascriipt:void(0);" class="nav-link btn btn-clean" data-toggle="tab" data-target="#reporting_tab" role="tab">Reporting</a>
			        </li>
			        <!--end::Item-->
		      	</ul>
		      	<!--begin::Tab Navs-->
		      	<!--begin::Tab Content-->
		      	<div class="tab-content">
			        <!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('admin.dashboard') ? 'show active' : '' }} " id="dashboard">
			          	<!--begin::Menu-->
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <!--begin::Nav-->
				            <ul class="menu-nav">
				              	<li class="menu-item {{ Request::routeIs('admin.dashboard') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.dashboard')}}" class="menu-link">
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
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('admin.user.*') ? 'show active' : '' }}" id="user_tab">
			          	<!--begin::Menu-->
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <!--begin::Nav-->
				            <ul class="menu-nav">
				              	<li class="menu-item {{ Request::routeIs('admin.user.list') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.user.list')}}" class="menu-link">
					                  	<span class="menu-text">All Clinic User</span>
					                </a>
				              	</li>
				              	<li class="menu-item {{ Request::routeIs('admin.user.create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.user.create')}}" class="menu-link">
					                  	<span class="menu-text">Add Clinic User</span>
					                </a>
				              	</li>
				            </ul>
				            <!--end::Nav-->
			          	</div>
			          	<!--end::Menu-->
			        </div>
			        <!--begin::Tab Pane-->
			        <!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('admin.patient.*') ? 'show active' : '' }}" id="patient_tab">
			          	<!--begin::Menu-->
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <!--begin::Nav-->
				            <ul class="menu-nav">
				              	<li class="menu-item {{ Request::routeIs('admin.patient.list') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.patient.list')}}" class="menu-link">
					                  	<span class="menu-text">All Patient</span>
					                </a>
				              	</li>
				              	<li class="menu-item {{ Request::routeIs('admin.patient.create') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.patient.create')}}" class="menu-link">
					                  	<span class="menu-text">Add Patient</span>
					                </a>
				              	</li>
				            </ul>
				            <!--end::Nav-->
			          	</div>
			          	<!--end::Menu-->
			        </div>
			        <!--begin::Tab Pane-->
			        <!--begin::Tab Pane-->
			        <div class="tab-pane py-5 p-lg-0 {{ Request::routeIs('admin.reporting.*') ? 'show active' : '' }}" id="reporting_tab">
			          	<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				            <ul class="menu-nav">
				              	<li class="menu-item {{ Request::routeIs('admin.reporting.list') ? 'menu-item-active' : '' }}" aria-haspopup="true">
					                <a href="{{route('admin.patient.list')}}" class="menu-link">
					                  	<span class="menu-text">All Reporting</span>
					                </a>
				              	</li>
				            </ul>
			          	</div>
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