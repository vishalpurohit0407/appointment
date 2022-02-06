<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // protected function credentials(Request $request)
    // {
    //     //return $request->only($this->username(), 'password');
    //     echo "<pre>";print_r(md5($request->password));exit();
    //     return ['email' => $request->email, 'password' => md5($request->password), 'user_type' => 1];
    // }

    protected function attemptLogin(Request $request)
    {
        // echo "<pre>";print_r(md5($request->password));exit();
        $user = User::where([
            'email' => $request->email,
            'password' => md5($request->password),
            'user_type' => '1'
        ])->first();
        
        if ($user) {
            $this->guard()->login($user, $request->has('remember'));
            return true;
        }

        return false;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

}
