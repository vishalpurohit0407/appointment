@extends('admin.auth.master')
@section('title')
  Admin Forgot Password
@endsection
@section('content')
<!--begin::Login forgot password form-->
<div class="login-forgot">
    <div class="mb-20">
        <h3>Forgotten Password ?</h3>
        <div class="text-muted font-weight-bold">Enter your email to reset your password</div>
    </div>
    <form role="form" method="POST" action="{{ route('admin.password.eamil') }}">
        @csrf
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="form-group mb-10">
            <input class="form-control form-control-solid h-auto py-4 px-8 {{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Email *" name="email" value="{{ old('email') }}"  autofocus />
            @error('email')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
        <div class="form-group d-flex flex-wrap flex-center mt-10">
            <button type="submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Send Password Reset Link</button>
            <a href="{{route('admin.login')}}" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Cancel</a>
        </div>
    </form>
</div>
<!--end::Login forgot password form-->
@endsection
