<?php

namespace App\Http\Traits;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Notification;
use Storage;

trait APITrait {
    public function response($message,$data,$responseCode) {
        $responseCodeArray = ["succ"=>200,"unauth"=>401,"val"=>200,"err"=>500];
        $respCode = (isset($responseCodeArray[$responseCode]))?$responseCodeArray[$responseCode]:403;
        $apiStatus = ($responseCode=="succ")?1:0;
        $data = ($data)?$data:(object)[];
        return response()->json(['message'=>$message, 'data'=>$data, 'status'=>$apiStatus], $respCode);
    }

    public function userProfile($userId) {
        return User::where("id",$userId)->select("person_id","name","email","mobile","profile_pic","address","status","email_verified_at","created_at")->first();
    }

    public function uploadFile($file,$type,$id) {
        $typeArray = array("profile"=>"users/$id/profile",
                           "vehicle"=>"users/$id/vehicle",
                           "admin"=>"admin/$id",
                           "admin_profile"=>"admin/$id/profile");
        // $name=time().$file->getClientOriginalName();
        $filePath = $typeArray[$type];
        return Storage::disk(env('FILESYSTEM_DRIVER'))->putFile($filePath, $file, 'public');
        return $filePath;
    }

    public function fileDelete($path){
        Storage::disk(env('FILESYSTEM_DRIVER'))->delete($path);
    }

    public function createNotification($title,$body,$data,$type,$from,$to) {
        return Notification::create(["user_id"=>$from,
                                  "receiver_id"=>$to,
                                  "type"=>$type,
                                  "title"=>$title,
                                  "body"=>$body,
                                  "data"=>$data]);
    }
}