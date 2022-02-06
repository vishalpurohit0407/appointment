<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patient;
use App\Models\Common;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\PatientImport;
use Maatwebsite\Excel\Facades\Excel;
use Hash;
use Storage;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.patient.list',array('title' => 'Patient List','breadcrumb' => array()));
    }

    public function listdata(Request $request){

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'status',
            4 => 'created_at'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $patients = User::where('user_type','2');

        $totalData = $patients->count();

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $patients=$patients->where(function ($query) use ($search) {
                $query->where('name','LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%");
            });

            $totalFiltered =  $patients->count();
        }

        $patients =  $patients->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();


        //dd($patients);
        $data = array();
        if(!empty($patients))
        {
            $sr_no = '1';
            foreach ($patients as $patient)
            {
                $nestedData['hashid'] = $patient->hashid;
                $nestedData['srno'] = $sr_no;
                $nestedData['name'] = $patient->name;
                $nestedData['email'] = $patient->email;
                $nestedData['profile_pic'] = $patient->profile_pic && Storage::exists($patient->profile_pic) ? Storage::url($patient->profile_pic) : asset('assets/media/users/default.jpg');
                $nestedData['status'] = $patient->status;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($patient->created_at));
                $nestedData['actions'] = $patient->id;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.patient.create', array('title' => 'Create New Patient', 'breadcrumb' => array(
                array('title' => 'Patient List', 'link' => route('admin.patient.list')))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "yeeee";exit();
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'email' => 'required|email|unique:users',

            'password' => 'required|min:8',
        ]);

        try {
            $input = $request->all();
            $input['password'] = md5($request->password);
            $input['dob']      = date("Y-m-d",strtotime($request->dob));
            $input['status'] = ($request->status && $request->status == '1')? '1' : '0';
            $input['gender'] = ($request->gender && $request->gender == '1')? '1' : '0';
            $input['user_type'] = '2';
            $patient = User::create($input);

            if($patient->save())
            {
                $request->session()->flash('alert-success', 'Patient created successfuly.');
            }
            return redirect(route('admin.patient.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.patient.create'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decode_id = \Hashids::decode($id);
        $patient = User::where('id',$decode_id)->first();
        return view('admin.patient.detail',array('title' => 'Patient Details','patientdata'=>$patient, 'breadcrumb' => array(
                array('title' => 'Patient List', 'link' => route('admin.patient.list')),
        )));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $decode_id = \Hashids::decode($id);
        $patient = User::where('id',$decode_id)->first();
        return view('admin.patient.edit',array('title' => 'Edit Patient','patientdata'=>$patient, 'breadcrumb' => array(
                array('title' => 'Patient List', 'link' => route('admin.patient.list')))));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $decode_id = \Hashids::decode($id);
        $patient = User::where('id',$decode_id)->first();
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            'email' => 'required|email|unique:users,email,'.$patient->id,

        ],[
            'erp_id.required' => 'The patient id field is required.',
            'erp_id.unique' => 'The patient id has already been taken.',
        ]);

        try {
            $patient->name = $request->name;
            $patient->last_name = $request->last_name;
            $patient->email = $request->email;
            $patient->mobile = $request->mobile;
            $patient->dob = date("Y-m-d",strtotime($request->dob));
            $patient->status = ($request->status && $request->status == '1')? '1' : '0';
            $patient->gender = ($request->gender && $request->gender == '1')? '1' : '0';
            $patient->user_type = '2';

            if ($request->password != '') {
                $request->validate([
                    'password' => 'min:8'
                ]);
                $patient->password = md5($request->password);
            }

            if($patient->save())
            {
                $request->session()->flash('alert-success', 'Patient updated successfuly.');
            }
            return redirect(route('admin.patient.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.patient.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $decode_id = \Hashids::decode($id);
        $patient = User::where('id',$decode_id)->first();
        try {
            if ($patient->delete()) {
                $request->session()->flash('alert-success', 'Patient deleted successfuly.');
            }
            return redirect(route('admin.patient.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.patient.list'));
        }
    }

    public function import(Request $request){
        return view('admin.patient.import',array('title' => 'Import Patient','breadcrumb' => array(array('title' => 'Patient List', 'link' => route('admin.patient.list')))));

    }

    public function importdata(Request $request){

        $request->validate([
            'csv_file' => 'required|mimes:csv',
        ]);

        try {

            $data = Excel::import(new PatientImport,request()->file('csv_file'));

            if($data)
            {
                $request->session()->flash('alert-success', 'Patient imported successfully.');
            }
            return redirect(route('admin.patient.list'));

        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.patient.list'));
        }
    }
}
