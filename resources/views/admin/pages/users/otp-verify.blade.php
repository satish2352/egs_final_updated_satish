@extends('admin.layout.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Users Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Users Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            @if ($register_user['msg_alert'] == 'green')
                                <div class="alert alert-primary" role="alert">
                                    {{ $register_user['msg'] }}
                                </div>
                            @endif
                            @if ($register_user['msg_alert'] == 'red')
                            <div class="alert alert-danger" role="alert">
                                {{ $register_user['msg'] }}
                            </div>
                        @endif
                            <form class="forms-sample" action="{{ route('otp-verification') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="otp_number">Enter OTP</label>&nbsp<span class="red-text">*</span>
                                            <input class="form-control" name="otp_number" id="otp_number"
                                                placeholder="Enter the otp">
                                            @if ($errors->has('otp_number'))
                                                <span class="red-text"><?php echo $errors->first('otp_number', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <input class="form-control" name="user_id" id="user_id" placeholder=""
                                            type="hidden" value="{{ $register_user['user_id'] }}">
                                        <input class="form-control" name="password_new" id="password_new" placeholder=""
                                            type="hidden" value="{{ $register_user['password_new'] }}">
                                        <input class="form-control" name="password_change" id="password_change"
                                            placeholder="" type="hidden" value="{{ $register_user['password_change'] }}">

                                        <input class="form-control" name="new_mobile_number" id="new_mobile_number"
                                            placeholder="" type="hidden" value="{{ $register_user['new_mobile_number'] }}">

                                        <input class="form-control" name="mobile_change" id="mobile_change" placeholder=""
                                            type="hidden" value="{{ $register_user['mobile_change'] }}">

                                        <button type="submit" class="btn btn-sm btn-success">Save &amp; Submit</button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('/dashboard') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
