<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Response;
use Hash;
use Storage;
use Redirect;
use App\Models\Common;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        return view('admin.user.list',array('title' => 'Clinic User List','breadcrumb' => array()));
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

        $users = User::where('user_type', '1');

        $totalData = $users->count();

        $totalFiltered = $totalData;

        if(!empty($request->input('search.value')))
        {
            $search = $request->input('search.value');
            $users=$users->where(function ($query) use ($search) {
                $query->where('name','LIKE',"%{$search}%")
                        ->orWhere('email', 'LIKE',"%{$search}%");
            });

            $totalFiltered =  $users->count();
        }

        $users =  $users->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();


        //dd($users);
        $data = array();
        if(!empty($users))
        {
            $sr_no = '1';
            foreach ($users as $user)
            {
                $nestedData['hashid'] = $user->hashid;
                $nestedData['srno'] = $sr_no;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['profile_pic'] = $user->profile_pic && Storage::exists($user->profile_pic) ? Storage::url($user->profile_pic) : asset('assets/media/users/default.jpg');
                $nestedData['status'] = $user->status;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($user->created_at));
                $nestedData['actions'] = $user->id;
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
    public function create(){
        return view('admin.user.create', array('title' => 'Create New Clinic User', 'breadcrumb' => array(
                array('title' => 'Clinic User List', 'link' => route('admin.user.list')))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // echo "<pre>"; print_r($request->all()); exit();
        $request->validate([
            'provider_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',

            'password' => 'required|min:8',
        ]);

        try {
            $input = $request->all();
            $input['password'] = md5($request->password);
            $input['status'] = ($request->status && $request->status == '1')? '1' : '0';
            $input['user_type'] = '1';
            $user = User::create($input);

            if($user->save())
            {
                $request->session()->flash('alert-success', 'Clinic User created successfuly.');
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.user.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){
        return view('admin.user.detail',array('title' => 'Clinic User Details','userdata'=>$user, 'breadcrumb' => array(
                array('title' => 'Clinic User List', 'link' => route('admin.user.list')),
        )));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user){
        return view('admin.user.edit',array('title' => 'Edit Clinic User','userdata'=>$user, 'breadcrumb' => array(
                array('title' => 'Clinic User List', 'link' => route('admin.user.list')))));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request){
        // echo "<pre>";print_r($request->all());exit();
        $request->validate([
            'provider_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,

        ]);

        try {
            $user->provider_name = $request->provider_name;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->status = ($request->status && $request->status == '1')? '1' : '0';
            $user->user_type = '1';

            if ($request->password != '') {
                $request->validate([
                    'password' => 'min:8'
                ]);
                $user->password = md5($request->password);
            }

            if($request->file('profile_img')){
                $request->validate([
                    'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
                if ($user->profile_pic) {
                    Common::deleteImage($user->profile_pic);
                }
                $path = Common::uploadFile($request->file('profile_img'),'profile',$user->id);

                $user->profile_pic = $path;
            }

            if($user->save())
            {
                $request->session()->flash('alert-success', 'Clinic User updated successfuly.');
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.user.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request){
        try {
            if ($user->delete()) {
                $request->session()->flash('alert-success', 'Clinic User deleted successfuly.');
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.user.list'));
        }
    }


    public function import(Request $request){
        return view('admin.user.import',array('title' => 'Import Clinic User','breadcrumb' => array(array('title' => 'Clinic User List', 'link' => route('admin.user.list')))));

    }

    public function importdata(Request $request){

        $request->validate([
            'csv_file' => 'required|mimes:csv',
        ]);

        try {

            $data = Excel::import(new UsersImport,request()->file('csv_file'));

            if($data)
            {
                $request->session()->flash('alert-success', 'Clinic User imported successfully.');
            }
            return redirect(route('admin.user.list'));

        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage());
            return redirect(route('admin.user.list'));
        }
    }

}
