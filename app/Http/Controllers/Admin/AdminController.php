<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use app\Models\Admin;
use Auth;
use Storage;
use Hash;
use App\Models\SqlHtlCustInfo;
use App\Models\Setting;

class AdminController extends Controller
{
    public function __construct() {
      $this->middleware(['admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

      /*//$users = DB::connection('mysql2')->select(...);
      print_r(SqlHtlCustInfo::count());  
      $userData = SqlHtlCustInfo::where('CustID', '1A001')->get();
      foreach($userData as $row=>$col){
        print_r($col);
      }
      dd($userData);*/
      
      return view('admin.dashboard',array('title' => 'Dashboard')); 
    }


    public function getChangePass() {
        return view('admin.profile.changepass',array('title' => 'Change Password'));
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
        'newpass' => 'required|min:6|confirmed',
        'newpass_confirmation' => 'required|min:6',
      ], $messages);

        $userData = Admin::find(Auth::guard('admin')->user()->id);
        if(!Hash::check($request->get('currentpass'),$userData->password)){
            $request->session()->flash('alert-danger','Please enter valid current password.');
            return redirect(route('admin.changepass'));
        }

        $userData->password = Hash::make($request->get('newpass'));
        if($userData->save()){
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
            $request->session()->flash('alert-success','Password changed successfully.');
        }

        return redirect(route('admin.changepass'));
    }
    
    public function profile() {
        $userData = Admin::find(Auth::guard('admin')->user()->id);
        return view('admin.profile.editprofile',array('title' => 'Edit Profile','userData' => $userData));
    }
    
    public function updateProfile(Request $request) {
        $request->validate([           
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        $userData = Admin::find(Auth::guard('admin')->user()->id);
        $userData->name = $request->name;
        $userData->username = $request->username;
        $userData->email = $request->email;

        $file=$request->file('profile_img');
        if($file){
            // echo "<pre>";print_r($file);exit();
            $request->validate([
                'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if (Storage::exists($userData->profile_img)) { 
                Storage::delete($userData->profile_img);
            }
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $path = 'adminprofile/'.$userData->id.'/'.$imageName.".".$imgext;
            Storage::disk('public')->putFileAs('adminprofile/'.$userData->id,$file,$imageName.".".$imgext);
            
            $userData->profile_img = $path;
        }else{
            if($request->profile_avatar_remove){
                Storage::delete($userData->profile_img);
                $userData->profile_img = NULL;
            }else{
                unset($userData->profile_img);
            }
        }
        
        if($userData->save()){
            $request->session()->flash('alert-success','Profile updated successfully.');
        }
        return redirect(route('admin.editprofile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $tables_sync_val = Setting::where('key','table_script_synchronization_status')->first('value');
        return view('admin.settings.generalSetting',array('title' => 'General Setting','tables_sync_val'=>$tables_sync_val));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveSettings(Request $request)
    {

        // echo "<pre>";print_r($request->all());exit();
        $setting = Setting::where('key','table_script_synchronization_status')->first();
        if ($setting) {
            $setting->key = "table_script_synchronization_status";
            $setting->value = isset($request->tables_sync_val) == '1' || isset($request->tables_sync_val) == '2' ? $request->tables_sync_val : '0';
            $setting->save();
        }else{
            // echo "<pre>";print_r($keyName);exit();
            Setting::create([
                'key'=> 'table_script_synchronization_status',
                'value'=> isset($request->tables_sync_val) == '1' || isset($request->tables_sync_val) == '2' ? $request->tables_sync_val : '0'
            ]);
        }
        
        $request->session()->flash('alert-success','Settings save successfully.');
        return redirect(route('admin.settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
