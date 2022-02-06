<div>
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
	    @if(Session::has('alert-' . $msg))
	        <div class="alert alert-custom alert-{{ $msg }} fade show mb-5 alert-dismissible" role="alert">                           
	            <div class="alert-text">{!! Session::get('alert-' . $msg) !!}</div>
	           <!--  <div class="alert-close">
	                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                    <span aria-hidden="true">
	                        <i class="ki ki-close"></i>
	                    </span>
	                </button>
	            </div> -->
	        </div>
	    @endif 
	@endforeach
</div>