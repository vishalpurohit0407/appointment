<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
use App\User;
use Storage;
use Config;

class Common extends Model {


	public static function randomCode() {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
    }

    public static function randomNumber() {
        $chars = "0123456789";
        $res = "";
        for ($i = 0; $i < 4; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
    }

    public static function uploadFile($file,$type,$user_id) {
      //dd($file);
	    if($file) {
	    	$file_name =$file->getClientOriginalName();
	        $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
	        $imageName = md5($fileslug.time());
	        $imgext =$file->getClientOriginalExtension();
        if($type == 'profile'){
            $path = Storage::disk(env('FILESYSTEM_DRIVER'))->putFileAs('users/'.$user_id,$file,$imageName.".".$imgext);
        }elseif($type == 'patient_profile'){
            $path = Storage::disk(env('FILESYSTEM_DRIVER'))->putFileAs('patient/'.$user_id,$file,$imageName.".".$imgext);
        }
        	return $path;
    	}else {
    		return false;
    	}
    }



    public static function deleteImage($fileurl) {
 		  Storage::disk(env('FILESYSTEM_DRIVER'))->delete($fileurl);
      return back();
    }

}