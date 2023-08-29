@extends('layouts.auth')

@section('body')
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat"
            style="background-image: url('assets/media/bg/bg-3.jpg');">
            <div class="login-form text-center p-7 position-relative overflow-hidden">
                <!--begin::Login Header-->
                <div class="d-flex flex-center mb-15">
                    <a href="#">
                        <img src="logo_bsb_2.png" class="max-h-75px" alt="" />
                    </a>
                </div>
                <!--end::Login Header-->
                <!--begin::Login Sign in form-->
                <div class="login-signin">
                    <div class="mb-20">
                        <h3>Sign In</h3>
                        <div class="text-muted font-weight-bold">Enter your details to login to your account:</div>
                    </div>
                    <div class="mb-10">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        @if ($errors->any())

                        <div class="alert alert-danger">

                            @foreach ($errors->all() as $error)
                            {{ $error }}</br>
                            @endforeach

                        </div>
                        @endif
                    </div>
                    <form class="form" id="kt_login_signin_form" action="/login" method="post">
                        @csrf
                        
                        <div class="form-group mb-5">
                            <input
                                class="form-control h-auto form-control-solid py-4 px-8 @error('email') is-invalid @enderror"
                                type="text" placeholder="Email" name="email" autocomplete="off" />
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-5">
                            <input
                                class="form-control h-auto form-control-solid py-4 px-8 @error('password') is-invalid @enderror"
                                type="password" placeholder="Password" name="password" />
                        </div>
                        @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                            <div class="checkbox-inline">
                                <label class="checkbox m-0 text-muted">
                                    <input type="checkbox" name="remember" id="remember" />
                                    <span></span>
                                    Remember me
                                </label>
                            </div>
                            <a href="/forgot-password" id="kt_login_forgot" class="text-muted text-hover-primary">Forget
                                Password ?</a>
                        </div>
                        <button id="kt_login_signin_submit"
                            class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Sign In</button>
                    </form>
                    <div class="mt-10">
                        <span class="opacity-70 mr-4">
                            Don't have an account yet?
                        </span>
                        <a href="/register" id="kt_login_signup"
                            class="text-muted text-hover-primary font-weight-bold">Sign
                            Up!</a>
                    </div>
                </div>
                <!--end::Login Sign in form-->

            </div>
        </div>
    </div>
    <!--end::Login-->
</div>
@endsection


@push('script')
<script src="assets/js/pages/custom/login/login-general.js?v=7.0.6"></script>
@endpush