<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TrackRecord;
use App\Models\HtlPartInfo;
use Auth;
use Storage;
use Hash;
use Response,DB;

class ReportingController extends Controller
{
    public function reportingList(Request $request) {
        return view('admin.reporting.list',array('title' => 'Reporting List'));
    }

    public function listdata(Request $request) {
        // echo "<pre>";print_r($request->all());exit();
        $columns = array(
            0 => 'id',
            1 => 'salesman_id',
            2 => 'session_id',
            3 => 'cust_erp_id',
            5 => 'date_time',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if (empty($request->input('patient_id')) && empty($request->input('start_date')) && empty($request->input('end_date')) && empty($request->input('clinic_user_id'))){
                $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval(0),
                    "recordsFiltered" => intval(0),
                    "data"            => []
                    );

                echo json_encode($json_data);return false;
        }

        $reporting = TrackRecord::with('salesman')->select('session_id','cust_id','salesman_id','cust_erp_id',DB::raw('count(DISTINCT  product_id) as product_count,max(date_time) as last_date,min(date_time) as first_date'));

        if (!empty($request->input('clinic_user_id'))) {
            $reporting = $reporting->whereIn('salesman_id',$request->input('clinic_user_id'));
        }
        if (!empty($request->input('patient_id'))) {
            $reporting = $reporting->whereIn('cust_id',$request->input('patient_id'));
            // echo "<pre>";print_r($reporting);exit();
        }
        if (!empty($request->input('start_date')) && !empty($request->input('end_date'))) {
            $from =date('Y-m-d H:i:s', strtotime($request->input('start_date')));
            $to = date('Y-m-d H:i:s', strtotime($request->input('end_date')));
            $reporting = $reporting->whereBetween('date_time', [$from, $to]);
        }
        $totalData = $reporting->distinct()->count('session_id');

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $reporting=$reporting->where(function ($query) use ($search) {
                $query->where('session_id','LIKE',"%{$search}%");
            });

            $totalFiltered =  $reporting->distinct()->count('session_id');
        }

        $reporting =  $reporting->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->groupBy('session_id','cust_id','salesman_id','cust_erp_id')
                    ->get();

        // echo "<pre>";print_r($reporting);exit();
        $data = array();
        if(!empty($reporting))
        {
            $sr_no = '1';
            foreach ($reporting as $report)
            {
                if (empty($request->input('start_date')) && empty($request->input('end_date'))) {
                    $date = TrackRecord::where('salesman_id',$request->input('clinic_user_id'))->where('cust_id',$report->cust_id)->select([ DB::raw('MIN(date_time) as firstdate'),DB::raw('MAX(date_time) as lastdate')])->first();
                    if($date->firstdate == $date->lastdate){
                        $reportingDate = date('d-M-Y H:i:s', strtotime($date->firstdate));
                    }else{
                        $reportingDate = date('d-M-Y H:i:s', strtotime($date->firstdate)).' / '.date('d-M-Y H:i:s', strtotime($date->lastdate));
                    }
                }else{
                    $reportingDate = date('d-M-Y H:i:s', strtotime($request->input('start_date'))).' / '.date('d-M-Y H:i:s', strtotime($request->input('end_date')));
                }
                $nestedData['id'] = $report->id;
                $nestedData['srno'] = $sr_no;
                $nestedData['session_id'] = $report->session_id;
                $nestedData['cust_id'] = $report->cust_id;
                $nestedData['date_time'] = $reportingDate;
                $nestedData['cust_erp_id'] = $report->cust_erp_id;
                $nestedData['product_count'] = $report->product_count;
                $nestedData['clinic_user'] = $report->salesman->name;
                $nestedData['salesman_id'] = $report->salesman->id;
                $nestedData['actions'] = $report->id;
                $data[] = $nestedData;
                $sr_no++;
            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);
    }

