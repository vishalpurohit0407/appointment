<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ForgotPassword;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\HtlCustInfo;
use App\Models\HtlCustLatestTrans;
use App\Models\HtlCustPartSummary;
use App\Models\HtlCustPriceBook;
use App\Models\HtlCustSummary;
use App\Models\HtlExRate;
use App\Models\HtlPartCost;
use App\Models\HtlPartImage;
use App\Models\HtlPartInfo;
use App\Models\HtlCustPartInfo;

use App\Http\Traits\APITrait;
use App\Models\Patient;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\HtlTableJsonData;
use App\Models\Setting;
use App\Models\TrackRecord;
use App\Mail\ForgotPassword as mailForgotPassword;
use Validator;
use DB, Mail, Hash,Storage;


class UserController extends Controller
{
    use APITrait;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->response($validator->errors()->first(),null,"val");
        }

        $user = User::where('email', request('email'))->where('password', md5(request('password')))->first();

        if($user){
            Auth::login($user);

            $user['token'] = Auth::user()->createToken('htl_portal')->accessToken;

            return $this->response("You have successfully logged-in.",$user,"succ");
        } else{
            return $this->response("Please check your email and password.",null,"val");
        }
    }

    /**
     * Mobile Verification api
     *
     * @return \Illuminate\Http\Response
     */
    public function check_mobile_verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_otp' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->response($validator->errors()->first(),null,"val");
        }

        $user = Auth::user();
        if((int)$user['phone_otp'] == (int)$request['phone_otp']) {
            $userData = User::find($user->id);
            $userData->phone_verified_at = date('Y-m-d H:i:s', time());
            $userData->update();

            return $this->response("Your Mobile has been verified successfully.",null,"succ");
        } else {
            return $this->response("You have entered wrong OTP. Please check again.",null,"val");
        }
    }

    /**
     * Resend Mobile Verification api
     *
     * @return \Illuminate\Http\Response
     */
    public function resend_mobile_verification(Request $request)
    {
        $user = Auth::user();

        if(!$user->mobile_verified_at){
          $userOTP = rand(111111,999999);

          $userData = User::find($user->id);
          $userData->phone_otp = "123456";
          $userData->update();

          return $this->response("OTP has been sent successfully on your registered mobile.",null,"succ");
        } else {
            return $this->response("Your mobile number is already verified.",null,"val");
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'person_id' => 'required|max:100',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users|max:255',
            'mobile' => 'required|unique:users|max:15',
            'profile_pic' => 'nullable|mimes:jpg,bmp,png|max:2048',
            'password' => 'required|min:6|max:30',
            'address' => 'required|max:255'
        ]);

        if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

        try { DB::beginTransaction();
            $userOTP = rand(111111,999999);
            $request['password'] = md5($request->password);
            $request['user_otp'] = $userOTP;

            if($userData = User::create($request->except(['person_id','name','email','mobile','password','address']))) {
                $request['user_id'] = $userData->id;
                if($request->hasfile('profile_pic')) {
                    $uploadedFile = $this->uploadFile($request->file('profile_pic'),"profile",$userData->id);
                    $userData->profile_pic = $uploadedFile;
                    $userData->update();
                }
            }

            $success =  $this->userProfile($userData->id);
            $success['token'] =  $userData->createToken('HTL')->accessToken;

            DB::commit();
            return $this->response("You have successfully register. Your account is send for admin approval.",$success,"succ");
        } catch (\Exception $e) {
            DB::rollback();
            return $this->response($e->getMessage(),null,"err");
        }
    }

    /**
     * profile api
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request) {
        $userData = User::find($request->user_id);
        if (!$userData) {
          return $this->response("User Not Found!",null,"val");
        }
        return $this->response("Your profile detail get successfully.",$userData,"succ");
    }

    /**
     * Update User Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUserDetails(Request $request)
    {

        $userData = User::find($request->id);
        if (!$userData) {
          return $this->response("User Not Found!",null,"val");
        }
        $validator = Validator::make($request->all(), [
            'erp_id' => 'required|max:100',
            'name' => 'required|max:100',
            'email' => 'required|max:255|email|unique:users,email,'.$userData->id,
            'mobile' => 'required|max:15|unique:users,mobile,'.$userData->id,
            'address' => 'required|max:255'
        ]);

        if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

        // echo "<pre>";print_r($userData);exit();
        try { DB::beginTransaction();
            $userData->erp_id = $request['erp_id'];
            $userData->name = $request['name'];
            $userData->email = $request['email'];
            $userData->mobile = $request['mobile'];
            $userData->address = $request['address'];

            if($request->profile_pic) {
                $this->fileDelete($userData->profile_pic);
                $uploadedFile = $this->uploadFile($request->file('profile_pic'),"profile",$userData->id);
                $userData->profile_pic = $uploadedFile;
            }
            if ($userData->update()) {
              DB::commit();
              return $this->response("Your profile has been updated successfully.",$userData,"succ");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->response($e->getMessage(),null,"err");
        }
    }

    /**
     * Change Password API
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'new_confirm_password' => 'required|same:new_password',
            'old_password' => 'required'
        ]);

        if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

        try { DB::beginTransaction();
            $userData = User::find($request->id);
            if (!$userData) {
              return $this->response("User Not Found!",null,"val");
            }
            $input = $request->all();
            if(md5($input['old_password']) != $userData->password){
                return $this->response("You have entered invalid password.",null,"val");
            } else {
                $userData->password = md5($input['new_password']);
                $userData->update();
                DB::commit();
                return $this->response("Password has been changed Successfully.",null,"succ");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->response($e->getMessage(),null,"err");
        }
    }

    /**
     * forgot password api
     *
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

        $userData = User::where('email',$request->email)->first();

        if($userData) {
            try { DB::beginTransaction();
                $newPassword = Str::random(8);
                $mailData = array("password"=>$newPassword,"name"=>$userData->first_name." ".$userData->last_name);
                Mail::to($userData->email)->send(new mailForgotPassword($mailData));
                // check for failed ones
                if (!Mail::failures()) {
                  $userData->password = md5($newPassword);
                  $userData->update();
                }
                DB::commit();
                return $this->response("Your password has been sent on registered email address.",null,"succ");
            } catch (\Exception $e) {
                DB::rollback();
                return $this->response($e->getMessage(),null,"err");
            }
        } else {
            return $this->response("Your account is not found with this email address.",null,"val");
        }
    }

    /**
     * User Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
      $userToLogout = User::find($request->id);
      Auth::setUser($userToLogout);
        if (Auth::check()) {
          Auth::logout();
          return $this->response("Your account has been logged out.",null,"succ");
        } else{
            return $this->response("Something Wrong.",null,"err");
        }
    }

    /**
     * Device Token For Push Notification
     *
     * @return \Illuminate\Http\Response
     */
    public function device_token(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:255',
            'type' => 'required|in:ios,web,android'
        ]);

        if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

        DeviceToken::create(['user_id'=>Auth::user()->id,'type'=>strtolower($request->type),'token'=>$request->token]);

        return $this->response("Your device token has been saved.",null,"succ");
    }

    /**
     * Send Notification Usin Cron
     *
     * @return \Illuminate\Http\Response
     */
    public function cron_send_push_notification(){
      $notificationData = Notification::where('status','pending')->get();
      if($notificationData){
          $apiKey = env('FIREBASE_SERVER_KEY');
          $apiKey = "AAAAmjyExOk:APA91bGDT0SVmbjByGNhcKeZILf-kC_pAl6djDSysJOZ544AMeddHzLRzBOrqd0bXOl2_1SkP0M6exdMOH9842b1kpU-ahv2otIyTg79S-Hl4SPp4PS3sFHU6PABtCq_DD54G7bLoFQN";

          $GOOGLE_FCM_URL = 'https://fcm.googleapis.com/fcm/send';

          foreach($notificationData as $notification) {
                $receiverData  = User::where('id',$notification->receiver_id)->where('notification_status',"1")->first();
                if(!$receiverData) {  continue; }
                $notification->status = 'sent';
                $notification->update();

                $deviceToken = DeviceToken::where('user_id',$notification->receiver_id)->pluck('token')->toArray();
                if(!count($deviceToken)) {  continue; }

                $deviceToken = ["cLZCr42jQseipFAIkBw54H:APA91bHs-yiMKM4hIoHo198HEpcMuOdJwDUNzXJVUziGQMizDIJcACMnOVuD35Zj5yNeIATqXjAgikWXbEn_ZMGhJo4QVeQYyTFnipmK-lx14HjlRMPCqnVNZdBUYHPgMQdwMJG2x6UV"];

                $payload = array([
                                    "to"=>"Notification Token",
                                    "notification"=> [
                                        "body"=>$notification->body,
                                        "title"=>$notification->title,
                                        "icon"=>"",
                                    ],
                                    "android" => [
                                        "notification"=> [
                                          "imageUrl"=>"https://www.1stcontactconnection.com/assets/images/logo/logo-icon.png"
                                        ]
                                    ]
                                ]);

                $aps = array("title"=>"This is title", "is_background"=>false, "body"=>"This is body","badge"=>"1");
                $payload = array(
                   'registration_ids'          => $deviceToken,
                   'priority'                  => "high",
                   'notification'              => $aps,
                   'data'                      => ["key1"=>"value1"]
                );

                $headers = array(
                   $GOOGLE_FCM_URL,
                   'Content-Type: application/json',
                   'Authorization: key=' . $apiKey
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $GOOGLE_FCM_URL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

                $result = curl_exec($ch);
                curl_close($ch);
            }
        }
    }


    /**
     * Support API
     *
     * @return \Illuminate\Http\Response
     */
    public function fullSyncJsonFile(Request $request) {

        try {
          $jsonDataFiles = HtlTableJsonData::orderBy('id','desc')->take(11)->get();
          $last_sync_datetime = date('Y-m-d H:m:s');
          $product_all_images_size = Setting::where('key','product_all_images_size')->first('value');
          $files = Storage::disk('public')->allFiles('part_images_demo');
          //$url=storage::url('part_images_demo');
          // echo "<pre>";print_r($url);exit();
          // return $this->response("Full sync data fetch successfully.",$jsonDataFiles,$last_sync_datetime,"succ");
          return response()->json([
              'message' => 'Full sync data fetch successfully.',
              'data' => $jsonDataFiles,
              'last_sync_datetime' => $last_sync_datetime,
              'product_all_images_size' => (int)$product_all_images_size->value,
              'status' => 1
          ]);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(),null,"err");
        }
    }

    public function smartSync(Request $request) {

        try {

            /*$validator = Validator::make($request->all(), [
                'last_sync_datetime' => 'required',
            ]);*/

            if(!isset($request->last_sync_datetime) && !isset($request->erp_id)){
                return $this->response('The last_sync_datetime or erp_id field is required.',null,"val");
            }
            //if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

            $tableJsonArr = array();

            $lastSyncDate = date('Y-m-d H:i:s', strtotime($request->last_sync_datetime));

            $usersTable = new User;
            $custInfoTable = new HtlCustInfo;
            $custLatestTransTable = new HtlCustLatestTrans;
            $custPartSummariesTable = new HtlCustPartSummary;
            $custPriceBooksTable = new HtlCustPriceBook;
            $custSummariesTable = new HtlCustSummary;
            $exRatesTable = new HtlExRate;
            $partCostsTable = new HtlPartCost;
            $partImageTable = new HtlPartImage;
            $partInfos = new HtlPartInfo;

            $custPartInfoTable = new HtlCustPartInfo;

            if(isset($request->last_sync_datetime) && $request->last_sync_datetime != ''){
              $usersTable=$usersTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custInfoTable=$custInfoTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custLatestTransTable=$custLatestTransTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custPartSummariesTable=$custPartSummariesTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custPriceBooksTable=$custPriceBooksTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custSummariesTable=$custSummariesTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $exRatesTable=$exRatesTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $partCostsTable=$partCostsTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('htl_part_costs.created_at', '>',$lastSyncDate)
                          ->orWhereDate('htl_part_costs.updated_at', '>',$lastSyncDate);
              });
              $partImageTable=$partImageTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('htl_part_images.created_at', '>',$lastSyncDate)
                          ->orWhereDate('htl_part_images.updated_at', '>',$lastSyncDate);
              });
              $partInfos=$partInfos->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
              $custPartInfoTable=$custPartInfoTable->where(function ($query) use ($lastSyncDate) {
                  $query->whereDate('created_at', '>',$lastSyncDate)
                          ->orWhereDate('updated_at', '>',$lastSyncDate);
              });
            }

            if($request->erp_id){

                $usersTable = $usersTable->where('erp_id',$request->erp_id);
                $custInfoTable = $custInfoTable->where('cust_id',$request->erp_id);
                $custLatestTransTable = $custLatestTransTable->where('cust_id',$request->erp_id);
                $custPartSummariesTable = $custPartSummariesTable->where('cust_id',$request->erp_id);
                $custPriceBooksTable = $custPriceBooksTable->where('cust_id',$request->erp_id);
                $custSummariesTable = $custSummariesTable->where('cust_id',$request->erp_id);
                $partInfos = $partInfos->where('sold_to_cust_id',$request->erp_id);
                $custPartInfoTable = $custPartInfoTable->where('cust_id',$request->erp_id);

                $partCostsTable = $partCostsTable->select('htl_part_costs.*')->leftJoin('htl_part_infos', function($join) {
                  $join->on('htl_part_infos.part_num', '=', 'htl_part_costs.part_num');
                })->where('htl_part_infos.sold_to_cust_id',$request->erp_id);

                $partImageTable = $partImageTable->select('htl_part_images.*')->leftJoin('htl_part_infos', function($join) {
                  $join->on('htl_part_infos.part_num', '=', 'htl_part_images.part_num');
                })->where('htl_part_infos.sold_to_cust_id',$request->erp_id);
            }

            $tableJsonArr['users'] = $usersTable->count();
            $tableJsonArr['htl_cust_info'] = $custInfoTable->count();
            $tableJsonArr['htl_cust_latest_trans'] = $custLatestTransTable->count();
            $tableJsonArr['htl_cust_part_summaries'] = $custPartSummariesTable->count();
            $tableJsonArr['htl_cust_price_books'] = $custPriceBooksTable->count();
            $tableJsonArr['htl_cust_summaries'] = $custSummariesTable->count();
            $tableJsonArr['htl_ex_rates'] = $exRatesTable->count();
            $tableJsonArr['htl_part_costs'] = $partCostsTable->count();
            $tableJsonArr['htl_part_infos'] = $partInfos->count();
            $tableJsonArr['htl_part_images'] = $partImageTable->count();
            $tableJsonArr['htl_cust_part_info'] = $custPartInfoTable->count();

            $htlPartImageData = $partImageTable->where('content', '!=', NULL)->get()->pluck('content')->toArray();

            $data = [];
            foreach ($tableJsonArr as $key => $value) {
              if ($value > 0) {
                $data[] = array(
                            'table_name' => $key
                          );
              }
            }

          $last_sync_datetime = date('Y-m-d H:m:s');

          $allFilesSize = -1;
          if($htlPartImageData){
            $filesSize= 0;
            foreach ($htlPartImageData as $key => $value) {
                $filesSize += Storage::disk('public')->size($value);
            }
            $allFilesSize = $filesSize;
          }
          return response()->json([
              'status' => 1,
              'message' => 'Smart sync data fetch successfully.',
              'last_sync_datetime' => $last_sync_datetime,
              'product_all_images_size' => (int)$allFilesSize,
              'data' => $data
          ]);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(),null,"err");
        }
    }

    public function smartSyncTable(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                //'last_sync_datetime' => 'required',
                'table_name' => 'required',
            ]);
            if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }

            if(!isset($request->last_sync_datetime) && !isset($request->erp_id)){
                return $this->response('The last_sync_datetime or erp_id field is required.',null,"val");
            }

            $tableName = array(
                "users","htl_cust_info","htl_cust_latest_trans","htl_cust_part_summaries","htl_cust_price_books","htl_cust_summaries","htl_ex_rates","htl_part_costs","htl_part_images","htl_part_infos", "htl_cust_part_infos"
            );

            $tableJsonArr = array();
            $lastSyncDate = date('Y-m-d H:i:s', strtotime($request->last_sync_datetime));
            $requestTableName = ($request->table_name == 'htl_cust_part_info')? 'htl_cust_part_infos' : $request->table_name;

            if (in_array($requestTableName,$tableName)) {

              $tableJsonArr = DB::table($requestTableName);
              if(isset($request->last_sync_datetime) && $request->last_sync_datetime != ''){
                $tableJsonArr=$tableJsonArr->where(function ($query) use ($lastSyncDate,$requestTableName) {
                    $query->whereDate($requestTableName.'.created_at', '>',$lastSyncDate)
                            ->orWhereDate($requestTableName.'.updated_at', '>',$lastSyncDate);
                });
              }

              if($request->erp_id){

                if($request->table_name == 'users'){

                  //$tableJsonArr = $tableJsonArr->where('erp_id',$request->erp_id);

                }else if($request->table_name == 'htl_part_infos'){

                  $tableJsonArr = $tableJsonArr->where('sold_to_cust_id',$request->erp_id);
                }
                else if($request->table_name == 'htl_ex_rates'){


                }
                else if($request->table_name == 'htl_part_images'){

                  $tableJsonArr = $tableJsonArr->select('htl_part_images.*')->leftJoin('htl_part_infos', function($join) {
                    $join->on('htl_part_infos.part_num', '=', 'htl_part_images.part_num');
                  })->where('htl_part_infos.sold_to_cust_id',$request->erp_id);
                }elseif($request->table_name =='htl_part_costs'){

                  $tableJsonArr = $tableJsonArr->select('htl_part_costs.*')->leftJoin('htl_part_infos', function($join) {
                    $join->on('htl_part_infos.part_num', '=', 'htl_part_costs.part_num');
                  })->where('htl_part_infos.sold_to_cust_id',$request->erp_id);

                }else{
                  $tableJsonArr = $tableJsonArr->where('cust_id',$request->erp_id);
                }
              }

              $tableJsonArr = $tableJsonArr->get()->toArray();
            }else{
              return response()->json([
                  'status' => 0,
                  'message' => 'Table not found.',
                  'data' => array()
              ]);
              return $this->response('Table not found.',null,"val");
            }

            $product_all_images_size =-1;
            if ($request->table_name == 'htl_part_images') {
              $allFilesSize = 0;
              if($tableJsonArr){
                foreach ($tableJsonArr as $key => $value) {
                  $allFilesSize += Storage::disk('public')->size($value->content);
                }
              }
              $product_all_images_size=$allFilesSize;
            }

          $last_sync_datetime = date('Y-m-d H:i:s');

          return response()->json([
              'status' => 1,
              'message' => 'Smart sync table data fetch successfully.',
              "table_name" => $request->table_name,
              'last_sync_datetime' => $last_sync_datetime,
              'product_all_images_size' => (int)$product_all_images_size,
              'data' => $tableJsonArr
          ]);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(),null,"err");
        }
    }

    public function trackRecord(Request $request) {

        try {
            // echo "<pre>";print_r($request->all());exit();
            $validator = Validator::make($request->all(), [
                'salesman_id' => 'required',
                'salesman_erp_id' => 'required',
                'session_id' => 'required',
                'date_time' => 'required',
                'product_id' => 'required',
                'part_number' => 'required',
            ]);
            $allData = $request->all();
            if ($allData) {
                foreach ($allData as $key => $value) {
                    TrackRecord::create([
                       'salesman_id' => $value['salesman_id'],
                       'salesman_erp_id' => $value['salesman_erp_id'],
                       'session_id' => $value['session_id'],
                       'cust_id' => $value['cust_id'],
                       'cust_erp_id' => $value['cust_erp_id'],
                       'date_time' => date('Y-m-d',strtotime($value['date_time'])),
                       'product_id' => $value['product_id'],
                       'part_number' => $value['part_number'],
                       'updated_at' => NULL,
                    ]);
                }
            }
            return response()->json([
                'status' => 1,
                'message' => 'Success',
            ]);

        } catch (\Exception $e) {
            return $this->response($e->getMessage(),null,"err");
        }
    }

    public function smartSyncTableDemo(Request $request) {

        try {

            $validator = Validator::make($request->all(), [
                'last_sync_datetime' => 'required',
                'table_name' => 'required',
            ]);
            if ($validator->fails()) { return $this->response($validator->errors()->first(),null,"val"); }
            $tableName = array(
                "users","htl_cust_info","htl_cust_latest_trans","htl_cust_part_summaries","htl_cust_price_books","htl_cust_summaries","htl_ex_rates","htl_part_costs","htl_part_images","htl_part_infos"
            );

            $tableJsonArr = array();
            $lastSyncDate = date('Y-m-d H:i:s', strtotime($request->last_sync_datetime));
            if (in_array($request->table_name,$tableName)) {
              $tableJsonArr = DB::table($request->table_name)->whereDate('created_at', '>',$lastSyncDate)->orWhereDate('updated_at', '>',$lastSyncDate)->get()->toArray();
            }else{
              return $this->response('Table not found.',null,"val");
            }
            if($tableJsonArr){
              $allFilesSize = 0;
              foreach ($tableJsonArr as $key => $value) {
                $allFilesSize += Storage::disk('public')->size($value->content);
              }
              // echo "<pre>";print_r($allFilesSize);exit();
            }
          $last_sync_datetime = date('Y-m-d H:i:s');

          return response()->json([
              'status' => 1,
              'message' => 'Smart sync table data fetch successfully.',
              "table_name" => $request->table_name,
              'last-sync-datetime' => $last_sync_datetime,
              'all-images-size' => $allFilesSize,
              'data' => $tableJsonArr
          ]);
        } catch (\Exception $e) {
            return $this->response($e->getMessage(),null,"err");
        }
    }

    /**
     * Check user status
     *
     * @return \Illuminate\Http\Response
     */
    public function checkStatus(Request $request){


        $validator = Validator::make($request->all(), [
            'erp_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors()->first(),null,"val");
        }

        $user = User::where('id', request('user_id'))->where('erp_id',request('erp_id'))->first();

        if($user){

            return $this->response("User Fetch successfully.",$user,"succ");
        } else{
            return $this->response("Please check your ERP ID and User ID.",null,"val");
        }
    }
}