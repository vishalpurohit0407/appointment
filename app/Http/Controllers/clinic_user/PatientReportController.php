<?php

namespace App\Http\Controllers\clinic_user;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Common;
use App\Models\User;
use App\Models\PatientAppointment;
use App\Models\PatientReport;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Storage;
use Mail;
use App\Mail\PatientReport as mailPatientReport;

class PatientReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clinic_user.patient-report.list',array('title' => 'Patient Reports / Appointments','breadcrumb' => array()));
    }

    public function listdata(Request $request){

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'received_date'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $patients = PatientReport::select('users.name', 'users.email',  'patient_reports.patient_id', \DB::raw('patient_reports.id as id'), 'patient_reports.received_date')
        ->leftJoin('users', function($join) {
            $join->on('users.id', '=', 'patient_reports.patient_id');
        });
        $patients->where('users.user_type','2');
        $totalData = $patients->count();

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $patients=$patients->where(function ($query) use ($search) {
                $query->where('users.name','LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%");
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
                $nestedData['received_date'] = date('j M Y h:i a',strtotime($patient->received_date));
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
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decode_id          = \Hashids::decode($id);
        $patientReport      = PatientReport::where('id', $decode_id)->first();
        $patientAppointment = PatientAppointment::where('patient_id', $patientReport->patient_id)->where('id', $patientReport->appointment_id)->first();
        $patient            = User::where('id', $patientReport->patient_id)->first();
        //dd($patientReport);
        return view('clinic_user.patient-report.detail', array(
            'title'             => 'Patient Report Details',
            'patientdata'       => $patient,
            'patientAppointment'=> $patientAppointment,
            'patientReport'     => $patientReport,
            'breadcrumb'        => array(
                array(
                    'title' => 'Patient List',
                    'link'  => route('patient-report.list')
                ),
            )
        ));
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
        $report = PatientReport::where('id',$decode_id)->first();
        try {
            if ($report->delete()) {
                $request->session()->flash('alert-success', 'Patient Report deleted successfuly.');
            }
            return redirect(route('patient-report.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('patient-report.list'));
        }
    }

    public function appointmentlistdata(Request $request){

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'appointment_time'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $patients = PatientAppointment::select('users.name', 'users.email',  'patient_appointments.patient_id', \DB::raw('patient_appointments.id as id'), 'patient_appointments.appointment_time')
        ->leftJoin('users', function($join) {
            $join->on('users.id', '=', 'patient_appointments.patient_id');
        })
        ->whereNotExists(function($query)
        {
            $query->select(\DB::raw(1))
                    ->from('patient_reports')
                    ->whereRaw('patient_reports.rt_pcr = "1"')
                    ->whereRaw('patient_reports.antigens = "1"')
                    ->whereRaw('patient_reports.patient_id = patient_appointments.patient_id')
                    ->whereRaw('patient_reports.appointment_id = patient_appointments.id');
        });
        $patients->where('users.user_type','2');
        $totalData = $patients->count();

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $patients=$patients->where(function ($query) use ($search) {
                $query->where('users.name','LIKE',"%{$search}%")
                        ->orWhere('users.email', 'LIKE',"%{$search}%");
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
                $nestedData['appointment_time'] = date('j M Y h:i a',strtotime($patient->appointment_time));
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
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function appointmentshow($id)
    {
        $decode_id          = \Hashids::decode($id);
        $patientAppointment = PatientAppointment::where('id', $decode_id)->first();
        $patient            = User::where('id', $patientAppointment->patient_id)->first();
        $patientReport      = PatientReport::where('patient_id', $patientAppointment->patient_id)->where('appointment_id', $patientAppointment->id)->first();
        //dd($patientReport);
        return view('clinic_user.patient-appointment.detail', array(
            'title'             => 'Patient Details',
            'patientdata'       => $patient,
            'patientAppointment'=> $patientAppointment,
            'patientReport'     => $patientReport,
            'breadcrumb'        => array(
                array(
                    'title' => 'Patient List',
                    'link'  => route('patient-report.list')
                ),
            )
        ));
    }

    public function saveReport(Request $request){

        $patient_id       = $request->get('patient_id');
        $appointment_id  = $request->get('appointment_id');
        $type            = $request->get('type');
        $rt_pcr_status   = $request->get('rt_pcr_status');
        $antigens_status = $request->get('antigens_status');
        $antigens_count   = $request->get('antigens_count');

        try {

        $model = PatientReport::where('patient_id', $patient_id)->where('appointment_id', $appointment_id)->first();

        if(!isset($model->id))
        {
            $model = new PatientReport();
        }

        $model->patient_id      = $patient_id;
        $model->appointment_id  = $appointment_id;
        $model->received_date   = date("Y-m-d H:i:s");

        if($type == 'antigens')
        {
            $model->antigens        = '1';
            $model->antigens_status = $antigens_status;
            $model->antigens_count  = $antigens_count;
        }
        else
        {
            $model->rt_pcr          = '1';
            $model->rt_pcr_status   = $rt_pcr_status;
        }

        $model->save();

        $patientReport = PatientReport::where('id', $model->id)->first();
        $patient       = User::where('id', $patient_id)->first();

        $mailData = array("patient"=>$patient,"patientReport"=>$patientReport, 'provider_name' => Auth::user()->provider_name, 'type' => $type);

        Mail::to($patient->email)->send(new mailPatientReport($mailData));

        return response()->json([
            'success' => true,
            'message' => 'Patient Report saved successfully'
        ]);

         }catch (ModelNotFoundException $exception) {

             return response()->json([
                 'success' => false,
                 'message' => $exception->getMessage()
             ]);
         }
     }

    public function sendReport(Request $request){

        $report_id = $request->get('report_id');
        $type      = $request->get('type');

        try {
            $patientReport = PatientReport::where('id', $report_id)->first();
            $patient       = User::where('id', $patientReport->patient_id)->first();

            $mailData = array("patient"=>$patient,"patientReport"=>$patientReport, 'provider_name' => Auth::user()->provider_name, 'type' => $type);

            Mail::to($patient->email)->send(new mailPatientReport($mailData));

            return response()->json([
                'success' => true,
                'message' => 'Patient Report send successfully'
            ]);

        }catch (ModelNotFoundException $exception) {

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function appointmentdestroy(Request $request,$id)
    {
        $decode_id = \Hashids::decode($id);
        $patient = PatientAppointment::where('id',$decode_id)->first();
        try {
            if ($patient->delete()) {
                $request->session()->flash('alert-success', 'Patient deleted successfuly.');
            }
            return redirect(route('patient-report.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('patient-report.list'));
        }
    }

    public function saveAppointment(Request $request){

       $patientId = $request->get('patient_id');
       $time =  date("Y-m-d H:i:s", strtotime($request->get('time')));

        try {

            $model = new PatientAppointment();
            $model->patient_id = $patientId;
            $model->appointment_time = $time;
            $model->save();

            return response()->json([
                'success' => true,
                'message' => 'Patient Appointment saved successfully'
            ]);

        }catch (ModelNotFoundException $exception) {

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
