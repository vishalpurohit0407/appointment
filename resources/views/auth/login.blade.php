@extends('auth.master')
@section('title')
Clinic User Login
@endsection
@section('content')
<!--begin::Login Sign in form-->
<div class="login-signin">
    <div class="mb-20">
        <h3>Sign In To Clinic User</h3>
        <div class="text-muted font-weight-bold">Enter your details to login to your account:</div>
    </div>
    @if(Session::has('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form role="form" method="POST" action="{{ route('login') }}">
    @csrf
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8 @error('email') is-invalid @enderror" type="text" placeholder="Email *" name="email" autocomplete="off"  value="{{ old('email') }}"/>
            @error('email')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8 @error('password') is-invalid @enderror" type="password" placeholder="Password *" name="password" />
            @error('password')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
            <div class="checkbox-inline">
                <label class="checkbox m-0 text-muted">
                <input type="checkbox" name="remember" value="1" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                <span></span>Remember me</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button>
    </form>
</div>
<!--end::Login Sign in form-->
@endsection
