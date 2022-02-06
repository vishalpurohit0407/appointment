<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use Validator;
use Carbon\Carbon;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Validator::make($row, [
             'clinicuserid' => 'required',
             'fullname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
         ])->validate();

        $users = User::where('user_type',1)->get();

        $check_users = User::where('erp_id', $row['clinicuserid'])->first();

        if(!$check_users)
        {
            $userData = new User([
                'erp_id'    => $row['clinicuserid'],
                'name'      => $row['fullname'],
                'email'     => $row['email'],
                'password'  => md5($row['password']),
                'user_type' => 1,
                'status'    => 1,
                'email_verified_at' => date("Y-m-d h:i:s" )

            ]);
           $userData->save();
        }else
        {
            $check_users->erp_id = $row['clinicuserid'];
            $check_users->name = $row['fullname'];
            $check_users->email = $row['email'];
            $check_users->password = md5($row['password']);
            $check_users->user_type = 1;
            $check_users->status = 1;
            $check_users->email_verified_at= date("Y-m-d h:i:s");
            $check_users->save();
        }
    }
}
