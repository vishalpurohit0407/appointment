<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Response;
use Storage;
use Redirect;
use App\Models\ScriptActivity;
use App\Models\HtlCustInfo;
use App\Models\SqlHtlCustInfo;
use App\Models\HtlCustLatestTrans;
use App\Models\SqlHtlCustLatestTrans;
use App\Models\HtlCustPartSummary;
use App\Models\SqlHtlCustPartSummary;
use App\Models\HtlCustPriceBook;
use App\Models\SqlHtlCustPriceBook;
use App\Models\SqlHtlCustPartInfo;
use App\Models\HtlCustPartInfo;
use App\Models\HtlCustSummary;
use App\Models\SqlHtlCustSummary;
use App\Models\HtlExRate;
use App\Models\SqlHtlExRate;
use App\Models\HtlPartCost;
use App\Models\SqlHtlPartCost;
use App\Models\HtlPartImage;
use App\Models\SqlHtlPartImage;
use App\Models\HtlPartInfo;
use App\Models\SqlHtlPartInfo;
use App\Models\Setting;
use App\Models\HtlTableJsonData;
use App\Models\Admin;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Carbon\Carbon;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

use Illuminate\Support\Arr;

class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function scriptForCustInfoTransSummary(Request $request){


        try {

            $tables_sync_status = Setting::select('id','value')->where('key','table_script_synchronization_status')->first();
            $cust_info_sync_status = Setting::select('id','value')->where('key','custinfo_trans_Summary_script_synchronization_status')->first();

            if($tables_sync_status && $tables_sync_status->value == '1' && $cust_info_sync_status && $cust_info_sync_status->value == '0'){

                $tables_sync_status->value = '2';
                $tables_sync_status->save();

                $cust_info_sync_status->value = '1';
                $cust_info_sync_status->save();

                //Sql PatientInfo (Done)
                ScriptActivity::create(['table' => 'htl_cust_info', 'description' => 'htl_cust_info table script started.']);
                $sqlPatientInfo = SqlHtlCustInfo::get();
                if($sqlPatientInfo){
                    foreach ($sqlPatientInfo as $keyCustInfo => $patientInfo) {

                        $custoInfoArr = array();
                        $custoInfoArr['cust_id'] = $patientInfo['CustID'];
                        $custoInfoArr['currency_code'] = $patientInfo['CurrencyCode'];
                        $custoInfoArr['sales_rep_code'] = $patientInfo['SalesRepCode'];
                        $custoInfoArr['payment_term'] = (string)$patientInfo['Payment Term'];
                        $custoInfoArr['source_created_date'] = (string)$patientInfo['CreateDate'];

                        $checkPatientInfo = HtlCustInfo::where('cust_id', $patientInfo['CustID'])->first();
                        if($checkPatientInfo){
                            $patientInfoDiff=array_diff_assoc($checkPatientInfo->toArray(),$custoInfoArr);
                            $PatientInfoKeys = array_keys(Arr::except($patientInfoDiff,['id','created_at','updated_at']));
                            $PatientInfoDataUpdate = Arr::only($custoInfoArr,$PatientInfoKeys);
                            if ($PatientInfoDataUpdate) {
                                HtlCustInfo::where('id',$checkPatientInfo->id)->update($PatientInfoDataUpdate);
                            }
                        }else{
                            HtlCustInfo::create($custoInfoArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_info', 'description' => 'htl_cust_info table script ended.']);
                }

                //Sql CustLatestTrans (Done)
                ScriptActivity::create(['table' => 'htl_cust_latest_trans', 'description' => 'htl_cust_latest_trans table script started.']);
                $sqlCustLatestTrans = SqlHtlCustLatestTrans::get();
                if($sqlCustLatestTrans){
                    foreach ($sqlCustLatestTrans as $keyLatestTransInfo => $latestTransInfo) {

                        $custoLatestTransArr = array();
                        $custoLatestTransArr['cust_id'] = $latestTransInfo['CustID'];
                        $custoLatestTransArr['part_num'] = $latestTransInfo['PartNum'];
                        $custoLatestTransArr['x_part_num'] = $latestTransInfo['XPartNum'];
                        $custoLatestTransArr['order_date'] = $latestTransInfo['OrderDate'];
                        $custoLatestTransArr['ship_by'] = $latestTransInfo['Shipby'];
                        $custoLatestTransArr['order_num'] = $latestTransInfo['OrderNum'];
                        $custoLatestTransArr['order_line'] = $latestTransInfo['OrderLine'];
                        $custoLatestTransArr['currency_code'] = $latestTransInfo['CurrencyCode'];
                        $custoLatestTransArr['surcharge'] = $latestTransInfo['Surcharge'];
                        $custoLatestTransArr['unit_price_surchg'] = $latestTransInfo['unitPrice+Surchg'];
                        $custoLatestTransArr['discount_percent'] = $latestTransInfo['DiscountPercent'];
                        $custoLatestTransArr['net_unit_price'] = $latestTransInfo['NetUnitPrice'];
                        $custoLatestTransArr['selling_quantity'] = $latestTransInfo['SellingQuantity'];
                        $custoLatestTransArr['sales_um'] = $latestTransInfo['SalesUM'];
                        $custoLatestTransArr['total_amt'] = $latestTransInfo['TotalAmt'];
                        $custoLatestTransArr['out_std_qty'] = $latestTransInfo['OutStdQty'];
                        $custoLatestTransArr['gm_percentage'] = $latestTransInfo['GM%'];
                        $custoLatestTransArr['net_unit_price_hkd'] = $latestTransInfo['NetUnitPrice(HKD)'];
                        $custoLatestTransArr['act_cost'] = $latestTransInfo['ActCost'];
                        $custoLatestTransArr['exp_cost'] = $latestTransInfo['ExpCost'];
                        $custoLatestTransArr['act_gm_percentage'] = $latestTransInfo['ActGM%'];
                        $custoLatestTransArr['exp_gm_percentage'] = $latestTransInfo['ExpGM%'];
                        $custoLatestTransArr['date_range'] = $latestTransInfo['DateRange'];
                        $custoLatestTransArr['source_created_date'] = $latestTransInfo['CreateDate'];

                        $checkLatestTrans = HtlCustLatestTrans::where('cust_id', $latestTransInfo['CustID'])->where('part_num', $latestTransInfo['PartNum'])->where('order_num', $latestTransInfo['OrderNum'])->where('order_line',$latestTransInfo['OrderLine'])->first();
                        if($checkLatestTrans){

                            $custLatestTransDiff=array_diff_assoc($checkLatestTrans->toArray(),$custoLatestTransArr);
                            $custLatestTransKeys = array_keys(Arr::except($custLatestTransDiff,['id','created_at','updated_at']));
                            $custLatestTransDataUpdate = Arr::only($custoLatestTransArr,$custLatestTransKeys);
                            if ($custLatestTransDataUpdate) {
                                HtlCustLatestTrans::where('id',$checkLatestTrans->id)->update($custLatestTransDataUpdate);
                            }
                        }else{
                            HtlCustLatestTrans::create($custoLatestTransArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_latest_trans', 'description' => 'htl_cust_latest_trans table script ended.']);
                }

                //Sql CustSummary (Done)
                ScriptActivity::create(['table' => 'htl_cust_summaries', 'description' => 'htl_cust_summaries table script started.']);
                $sqlCustSummary = SqlHtlCustSummary::get();
                if($sqlCustSummary){
                    foreach ($sqlCustSummary as $keyCustSummary => $custSummary) {

                        $custSummaryArr = array();
                        $custSummaryArr['cust_id'] = $custSummary['CustID'];
                        $custSummaryArr['date_range'] = $custSummary['DateRange'];
                        $custSummaryArr['cust_currency'] = $custSummary['CustCurrency'];
                        $custSummaryArr['cust_total_qty'] = $custSummary['CustTotalQty'];
                        $custSummaryArr['cust_total_amt_usd'] = $custSummary['CustTotalAmt(USD)'];
                        $custSummaryArr['cust_total_amt_hkd'] = $custSummary['CustTotalAmt(HKD)'];
                        $custSummaryArr['cust_gm_percentage'] = $custSummary['CustGM%'];
                        $custSummaryArr['source_created_date'] = $custSummary['CreateDate'];

                        $checkCustSummary = HtlCustSummary::where('cust_id', $custSummary['CustID'])->where('date_range', $custSummary['DateRange'])->first();
                        if($checkCustSummary){

                            $custSummaryDiff=array_diff_assoc($checkCustSummary->toArray(),$custSummaryArr);
                            $custSummaryKeys = array_keys(Arr::except($custSummaryDiff,['id','created_at','updated_at']));
                            $custSummaryDataUpdate = Arr::only($custSummaryArr,$custSummaryKeys);
                            if ($custSummaryDataUpdate) {
                                HtlCustSummary::where('id',$checkCustSummary->id)->update($custSummaryDataUpdate);
                            }
                        }else{
                            HtlCustSummary::create($custSummaryArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_summaries', 'description' => 'htl_cust_summaries table script ended.']);
                }

                $cust_info_sync_status->value = '0';
                $cust_info_sync_status->save();

            }else{
                echo "Table script synchronization status is disabled.";echo "<br>";
            }

        }catch(\Illuminate\Database\QueryException $ex){

            $log = ['script' => 'Cust Info-Trans-Summary',
                    'description' => $ex->getMessage()];

            //first parameter passed to Monolog\Logger sets the logging channel name
            $orderLog = new Logger('Cust Info-Trans-Summary Script');
            $orderLog->pushHandler(new StreamHandler(storage_path('logs/script.log')), Logger::INFO);
            $orderLog->info('custSscriptLog', $log);
        }
    }

    public function scriptForCustPartSummaryPriceBook(Request $request){

        try {

            $tables_sync_status = Setting::select('id','value')->where('key','table_script_synchronization_status')->first();
            $cust_part_summary_sync_status = Setting::select('id','value')->where('key','custpart_summary_pricebook_script_synchronization_status')->first();

            if ($tables_sync_status && $tables_sync_status->value == '2' && $cust_part_summary_sync_status && $cust_part_summary_sync_status->value == '0') {

                $cust_part_summary_sync_status->value = '1';
                $cust_part_summary_sync_status->save();

                //Sql CustPartSummary (Done)
                ScriptActivity::create(['table' => 'htl_cust_part_summaries', 'description' => 'htl_cust_part_summaries table script started.']);
                $sqlCustPartSummary = SqlHtlCustPartSummary::get();
                if($sqlCustPartSummary){

                    foreach ($sqlCustPartSummary as $keyCustPartSummary => $custPartSummary) {
                        $custPartSummaryArr = array();
                        $custPartSummaryArr['cust_id'] = $custPartSummary['CustID'];
                        $custPartSummaryArr['date_range'] = $custPartSummary['DateRange'];
                        $custPartSummaryArr['cust_currency'] = $custPartSummary['CustCurrency'];
                        $custPartSummaryArr['part_num'] = $custPartSummary['PartNum'];
                        $custPartSummaryArr['sales_um'] = $custPartSummary['SalesUM'];
                        $custPartSummaryArr['cust_total_qty'] = $custPartSummary['CustTotalQty'];
                        $custPartSummaryArr['cust_total_amt_usd'] = $custPartSummary['CustTotalAmt(USD)'] ?? '0';
                        $custPartSummaryArr['cust_total_amt_hkd'] = $custPartSummary['CustTotalAmt(HKD)'] ?? '0';
                        $custPartSummaryArr['cust_gm_percentage'] = $custPartSummary['CustGM%'];
                        $custPartSummaryArr['source_created_date'] = $custPartSummary['CreateDate'];

                        $checkPartSummary = HtlCustPartSummary::where('cust_id', $custPartSummary['CustID'])->where('date_range', $custPartSummary['DateRange'])->where('part_num', $custPartSummary['PartNum'])->first();
                        if($checkPartSummary){

                            $partSummaryDiff=array_diff_assoc($checkPartSummary->toArray(),$custPartSummaryArr);
                            $partSummaryKeys = array_keys(Arr::except($partSummaryDiff,['id','created_at','updated_at']));
                            $partSummaryDataUpdate = Arr::only($custPartSummaryArr,$partSummaryKeys);
                            if ($partSummaryDataUpdate) {
                                HtlCustPartSummary::where('id',$checkPartSummary->id)->update($partSummaryDataUpdate);
                            }
                        }else{
                            HtlCustPartSummary::create($custPartSummaryArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_part_summaries', 'description' => 'htl_cust_part_summaries table script ended.']);
                }

                //Sql CustPriceBook Done
                ScriptActivity::create(['table' => 'htl_cust_price_books', 'description' => 'htl_cust_price_books table script started.']);
                $sqlCustPriceBook = SqlHtlCustPriceBook::get();
                if($sqlCustPriceBook){

                    foreach ($sqlCustPriceBook as $keycustPriceBook => $custPriceBook) {

                        $custPriceBookArr = array();
                        $custPriceBookArr['cust_id'] = $custPriceBook['CustID'];
                        $custPriceBookArr['list_code'] = $custPriceBook['ListCode'];
                        $custPriceBookArr['part_num'] = $custPriceBook['PartNum'];
                        $custPriceBookArr['cust_part_num'] = $custPriceBook['CustPartNum'];
                        $custPriceBookArr['uom_code'] = $custPriceBook['UOMCode'];
                        $custPriceBookArr['currency_code'] = $custPriceBook['CurrencyCode'];
                        $custPriceBookArr['base_price'] = $custPriceBook['BasePrice'];
                        $custPriceBookArr['price_break'] = $custPriceBook['PriceBreak'];
                        $custPriceBookArr['base_price_usd'] = $custPriceBook['BasePrice(USD)'];
                        $custPriceBookArr['base_price_hkd'] = $custPriceBook['BasePrice(HKD)'];
                        $custPriceBookArr['price_break_usd'] = $custPriceBook['PriceBreak(USD)'];
                        $custPriceBookArr['price_break_hkd'] = $custPriceBook['PriceBreak(HKD)'];
                        $custPriceBookArr['ex_rate_2us'] = $custPriceBook['ExRate2US'];
                        $custPriceBookArr['ex_rate_2hk'] = $custPriceBook['ExRate2HK'];
                        $custPriceBookArr['source_created_date'] = $custPriceBook['CreateDate'];

                        $checkPatientPriceBook = HtlCustPriceBook::where('cust_id', $custPriceBook['CustID'])->where('list_code', $custPriceBook['ListCode'])->where('part_num', $custPriceBook['PartNum'])->where('uom_code', $custPriceBook['UOMCode'])->first();
                        if($checkPatientPriceBook){

                            $custPriceBookDiff=array_diff_assoc($checkPatientPriceBook->toArray(),$custPriceBookArr);
                            $custPriceBookKeys = array_keys(Arr::except($custPriceBookDiff,['id','created_at','updated_at']));
                            $custPriceBookUpdate = Arr::only($custPriceBookArr,$custPriceBookKeys);
                            if ($custPriceBookUpdate) {
                                HtlCustPriceBook::where('id',$checkPatientPriceBook->id)->update($custPriceBookUpdate);
                            }
                        }else{
                            HtlCustPriceBook::create($custPriceBookArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_price_books', 'description' => 'htl_cust_price_books table script ended.']);
                }

                //Sql CustPartInfo
                ScriptActivity::create(['table' => 'htl_cust_part_info', 'description' => 'htl_cust_part_info table script started.']);
                $sqlCustPartInfo = SqlHtlCustPartInfo::get();
                if($sqlCustPartInfo){

                    foreach ($sqlCustPartInfo as $keycustPartInfo => $custPartInfo) {

                        $custPartInfoArr = array();
                        $custPartInfoArr['half_yr_date_range'] = $custPartInfo['HalfYrDateRange'];
                        $custPartInfoArr['1yr_date_range'] = $custPartInfo['1YrDateRange'];
                        $custPartInfoArr['3yr_date_range'] = $custPartInfo['3YrDateRange'];
                        $custPartInfoArr['cust_id'] = $custPartInfo['CustID'];
                        $custPartInfoArr['part_num'] = $custPartInfo['PartNum'];
                        $custPartInfoArr['open_order_qty'] = $custPartInfo['OpenOrderQty'];
                        $custPartInfoArr['open_order_shiped_qty'] = $custPartInfo['OpenOrderShipedQty'];
                        $custPartInfoArr['out_std_qty_on_open_order'] = $custPartInfo['OutstdQtyOnOpenOrder'];
                        $custPartInfoArr['qty_ready_for_shipment'] = $custPartInfo['QtyReadyforShipment'];
                        $custPartInfoArr['6m_avg_sales_qty'] = $custPartInfo['6MAvgSalesQty'];
                        $custPartInfoArr['6m_sales_qty'] = $custPartInfo['6MSalesQty'];
                        $custPartInfoArr['1yr_avg_sales_qty'] = $custPartInfo['1YrAvgSalesQty'];
                        $custPartInfoArr['1yr_sales_qty'] = $custPartInfo['1YrSalesQty'];
                        $custPartInfoArr['3yr_avg_sales_qty'] = $custPartInfo['3YrAvgSalesQty'];
                        $custPartInfoArr['3yr_sales_qty'] = $custPartInfo['3YrSalesQty'];
                        $custPartInfoArr['source_created_date'] = $custPartInfo['CreateDate'];

                        $checkPatientPartInfo = HtlCustPartInfo::where('cust_id', $custPartInfo['CustID'])->where('part_num', $custPartInfo['PartNum'])->first();
                        if($checkPatientPartInfo){

                            $custPartInfoDiff=array_diff_assoc($checkPatientPartInfo->toArray(),$custPartInfoArr);
                            $custPartInfoKeys = array_keys(Arr::except($custPartInfoDiff,['id','created_at','updated_at']));
                            $custPartInfoUpdate = Arr::only($custPartInfoArr,$custPartInfoKeys);
                            if ($custPriceBookUpdate) {
                                HtlCustPartInfo::where('id',$checkPatientPartInfo->id)->update($custPartInfoUpdate);
                            }
                        }else{
                            HtlCustPartInfo::create($custPartInfoArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_cust_part_info', 'description' => 'htl_cust_part_info table script ended.']);
                }

                $cust_part_summary_sync_status->value = '0';
                $cust_part_summary_sync_status->save();

            }else{
                echo "Table script synchronization status is disabled.";echo "<br>";
            }
        }catch(\Illuminate\Database\QueryException $ex){

            $log = ['script' => 'Cust Part-Summary-Price-Book',
                    'description' => $ex->getMessage()];

            //first parameter passed to Monolog\Logger sets the logging channel name
            $orderLog = new Logger('Cust Part-Summary-Price-Book Script');
            $orderLog->pushHandler(new StreamHandler(storage_path('logs/script.log')), Logger::INFO);
            $orderLog->info('custSscriptLog', $log);
        }
    }

    public function scriptForPart(Request $request){

        try {

            $tables_sync_status = Setting::select('id','value')->where('key','table_script_synchronization_status')->first();
            $part_sync_status = Setting::select('id','value')->where('key','part_script_synchronization_status')->first();

            if ($tables_sync_status && $tables_sync_status->value == '2' && $part_sync_status && $part_sync_status->value == '0') {

                $part_sync_status->value = '1';
                $part_sync_status->save();

                //Sql PartInfo (Done)
                ScriptActivity::create(['table' => 'htl_part_infos', 'description' => 'htl_part_infos table script started.']);
                $sqlPartInfo = SqlHtlPartInfo::get();
                if($sqlPartInfo){
                    foreach ($sqlPartInfo as $keyPartInfo => $partInfo) {

                        $partInfoArr = array();
                        $partInfoArr['part_num'] = $partInfo['PartNum'];
                        $partInfoArr['product_barcode'] = $partInfo['ProductBarcode'];
                        $partInfoArr['sales_moq'] = $partInfo['Sales MOQ'];
                        $partInfoArr['pkg_inner'] = $partInfo['Pkg Inner'];
                        $partInfoArr['pkg_in_bag'] = $partInfo['Pkg in Bag'];
                        $partInfoArr['pkg_in_box'] = $partInfo['Pkg in Box'];
                        $partInfoArr['qty_per_master_carton'] = $partInfo['Qty. per Master Carton'];
                        $partInfoArr['pkg_cbm'] = $partInfo['PkgCBM'];
                        $partInfoArr['pkg_nw'] = $partInfo['PkgNW'];
                        $partInfoArr['pkg_gw'] = $partInfo['PKGGW'];
                        $partInfoArr['hs_code'] = $partInfo['HSCode'];
                        $partInfoArr['default_cust_id'] = $partInfo['DefaultCustID'];
                        $partInfoArr['sold_to_cust_id'] = $partInfo['SoldToCustID'];
                        $partInfoArr['price_list_code'] = $partInfo['PriceListCode'];
                        $partInfoArr['currency_code'] = $partInfo['CurrencyCode'];
                        $partInfoArr['unit_price'] = $partInfo['Unit Price'];
                        $partInfoArr['ex_rate_2us'] = $partInfo['ExRate2US'];
                        $partInfoArr['ex_rate_2hk'] = $partInfo['ExRate2HK'];
                        $partInfoArr['unit_price_usd'] = $partInfo['UnitPrice(USD)'];
                        $partInfoArr['unit_price_hkd'] = $partInfo['UnitPrice(HKD)'];
                        $partInfoArr['list_unit_price_usd'] = $partInfo['ListUnitPrice(USD)'];
                        $partInfoArr['list_unit_price_hkd'] = $partInfo['ListUnitPrice(HKD)'];
                        $partInfoArr['sales_um'] = $partInfo['SalesUM'];
                        $partInfoArr['ium'] = $partInfo['IUM'];
                        $partInfoArr['on_hand'] = $partInfo['OnHand'];
                        $partInfoArr['balance'] = $partInfo['Balance'];
                        $partInfoArr['iuom_2sales_uom'] = $partInfo['IUOM2SalesUOM'];
                        $partInfoArr['part_description'] = $partInfo['PartDescription'];
                        $partInfoArr['brand'] = $partInfo['Brand'];
                        $partInfoArr['parent'] = $partInfo['Parent'];
                        $partInfoArr['child'] = $partInfo['Child'];
                        $partInfoArr['sales_cat'] = $partInfo['SalesCAT'];
                        $partInfoArr['favor'] = $partInfo['Favor'];
                        $partInfoArr['stk_cat'] = $partInfo['STK_CAT'];
                        $partInfoArr['buyer_id'] = $partInfo['BuyerID'];
                        $partInfoArr['supplier_id'] = $partInfo['SupplierID'];
                        $partInfoArr['image_id'] = $partInfo['ImageId'];
                        $partInfoArr['source_created_date'] = $partInfo['CreateDate'];

                        $checkPartInfo = HtlPartInfo::where('part_num', $partInfo['PartNum'])->where('sold_to_cust_id',$partInfo['SoldToCustID'])->where('product_barcode',$partInfo['ProductBarcode'])->first();
                        if($checkPartInfo){

                            $partInfoDiff=array_diff_assoc($checkPartInfo->toArray(),$partInfoArr);
                            $partInfoKeys = array_keys(Arr::except($partInfoDiff,['id','created_at','updated_at']));
                            $partInfoUpdate = Arr::only($partInfoArr,$partInfoKeys);
                            if ($partInfoUpdate) {
                               HtlPartInfo::where('id',$checkPartInfo->id)->update($partInfoUpdate);

                            }
                        }else{
                            HtlPartInfo::create($partInfoArr);
                        }

                    }
                    ScriptActivity::create(['table' => 'htl_part_infos', 'description' => 'htl_part_infos table script ended.']);
                }

                //Sql ExRate (Done)
                ScriptActivity::create(['table' => 'htl_ex_rates', 'description' => 'htl_ex_rates table script started.']);
                $sqlExRate = SqlHtlExRate::get();
                if($sqlExRate){
                    foreach ($sqlExRate as $keyExRate => $exRate) {

                        $exRateArr = array();
                        $exRateArr['company'] = $exRate['Company'];
                        $exRateArr['source_curr_code'] = $exRate['SourceCurrCode'];
                        $exRateArr['current_rate'] = $exRate['CurrentRate'];
                        $exRateArr['effective_date'] = $exRate['EffectiveDate'];
                        $exRateArr['source_created_date'] = $exRate['CreateDate'];

                        $checkExRate = HtlExRate::where('company', $exRate['Company'])->where('source_curr_code', $exRate['SourceCurrCode'])->first();
                        if($checkExRate){

                            $exRateDiff=array_diff_assoc($checkExRate->toArray(),$exRateArr);
                            $exRateKeys = array_keys(Arr::except($exRateDiff,['id','created_at','updated_at']));
                            $exRateUpdate = Arr::only($exRateArr,$exRateKeys);
                            if ($exRateUpdate) {
                                HtlExRate::where('id',$checkExRate->id)->update($exRateUpdate);
                            }
                        }else{
                            HtlExRate::create($exRateArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_ex_rates', 'description' => 'htl_ex_rates table script ended.']);
                }

                //Sql PartCost (Done)
                ScriptActivity::create(['table' => 'htl_part_costs', 'description' => 'htl_part_costs table script started.']);
                $sqlPartCost = SqlHtlPartCost::get();
                if($sqlPartCost){
                    foreach ($sqlPartCost as $keyPartCost => $partCost) {

                        $partCostArr = array();
                        $partCostArr['product_barcode'] = $partCost['ProductBarcode'];
                        $partCostArr['part_num'] = $partCost['PartNum'];
                        $partCostArr['imu'] = $partCost['IUM'];
                        $partCostArr['avg_unit_cost'] = $partCost['AvgUnitCost'];
                        $partCostArr['last_unit_cost'] = $partCost['LastUnitCost'];
                        $partCostArr['part_cost'] = $partCost['PartCost'];
                        $partCostArr['max_part_cost'] = $partCost['MaxPartCost'];
                        $partCostArr['min_part_cost'] = $partCost['MinPartCost'];
                        $partCostArr['source_created_date'] = $partCost['CreateDate'];

                        $checkPartCost = HtlPartCost::where('part_num', $partCost['PartNum'])->first();
                        if($checkPartCost){

                            $exRateDiff=array_diff_assoc($checkPartCost->toArray(),$partCostArr);
                            $partCostKeys = array_keys(Arr::except($exRateDiff,['id','created_at','updated_at']));
                            $partCostUpdate = Arr::only($partCostArr,$partCostKeys);
                            if ($partCostUpdate) {
                                HtlPartCost::where('id',$checkPartCost->id)->update($partCostUpdate);
                            }
                        }else{
                            HtlPartCost::create($partCostArr);
                        }
                    }
                    ScriptActivity::create(['table' => 'htl_part_costs', 'description' => 'htl_part_costs table script ended.']);
                }

                $part_sync_status->value = '0';
                $part_sync_status->save();

            }else{
                echo "Table script synchronization status is disabled.";echo "<br>";
            }

        } catch(\Illuminate\Database\QueryException $ex){

            $log = ['script' => 'Part Info',
                    'description' => $ex->getMessage()];

            //first parameter passed to Monolog\Logger sets the logging channel name
            $orderLog = new Logger('Part Script');
            $orderLog->pushHandler(new StreamHandler(storage_path('logs/script.log')), Logger::INFO);
            $orderLog->info('partsScriptLog', $log);
        }

    }

    public function scriptForPartImage(Request $request){


        try {

            $tables_sync_status = Setting::select('id','value')->where('key','table_script_synchronization_status')->first();
            $part_images_sync_status = Setting::select('id','value')->where('key','partimage_script_synchronization_status')->first();

            if ($tables_sync_status && $tables_sync_status->value == '2' && $part_images_sync_status && $part_images_sync_status->value == '0') {

                $part_images_sync_status->value = '1';
                $part_images_sync_status->save();

                ScriptActivity::create(['table' => 'htl_part_images', 'description' => 'htl_part_images table script started.']);
                //Sql PartImage
                $sqlPartImage = SqlHtlPartImage::select('PartNum','ImageId','ImageCategoryId','ImageSubCategoryId','FileName', 'CreatedOn','ModifiedOn','CreateDate')->get();

                if($sqlPartImage){
                    foreach ($sqlPartImage as $keyPartImage => $partImage) {

                        $partImageContent = SqlHtlPartImage::where('ImageId', $partImage['ImageId'])->select('Content')->first();

                        $imagepathName = str_replace("/","-",$partImage['FileName']);
                        $result = imagecreatefromstring($partImageContent->Content);

                        $img = imagejpeg($result, storage_path('app/public/part_images/'.$imagepathName.'.jpg'));
                        $imageinfo = getimagesize(storage_path('app/public/part_images/'.$imagepathName.'.jpg'));

                        if($imageinfo[0] > 800 || $imageinfo[1] > 800){

                            //0 = width, 1=height
                            if($imageinfo[0] > $imageinfo[1]){
                                $imgWidth = 800;
                                $imgHeight = -1;
                            }else{
                                $imgWidth = -1;
                                $imgHeight = 800;
                            }
                            $img_resize = imagescale($result,$imgWidth, $imgHeight);
                            $destination = imagejpeg($img_resize, storage_path('app/public/part_images_resize/'.$imagepathName.'.jpg'));
                        }else{
                            $img = imagejpeg($result, storage_path('app/public/part_images_resize/'.$imagepathName.'.jpg'));
                        }

                        $partImageArr = array();
                        $partImageArr['part_num'] = $partImage['PartNum'];
                        $partImageArr['image_id'] = $partImage['ImageId'];
                        $partImageArr['image_category_id'] = $partImage['ImageCategoryId'];
                        $partImageArr['image_sub_category_id'] = $partImage['ImageSubCategoryId'];
                        $partImageArr['file_name'] = $partImage['FileName'];
                        $partImageArr['created_on'] = $partImage['CreatedOn'];
                        $partImageArr['modified_on'] = $partImage['ModifiedOn'];
                        $partImageArr['content'] = 'part_images_resize/'.$imagepathName.'.jpg';
                        $partImageArr['content_original'] = 'part_images/'.$imagepathName.'.jpg';
                        $partImageArr['source_created_date'] = $partImage['CreateDate'];

                        $checkpartImage = HtlPartImage::where('part_num', $partImage['PartNum'])->where('image_id', $partImage['ImageId'])->first();

                        if($checkpartImage){

                            $partImageDiff=array_diff_assoc($checkpartImage->toArray(),$partImageArr);

                            $partImageKeys = array_keys(Arr::except($partImageDiff,['id','created_at','updated_at']));

                            $partImageUpdate = Arr::only($partImageArr,$partImageKeys);

                            if ($partImageUpdate) {
                                HtlPartImage::where('id',$checkpartImage->id)->update($partImageUpdate);
                            }
                        }else{
                            HtlPartImage::create($partImageArr);
                        }
                    }

                    $checkImageSizeSetting = Setting::where('key','product_all_images_size')->first();
                    $partImagesSize = 0;
                    $dir_part_images = storage_path('app\public\part_images_resize');

                    foreach (glob(rtrim($dir_part_images, '/').'/*', GLOB_NOSORT) as $eachPartImage) {

                        $partImagesSize += filesize($eachPartImage);
                    }

                    if($checkImageSizeSetting){
                        $checkImageSizeSetting->value = $partImagesSize;
                        $checkImageSizeSetting->save();
                    }else{
                        $settingsArr = array();
                        $settingsArr['key'] = 'product_all_images_size';
                        $settingsArr['value'] = $partImagesSize;
                        Setting::create($settingsArr);
                    }
                    ScriptActivity::create(['table' => 'htl_part_images', 'description' => 'htl_part_images table script ended.']);
                }

                $this->export_json_database_to_storage();
            }else{
                echo "Table script synchronization status is disabled.";echo "<br>";
            }

        }catch(\Illuminate\Database\QueryException $ex){

            $log = ['script' => 'Part Image',
                    'description' => $ex->getMessage()];

            //first parameter passed to Monolog\Logger sets the logging channel name
            $orderLog = new Logger('Part Image Script');
            $orderLog->pushHandler(new StreamHandler(storage_path('logs/script.log')), Logger::INFO);
            $orderLog->info('partImageScriptLog', $log);
        }
    }

    public function export_json_database_to_storage(){

        $tables_sync_status = Setting::select('id','value')->where('key','table_script_synchronization_status')->first();
        $part_images_sync_status = Setting::select('id','value')->where('key','partimage_script_synchronization_status')->first();

        if ($tables_sync_status && $tables_sync_status->value == '2' && $part_images_sync_status && $part_images_sync_status->value == '1') {

            ScriptActivity::create(['table' => 'htl_table_json_data', 'description' => 'htl_table_json_data table script started.']);
            $datacount = HtlTableJsonData::select('table_name','created_at')->count();

            if($datacount == 30){

                $fetchLastRecords = HtlTableJsonData::orderBy('id', 'asc')->take(10)->get();
                if($fetchLastRecords){
                    foreach ($fetchLastRecords as $key => $tableRecords) {

                        Storage::delete($tableRecords->file_url);
                        $tableRecords->delete();
                    }
                }
            }

            $path = "htl-json-files/";
            if(!Storage::exists($path)){
                Storage::makeDirectory($path);
            }

            $jsonDatabaseInsertArr = array();

            $cust_info_file_name = $path.'htl_cust_info_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $cust_info = Storage::put($cust_info_file_name, HtlCustInfo::all()->toJson());
            $jsonDatabaseInsertArr0['table_name'] = 'htl_cust_info';
            $jsonDatabaseInsertArr0['file_url'] = $cust_info_file_name;
            $jsonDatabaseInsertArr0['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr0);

            $cust_latest_trans_file_name = $path.'htl_cust_latest_trans_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $cust_latest_trans = Storage::put($cust_latest_trans_file_name, HtlCustLatestTrans::all()->toJson());
            $jsonDatabaseInsertArr1['table_name'] = 'htl_cust_latest_trans';
            $jsonDatabaseInsertArr1['file_url'] = $cust_latest_trans_file_name;
            $jsonDatabaseInsertArr1['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr1);

            $cust_pat_summary_file_name = $path.'htl_cust_part_summaries_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $part_summary = Storage::put($cust_pat_summary_file_name, HtlCustPartSummary::all()->toJson());
            $jsonDatabaseInsertArr2['table_name'] = 'htl_cust_part_summaries';
            $jsonDatabaseInsertArr2['file_url'] = $cust_pat_summary_file_name;
            $jsonDatabaseInsertArr2['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr2);


            $cust_price_book_file_name = $path.'htl_cust_price_books_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $price_book = Storage::put($cust_price_book_file_name, HtlCustPriceBook::all()->toJson());
            $jsonDatabaseInsertArr3['table_name'] = 'htl_cust_price_books';
            $jsonDatabaseInsertArr3['file_url'] = $cust_price_book_file_name;
            $jsonDatabaseInsertArr3['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr3);

            $cust_part_info_file_name = $path.'htl_cust_part_info_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $cust_part_info = Storage::put($cust_part_info_file_name, HtlCustPartInfo::all()->toJson());
            $jsonDatabaseInsertArr10['table_name'] = 'htl_cust_part_info';
            $jsonDatabaseInsertArr10['file_url'] = $cust_part_info_file_name;
            $jsonDatabaseInsertArr10['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr10);


            $cust_summary_file_name = $path.'htl_cust_summaries_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $cust_summary = Storage::put($cust_summary_file_name, HtlCustSummary::all()->toJson());
            $jsonDatabaseInsertArr4['table_name'] = 'htl_cust_summaries';
            $jsonDatabaseInsertArr4['file_url'] = $cust_summary_file_name;
            $jsonDatabaseInsertArr4['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr4);



            $ex_rate_file_name = $path.'htl_ex_rates_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $ex_rate = Storage::put($ex_rate_file_name, HtlExRate::all()->toJson());
            $jsonDatabaseInsertArr5['table_name'] = 'htl_ex_rates';
            $jsonDatabaseInsertArr5['file_url'] = $ex_rate_file_name;
            $jsonDatabaseInsertArr5['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr5);



            $htl_part_costs_file_name = $path.'htl_part_costs_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $part_cost = Storage::put($htl_part_costs_file_name, HtlPartCost::all()->toJson());
            $jsonDatabaseInsertArr6['table_name'] = 'htl_part_costs';
            $jsonDatabaseInsertArr6['file_url'] = $htl_part_costs_file_name;
            $jsonDatabaseInsertArr6['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr6);



            $htl_part_images_file_name = $path.'htl_part_images_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $part_image = Storage::put($htl_part_images_file_name, HtlPartImage::all()->toJson());
            $jsonDatabaseInsertArr7['table_name'] = 'htl_part_images';
            $jsonDatabaseInsertArr7['file_url'] = $htl_part_images_file_name;
            $jsonDatabaseInsertArr7['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr7);

            $htl_part_infos_file_name = $path.'htl_part_infos_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $part_info = Storage::put($htl_part_infos_file_name, HtlPartInfo::all()->toJson());
            $jsonDatabaseInsertArr8['table_name'] = 'htl_part_infos';
            $jsonDatabaseInsertArr8['file_url'] = $htl_part_infos_file_name;
            $jsonDatabaseInsertArr8['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr8);

            $htl_users_file_name = $path.'users_'. Carbon::now()->format('d_M_Y_H_i_s').'.json';
            $userinfo = Storage::put($htl_users_file_name, User::all()->toJson());
            $jsonDatabaseInsertArr9['table_name'] = 'users';
            $jsonDatabaseInsertArr9['file_url'] = $htl_users_file_name;
            $jsonDatabaseInsertArr9['status'] = '1';
            HtlTableJsonData::create($jsonDatabaseInsertArr9);

            $part_images_sync_status->value = '0';
            $part_images_sync_status->save();

            $tables_sync_status->value = '0';
            if ($tables_sync_status->save()) {
                ScriptActivity::create(['table' => 'htl_table_json_data', 'description' => 'htl_table_json_data table script ended.']);
            }
        }else{
            echo "Table script synchronization status is disabled.";echo "<br>";
        }
    }

    public function scriptForServerTime(Request $request){

        dd(date("Y-m-d H:i:s"));
    }

    public function scriptRunningStatus(Request $request){

        if ( app()->runningInConsole() ){
            dd('Script running');// it's console.
        }
        dd('Script not running.');
    }

    public function scriptCheck(Request $request){

        $sqlPartInfo = SqlHtlPartInfo::get();
        $createCount = 0;
        $updateCount = 0;
        foreach ($sqlPartInfo as $keyPartInfo => $partInfo) {

            $partInfoArr = array();
            $partInfoArr['part_num'] = $partInfo['PartNum'];
            $partInfoArr['product_barcode'] = $partInfo['ProductBarcode'];
            $partInfoArr['sales_moq'] = $partInfo['Sales MOQ'];
            $partInfoArr['pkg_inner'] = $partInfo['Pkg Inner'];
            $partInfoArr['qty_per_master_carton'] = $partInfo['Qty. per Master Carton'];
            $partInfoArr['pkg_cbm'] = $partInfo['PkgCBM'];
            $partInfoArr['pkg_nw'] = $partInfo['PkgNW'];
            $partInfoArr['pkg_gw'] = $partInfo['PKGGW'];
            $partInfoArr['hs_code'] = $partInfo['HSCode'];
            $partInfoArr['default_cust_id'] = $partInfo['DefaultCustID'];
            $partInfoArr['sold_to_cust_id'] = $partInfo['SoldToCustID'];
            $partInfoArr['price_list_code'] = $partInfo['PriceListCode'];
            $partInfoArr['currency_code'] = $partInfo['CurrencyCode'];
            $partInfoArr['unit_price'] = $partInfo['Unit Price'];
            $partInfoArr['ex_rate_2us'] = $partInfo['ExRate2US'];
            $partInfoArr['ex_rate_2hk'] = $partInfo['ExRate2HK'];
            $partInfoArr['unit_price_usd'] = $partInfo['UnitPrice(USD)'];
            $partInfoArr['unit_price_hkd'] = $partInfo['UnitPrice(HKD)'];
            $partInfoArr['list_unit_price_usd'] = $partInfo['ListUnitPrice(USD)'];
            $partInfoArr['list_unit_price_hkd'] = $partInfo['ListUnitPrice(HKD)'];
            $partInfoArr['sales_um'] = $partInfo['SalesUM'];
            $partInfoArr['ium'] = $partInfo['IUM'];
            $partInfoArr['on_hand'] = $partInfo['OnHand'];
            $partInfoArr['balance'] = $partInfo['Balance'];
            $partInfoArr['iuom_2sales_uom'] = $partInfo['IUOM2SalesUOM'];
            $partInfoArr['part_description'] = $partInfo['PartDescription'];
            $partInfoArr['brand'] = $partInfo['Brand'];
            $partInfoArr['parent'] = $partInfo['Parent'];
            $partInfoArr['child'] = $partInfo['Child'];
            $partInfoArr['sales_cat'] = $partInfo['SalesCate'];
            $partInfoArr['favor'] = $partInfo['Favor'];
            $partInfoArr['stk_cat'] = $partInfo['STK_CAT'];
            $partInfoArr['buyer_id'] = $partInfo['BuyerID'];
            $partInfoArr['supplier_id'] = $partInfo['SupplierID'];
            $partInfoArr['image_id'] = $partInfo['ImageId'];
            $partInfoArr['source_created_date'] = $partInfo['CreateDate'];

            $checkPartInfo = HtlPartInfo::where('part_num', $partInfo['PartNum'])->where('sold_to_cust_id',$partInfo['SoldToCustID'])->where('product_barcode',$partInfo['ProductBarcode'])->first();
            if($checkPartInfo){

                $partInfoDiff=array_diff_assoc($checkPartInfo->toArray(),$partInfoArr);
                $partInfoKeys = array_keys(Arr::except($partInfoDiff,['id','created_at','updated_at']));
                $partInfoUpdate = Arr::only($partInfoArr,$partInfoKeys);
                if ($partInfoUpdate) {
                    $updateCount = $updateCount + 1;
                }
            }else{
                HtlPartInfo::create($partInfoArr);
                dd('done');
                $createCount = $createCount + 1;
                echo "<pre>";print_r('ProductBarcode = '.$partInfo['ProductBarcode']);echo "<br>";
                            print_r('PartNum = '.$partInfo['PartNum']);echo "<br>";
                            print_r('SoldToCustID = '.$partInfo['SoldToCustID']);echo "<br>";
            }
        }
        echo "<pre>";print_r('Total Created = '.$createCount);echo "<br>";
                    print_r('Total Updated = '.$updateCount);echo "<br>";
                    print_r('Total Records for Source = '.count($sqlPartInfo));
                    $totale = $createCount+$updateCount;
                    print_r('Total Created + Total Updated = '.$totale);
                    exit();
    }
}