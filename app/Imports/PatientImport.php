<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use Validator;
use Carbon\Carbon;

class PatientImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        Validator::make($row, [
             'patientid' => 'required',
             'fullname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
         ])->validate();

        $patient = User::where('user_type',2)->get();

        $check_patient = User::where('email', $row['email'])->first();

        if(!$check_patient)
        {
            $userData = new User([
                'erp_id'    => $row['patientid'],
                'name'      => $row['fullname'],
                'email'     => $row['email'],
                'password'  => md5($row['password']),
                'user_type' => 2,
                'status'    => 1,
                'email_verified_at' => date("Y-m-d h:i:s" )

            ]);
           $userData->save();
        }else
        {
            $check_patient->erp_id = $row['patientid'];
            $check_patient->name = $row['fullname'];
            $check_patient->email = $row['email'];
            $check_patient->password = md5($row['password']);
            $check_patient->user_type = 2;
            $check_patient->status = 1;
            $check_patient->email_verified_at= date("Y-m-d h:i:s");
            $check_patient->save();
        }
    }
}
