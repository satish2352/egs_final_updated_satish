<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>
        {{ env('APP_NAME') }}
    </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('website_files/images/home/DM.ico') }}">
    <!-- global css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ti-icons/css/themify-icons.css') }}">
    <!-- poppins font css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <!-- page css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stepwizard.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('website_files/images/home/DMS.png') }}" />
    <!-- toastr-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet" />
    <!-- {{-- CKEditor CDN --}} -->
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    <!-- Summernote Editor -->

    <link href="{{ asset('assets/bootstrap5.3.0/css/bootstrap.css') }}" type="text/css"
    rel="stylesheet">
    <link href="{{ asset('assets/bootstrap5.3.0/css/bootstrap.min.css') }}" type="text/css"
    rel="stylesheet">
    <script src="{{ asset('assets/bootstrap5.3.0/js/bootstrap.bundle.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>  --}}

    <link rel="stylesheet" href="{{ asset('assets/vendors/summernote/dist/summernote-bs4.css') }}">
    <script src="{{ asset('assets/vendors/summernote/dist/summernote-bs4.min.js') }}"></script>

    <!-- Include Spectrum CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">

<!-- Include Spectrum JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>



    
    <!-- Summernote Editor End -->
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <style>
        .error {
            /* color: #d8000c; */
            color: red !important;
            font-size: 14px;
            font-weight: 400 !important;
        }

        .close {
            margin-top: -5px;

        }

    </style>

</head>
<?php //$profile = getProfileImage();
 ?>

<?php 
             //$common_data = App\Http\Controllers\Admin\IndexController::getCommonWebData();
             ?>
<body class="sidebar-icon-only">


    <div class="container-scroller">
        <!-- top navigation -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="#"><img
                        src="{{ asset('/assets/images/mlogo.png') }}" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="#"><img
                        src="{{ asset('/assets/images/mlogo.png') }}" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch pr-0">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="fas fa-bars"></span>
                </button>
                <ul class="navbar-nav">
                    <li class="nav-item d-md-flex logo-title">
                        {{ env('APP_NAME') }}
                    </li>
                </ul>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown mr-0">

                    
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            {{-- <img class="img-size" 
                            src="{{ Config::get('DocumentConstant.USER_PROFILE_VIEW') }}{{ $profile->user_profile }}"
                            /> --}}
                            <i class="fas fa-user menu-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            {{-- @foreach ($user_data as $item) --}}
                            <a 
                            {{-- data-id="{{ $user_data->id }}"  --}}
                            class="dropdown-item edit-user-btn"
                                href="{{ route('edit-user-profile') }}">
                                <i class="fas fa-user text-primary"></i>
                                Profile
                            </a>
                            {{-- @endforeach --}}
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('log-out') }}">
                                <i class="fas fa-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="fas fa-bars"></span>
                </button>
            </div>
        </nav>


        <form method="GET" action="{{ url('/edit-user-profile') }}" id="edituserform">
            @csrf
            <input type="hidden" name="edit_user_id" id="edit_user_id" value="">
        </form>

        <div class="container-fluid page-body-wrapper">
            @include('admin.layout.sidebar')
            @yield('content')
            @extends('admin.layout.footer')
