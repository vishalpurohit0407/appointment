<?php

namespace App\Http\Controllers\clinic_user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TrackRecord;
use App\Models\HtlPartInfo;
use Auth;
use Storage;
use Hash;
use Response,DB;
use Illuminate\Support\Facades\File;

class ClinicUserController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard',array('title' => 'dashboard','breadcrumb' => array()));
    }

    public function getChangePass() {
        return view('clinic_user.profile.changepass',array('title' => 'Change Password'));
    }

    public function changePass(Request $request) {
        $messages = [
        	'currentpass.required' => 'The Current Password field is required.',
            'newpass.required' => 'The New Password field is required.',
            'newpass.min' => 'The New Password must be at least 6 characters.',
            'newpass.confirmed' => 'The New Password and Confirm Password does not match.',
            'newpass_confirmation.required' => 'The Confirm Password field is required.',
        ];

      	$request->validate([
        	'currentpass' => 'required',
        	'newpass' => 'required|min:8|confirmed',
        	'newpass_confirmation' => 'required|min:8',
      	], $messages);

        $userData = User::find(Auth::id());
        if(md5($request->get('currentpass')) != $userData->password){
            $request->session()->flash('alert-danger','Please enter valid current password.');
            return redirect(route('changepass'));
        }

        $userData->password = md5($request->get('newpass'));
        if($userData->save()){
            Auth::logout();
            return redirect()->route('login');
            $request->session()->flash('alert-success','Password changed successfully.');
        }

        return redirect(route('changepass'));
    }

    public function profile() {
        $userData = User::find(Auth::id());
      	return view('clinic_user.profile.editprofile',array('title' => 'Edit Profile','userData' => $userData));
    }

    public function updateProfile(Request $request) {
      	$request->validate([
            'provider_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'mobile' => 'required|min:10',
        ],[
            'erp_id.required' => 'The sales person id field is required.',
            'erp_id.unique' => 'The sales person id has already been taken.',
        ]);

        $userData = User::find(Auth::id());
        $userData->provider_name = $request->provider_name;
        $userData->name = $request->name;
        $userData->mobile = $request->mobile;
        $userData->email = $request->email;

        if($userData->save()){
            $request->session()->flash('alert-success','Profile updated successfully.');
        }
        return redirect(route('editprofile'));
    }

    public function reportingList(Request $request) {
        return view('clinic_user.reporting.list',array('title' => 'Reporting List'));
    }

    public function listdata(Request $request) {
        $columns = array(
            0 => 'id',
            1 => 'session_id',
            2 => 'cust_erp_id',
            4 => 'date_time',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('patient_id')) && empty($request->input('start_date')) && empty($request->input('end_date'))){
            $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval(0),
                    "recordsFiltered" => intval(0),
                    "data"            => []
                    );

            echo json_encode($json_data);return false;
        }

        $reporting = TrackRecord::where('salesman_id',Auth::user()->id)->select('session_id','cust_id','cust_erp_id',DB::raw('count(DISTINCT  product_id) as product_count'));

        if (!empty($request->input('patient_id'))) {
            $reporting = $reporting->whereIn('cust_id',$request->input('patient_id'));
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
                $query->where('session_id','LIKE',"%{$search}%")
                      ->orWhere('cust_id','LIKE',"%{$search}%")
                      ->orWhere('date_time','LIKE',"%{$search}%")
                      ->orWhere('cust_erp_id','LIKE',"%{$search}%");
            });

            $totalFiltered =  $reporting->distinct()->count('session_id');
        }

        $reporting =  $reporting->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->groupBy('session_id','cust_id','cust_erp_id')
                    ->get();


        // echo "<pre>";print_r($reporting);exit();
        $data = array();
        if(!empty($reporting))
        {
            $sr_no = '1';
            foreach ($reporting as $report)
            {
                if (empty($request->input('start_date')) && empty($request->input('end_date'))) {
                    $date = TrackRecord::where('cust_id',$report->cust_id)->select([ DB::raw('MIN(date_time) as firstdate'),DB::raw('MAX(date_time) as lastdate')])->first();
                    if($date->firstdate == $date->lastdate){
                        $reportingDate = $date->firstdate;
                    }else{
                        $reportingDate = $date->firstdate.' / '.$date->lastdate;
                    }
                }else{
                    $reportingDate = date('d-M-Y H:i:s', strtotime($request->input('start_date'))).' / '.date('d-M-Y H:i:s', strtotime($request->input('end_date')));
                }
                $nestedData['id'] = $report->id;
                $nestedData['srno'] = $sr_no;
                $nestedData['session_id'] = $report->session_id;
                $nestedData['cust_id'] = $report->cust_id;
                $nestedData['cust_erp_id'] = $report->cust_erp_id;
                $nestedData['date_time'] = $reportingDate;
                $nestedData['product_count'] = $report->product_count;
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

    public function productList(Request $request) {

        return view('clinic_user.reporting.product_list',array('title' => 'Product List','session_id'=>$request->session_id ,'cust_id'=>$request->cust_id ?? '','start_date'=>$request->start_date ?? '','end_date'=>$request->end_date ?? '', 'breadcrumb' => array(
                array('title' => 'Reporting List', 'link' => route('reporting.list')))));
    }

    public function productListData(Request $request) {
        // echo "<pre>";print_r($request->all());exit();
        $columns = array(
            0 => 'id',
            1 => 'part_description',
            2 => 'product_barcode',
            3 => 'part_num',
            4 => 'part_num',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $trackRecord = TrackRecord::where('salesman_id',Auth::user()->id)->where('session_id',$request->session_id);
        if (isset($request->cust_id) && $request->cust_id != 'null') {
            $trackRecord = $trackRecord->where('cust_id',$request->cust_id);
        }else{
            $trackRecord = $trackRecord->whereNull('cust_id');
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
                $date = TrackRecord::where('salesman_id',Auth::user()->id)->where('session_id',$request->session_id)->where('cust_id',$request->cust_id == 'null' ? NULL : $request->cust_id)->where('product_id', $product->id)->select('date_time')->first();
                $reportingDate = date('d-M-Y H:i:s', strtotime($date->date_time));
                $nestedData['id'] = $product->id;
                $nestedData['srno'] = $sr_no;
                $nestedData['product_barcode'] = $product->product_barcode;
                $nestedData['part_num'] = $product->part_num;
                $nestedData['part_description'] = $product->part_description;
                $nestedData['source_created_date'] = $reportingDate;
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
        return view('clinic_user.reporting.product_details',array('title' => 'Product Details','product'=>$product, 'breadcrumb' => array(
                array('title' => 'Product List', 'link' => route('reporting.product.list',$session_id)))));
    }

}