    public function reportingGetPatient(Request $request) {
        $patients = User::where('user_type', '2');
        if(isset($request['q']) && !empty($request['q'])){
            $keyword=$request['q'];
            $patients=$patients->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('erp_id', 'like', '%'.$keyword.'%');
            });
        }

        $patients = $patients->get();
        //dd($patients);
        $count = 0;
        if($patients){

            foreach ($patients as $key => $patient) {

                $carData = $patient->patientCars;

                $result['items'][] = array(
                    "id" => $patient->id,
                    //"hashid" => $patient->hashid,
                    "name" => $patient->name,
                    "email" => $patient->email,
                    "erp_id" => $patient->erp_id,
                );
                $count++;
            }
        }

        $result['total_count'] = $count;
        $result['incomplete_results'] = true;
        return Response::json($result);
    }

    public function reportingGetClinicUser(Request $request) {
        $patients = User::where('user_type', '1');
        if(isset($request['q']) && !empty($request['q'])){
            $keyword=$request['q'];
            $patients=$patients->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('erp_id', 'like', '%'.$keyword.'%');
            });
        }

        $patients = $patients->get();
        //dd($patients);
        $count = 0;
        if($patients){

            foreach ($patients as $key => $patient) {

                $carData = $patient->patientCars;

                $result['items'][] = array(
                    "id" => $patient->id,
                    "name" => $patient->name,
                    "email" => $patient->email,
                    "erp_id" => $patient->erp_id,
                );
                $count++;
            }
        }

        $result['total_count'] = $count;
        $result['incomplete_results'] = true;
        return Response::json($result);
    }

    public function productList(Request $request) {

        return view('admin.reporting.product_list',array('title' => 'Product List','session_id'=>$request->session_id ,'cust_id'=>$request->cust_id ?? '','clinic_user_id'=>$request->clinic_user_id ?? '','start_date'=>$request->start_date ?? '','end_date'=>$request->end_date ?? '', 'breadcrumb' => array(
                array('title' => 'Reporting List', 'link' => route('admin.reporting.list')))));
    }

    public function productListData(Request $request) {
        // echo "<pre>";print_r($request->all());exit();
        $columns = array(
            0 => 'id',
            1 => 'part_description',
            2 => 'product_barcode',
            3 => 'part_num',
            4 => 'date_time',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $trackRecord = TrackRecord::where('session_id',$request->session_id);
        if (isset($request->cust_id) && $request->cust_id != 'null') {
            $trackRecord = $trackRecord->where('cust_id',$request->cust_id);
        }else{
            $trackRecord = $trackRecord->whereNull('cust_id');
        }
        if (isset($request->clinic_user_id) && $request->clinic_user_id != 'null') {
            $trackRecord = $trackRecord->where('salesman_id',$request->clinic_user_id);
        }
        if (isset($request->start_date) && $request->start_date != 'null' && isset($request->end_date) && $request->end_date != 'null') {
            $from =date('Y-m-d H:i:s', strtotime($request->start_date));
            $to = date('Y-m-d H:i:s', strtotime($request->end_date));
            $trackRecord = $trackRecord->whereBetween('date_time', [$from, $to]);
        }
        $product_id = $trackRecord->pluck('product_id')->toArray();
        $products = HtlPartInfo::whereIn('id',array_unique($product_id));
        $totalData = $products->count();

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $products=$products->where(function ($query) use ($search) {
                $query->where('part_description','LIKE',"%{$search}%")
                      ->orWhere('product_barcode','LIKE',"%{$search}%")
                      ->orWhere('part_num','LIKE',"%{$search}%");
            });

            $totalFiltered =  $products->count();
        }

        $products =  $products->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

        // echo "<pre>";print_r($products);exit();
        $data = array();
        if(!empty($products))
        {
            $sr_no = '1';
            foreach ($products as $product)
            {
                $date = TrackRecord::where('session_id',$request->session_id)->where('salesman_id',$request->clinic_user_id)->where('cust_id',$request->cust_id == 'null'? NULL : $request->cust_id)->where('product_id', $product->id)->select('date_time')->first();
                $reportingDate = date('d-M-Y H:i:s', strtotime($date->date_time));
                $nestedData['id'] = $product->id;
                $nestedData['srno'] = $sr_no;
                $nestedData['product_barcode'] = $product->product_barcode;
                $nestedData['part_num'] = $product->part_num;
                $nestedData['part_description'] = $product->part_description;
                $nestedData['date_time'] = $reportingDate;
                $nestedData['actions'] = $product->id;
                $data[] = $nestedData;
                $sr_no++;
            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);
    }

    public function productDetails(Request $request,$product_id) {

        $session_id = TrackRecord::where('product_id',$product_id)->first()->session_id ?? '';
        // if (!$session_id) {
        //     abort(404);
        // }
        $product = HtlPartInfo::find($product_id);

        // echo "<pre>";print_r($product);exit();
        return view('admin.reporting.product_details',array('title' => 'Product Details','product'=>$product, 'breadcrumb' => array(
                array('title' => 'Product List', 'link' => url()->previous()))));
    }
}
