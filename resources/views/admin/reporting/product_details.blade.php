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
       <div class="d-flex flex-row">
            <!--begin::Layout-->
            <div class="flex-row-fluid">
                <!--begin::Section-->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xxl-12">
                        <!--begin::Engage Widget 14-->
                        <div class="card card-custom card-stretch gutter-b">
                            <div class="card-body p-15 pb-20">
                                <div class="row mb-10">
                                    <div class="col-xxl-4 mb-20 mb-xxl-10">
                                        <!--begin::Image-->
                                        <div class="">
                                            <div class="pt-0 rounded px-10 py-15 d-flex align-items-center justify-content-center" >
                                                @if(isset($product->part_image->content) && Storage::exists($product->part_image->content))
                                                    <img src="{{Storage::url($product->part_image->content)}}" class="mw-100 w-250px rounded" style="transform: scale(1.6);">
                                                @else
                                                    <img src="{{asset('assets/media/no-image.jpg')}}" class="mw-100 w-250px rounded" style="transform: scale(1.6);">
                                                @endif
                                            </div>
                                        </div>
                                        <!--end::Image-->
                                    </div>
                                    <div class="col-xxl-8 pl-xxl-11">
                                        <h2 class="font-weight-bolder text-dark mb-7" style="font-size: 25px;">{{$product->product_barcode}}</h2>
                                        <div class="font-size-h4 mb-7 text-dark-50">Part Number:
                                        <span class="font-weight-boldest ml-2">{{$product->part_num ?? 'N/A'}}</span></div>
                                        <div class="font-size-h4 mb-7 text-dark-50">Description:
                                        <span class="font-weight-boldest ml-2">{{$product->part_description ?? 'N/A'}}</span></div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs nav-tabs-line mb-15 justify-content-center">
                                    <li class="nav-item mr-2">
                                        <a class="nav-link active" data-toggle="tab" href="#basic_info">
                                            <span class="nav-icon mr-2"><i class="flaticon2-information icon-2x"></i></span>
                                            <span class="nav-text h5">Basic Info</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mr-2">
                                        <a class="nav-link" data-toggle="tab" href="#additional_info">
                                            <span class="nav-icon mr-2"><i class="flaticon2-paper icon-2x"></i></span>
                                            <span class="nav-text h5">Additional Info</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mr-2">
                                        <a class="nav-link" data-toggle="tab" href="#sales_history">
                                            <span class="nav-icon mr-2"><i class="flaticon2-notepad icon-2x"></i></span>
                                            <span class="nav-text h5">Sales History</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="basic_info" role="tabpanel" aria-labelledby="basic_info">
                                        <div class="row mb-6 px-5">
                                            <!--begin::Info-->
                                            <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Sales UOM</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->sales_um ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Unit Price</span>
                                                    @if($product->currency_code == 'USD')
                                                        <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->unit_price_usd ?? 'N/A'}} / {{$product->list_unit_price_usd ?? 'N/A'}}</span>
                                                    @else
                                                        <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->unit_price_hkd ?? 'N/A'}} / {{$product->list_unit_price_hkd ?? 'N/A'}}</span>
                                                    @endif
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">On Hand</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->on_hand ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Qty. Per Master Carton</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->qty_per_master_carton ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Pkg Inner</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->pkg_inner ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Pkg Nw</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->pkg_nw ?? 'N/A'}}</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Sales MOQ</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->sales_moq ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Product Barcode</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->product_barcode ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Balance</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->balance ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Pkg CBM</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->pkg_cbm ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Brand</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->brand ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Pkg Gw</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->pkg_gw ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">HS Code</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->hs_code ?? 'N/A'}}</span>
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="additional_info" role="tabpanel" aria-labelledby="additional_info">
                                        <div class="row mb-6">
                                            <!--begin::Info-->
                                            <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">UOM</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->sales_um ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Unit Price</span>
                                                    @if($product->currency_code == 'USD')
                                                        <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->unit_price_usd ?? 'N/A'}} / {{$product->list_unit_price_usd ?? 'N/A'}}</span>
                                                    @else
                                                        <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->unit_price_hkd ?? 'N/A'}} / {{$product->list_unit_price_hkd ?? 'N/A'}}</span>
                                                    @endif
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Currency Code</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->cust_info ? ($product->cust_info->currency_code ?? 'N/A') : 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Buyer Id</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->buyer_id ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Parent</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->parent ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Payment Terms</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->cust_info ? ($product->cust_info->payment_term ?? 'N/A') : 'N/A'}}</span>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Sales MOQ</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->sales_moq ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Part Cost</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->part_cost ? ($product->part_cost->part_cost ?? 'N/A') : 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Gross Margin %</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->part_summary ? ($product->part_summary->cust_gm_percentage ?? 'N/A') : 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Supplier Id</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->supplier_id ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">Child</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->child ?? 'N/A'}}</span>
                                                </div>
                                                <div class="mb-8 d-flex">
                                                    <span class="text-dark flex-root font-weight-bold mb-4">STK_CAT1</span>
                                                    <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$product->stk_cat ?? 'N/A'}}</span>
                                                </div>
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="sales_history" role="tabpanel" aria-labelledby="sales_history">
                                        @if(count($product->cust_latest_trans) > 0 )
                                            @foreach($product->cust_latest_trans as $trans_history)
                                                <div class="row ">
                                                    <!--begin::Info-->
                                                    <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Cust ID</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->cust_id ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">So#</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->order_num ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Order Qty.</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->selling_quantity ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Currency</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->currency_code ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">UOM</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->sales_um ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Surcharge</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->surcharge ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">GM%</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->gm_percentage ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Order Date</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->order_date ?? 'N/A'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-6 col-lg-6 col-sm-6">
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Part No.</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->part_num ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Cus. No.</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->x_part_num ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">S/O Qty.</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->out_std_qty ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">P1</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->unit_price_surchg ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Net P1</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->net_unit_price ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Total Amt.</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->total_amt ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Cost</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->act_cost ?? 'N/A'}}</span>
                                                        </div>
                                                        <div class="mb-8 d-flex">
                                                            <span class="text-dark flex-root font-weight-bold mb-4">Ship By</span>
                                                            <span class="text-muted flex-root font-weight-bolder font-size-lg">{{$trans_history->ship_by ?? 'N/A'}}</span>
                                                        </div>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                @if(!$loop->last)
                                                    <div class="separator separator-dashed separator-border-4 my-5 mb-15"></div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="row justify-content-center">
                                                <p class="font-weight-normal">No records found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="separator separator-dashed separator-border-4 my-5 mb-15"></div>
                                <!--begin::Buttons-->
                                <div class="d-flex">
                                    <a href="{{ URL::previous() }}" class="btn btn-light-primary font-weight-bolder px-8 font-size-sm">
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
                                </div>
                                <!--end::Buttons-->
                            </div>
                        </div>
                        <!--end::Engage Widget 14-->
                    </div>
                </div>
                <!--end::Section-->
            </div>
            <!--end::Layout-->
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Content-->
@endsection
@section('pagewise_js')
<script type="text/javascript">
    $(document).ready(function() {
       
    });
</script>
@endsection