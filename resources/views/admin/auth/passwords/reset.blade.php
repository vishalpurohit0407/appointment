@extends('admin.auth.master')
@section('title')
  Admin Reset Password
@endsection
@section('content')
<!--begin::Login reset password form-->
<div class="login-resetpass">
    <div class="mb-20">
        <h3>Reset Password</h3>
        <div class="text-muted font-weight-bold">Enter your new password to change password</div>
    </div>
    <form role="form" method="POST" action="{{ route('admin.password.reset') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8 {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Email *" name="email" autocomplete="off" readonly value="{{ $email ?? old('email') }}"/>
            @error('email')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8 {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" placeholder="Password *" name="password" autofocus/>
            @error('password')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group mb-5">
            <input class="form-control h-auto form-control-solid py-4 px-8 {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" type="password" placeholder="Confirm Password *" name="password_confirmation" />
            @error('password_confirmation')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <button class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Update Password</button>
        </div>
    </form>
</div>
<!--end::Login reset password form-->
@endsection
