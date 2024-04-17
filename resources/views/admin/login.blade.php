<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ env('APP_NAME') }}
    </title>
    {{-- <link rel="icon" type="image/x-icon" href="{{ asset('website_files/images/home/DM.ico') }}"> --}}
    <!-- css global-->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <!-- poppins font css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- page style css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<style>
     .password-toggle {
            cursor: pointer;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }

        .fa-eye-slash {
            /* display: none; */
        }
    </style>
<body>
    <!--  header -->
    <!--Container Scroller Start-->
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <!--  Brand Logo -->
                            <div class="brand-logo d-flex justify-content-center">
                                <img src="{{ asset('/assets/images/mlogo.png') }}" alt="logo">
                            </div>
                            <!--  Login Form -->
                            <h4 class="text-center" style="color:#392C70">EGS</h4>

                            @if (isset($return_data['msg_alert']) && $return_data['msg_alert'] == 'green')
                                <div class="alert alert-success" role="alert">
                                    {{ $return_data['msg'] }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <p>{{ session()->get('error') }} </p>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-danger" role="alert">
                                    <p> {{ session('success') }} </p>
                                </div>
                            @endif

                            <form class="pt-3 login_wrap" method="post" action='{{ route('submitLogin') }}'>
                                @csrf
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="fa fa-user text-primary"></i>
                                            </span>
                                        </div>
                                        <input class="form-control form-control-lg border-left-0" type="email"
                                            name='email' value='{{ old('email') }}' placeholder="Email">

                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="red-text"><?php echo $errors->first('email', ':message'); ?></span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="fa fa-lock text-primary"></i>
                                            </span>
                                        </div>
                                        <input class="form-control form-control-lg border-left-0 password" id="passport"
                                            type="password" name='password' placeholder='Password'>

                                            <span id="togglePassword" class="togglePpassword password-toggle"
                                            onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>

                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="red-text"><?php echo $errors->first('password', ':message'); ?></span>
                                    @endif
                                 
                                </div>
                                {{-- <div class="form-group">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}

                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="help-block">
                                            <span class="red-text">{{ $errors->first('g-recaptcha-response') }}</span>
                                        </span>
                                    @endif
                                </div> --}}


                                <div class="my-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Login</button>
                                    {{-- <div class="d-flex justify-content-sm-between mt-3 fo14">
                                        <a href="#" class="text-dark">Forgot Username</a>
                                        <a href="#" class="text-dark">Forgot Password</a>
                                    </div> --}}
                                    <div class="border-line"></div>
                                </div>
                                {{-- <div class="text-center mt-3 font-weight-light">
                                    <a href="user-register.php" class="color-org"> Don't have an account? Sign Up !</a>
                                </div> --}}
                            </form>
                            <!--  Login Form -->
                        </div>
                    </div>
                    <!--  Copyright text -->
                    <div class="col-lg-6 login-half-bg d-flex flex-row">
                       {{-- <img src="{{ asset('website_files\images\home\slide3.jpeg') }}" alt="logo">  --}}
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright ©
                            {{ date('Y') }}.
                            All rights reserved with Admin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-scroller end-->
    <!--footer-->
    <!-- global js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/js/vendor.bundle.addons.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <script>
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                var parent = $($(this).parent());

                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));

                    if (prev.length) {
                        $(prev).select();
                    }
                } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (
                        e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));

                    if (next.length) {
                        $(next).select();
                    } else {
                        if (parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $(".btn-otp").click(function() {
            $(".otp-area").slideUp();
            $(".redirect").slideDown();
        });

        $(".btn-otp2").click(function() {
            $(".otp-area2").slideUp();
            $(".redirecting2").slideDown();
        });

        $(".btn-auth").click(function() {
            $(".business-details").slideDown();
        });

        $(".btn-auth-2").click(function() {
            $(".business-details-2").slideDown();
        });

        $(".btn-non-impo").click(function() {
            $(".non-food-importer").slideDown();
            $(".food-importer").slideUp();
            $(".authorized-user").slideUp();
            $(".btn-non-impo").addClass("btn-register-active");
            $(".btn-autho-user").removeClass("btn-register-active");
            $(".btn-importer").removeClass("btn-register-active");
        });

        $(".btn-autho-user").click(function() {
            $(".authorized-user").slideDown();
            $(".food-importer").slideUp();
            $(".non-food-importer").slideUp();
            $(".btn-autho-user").addClass("btn-register-active");
            $(".btn-importer").removeClass("btn-register-active");
            $(".btn-non-impo").removeClass("btn-register-active");
        });

        $(".btn-importer").click(function() {
            $(".food-importer").slideDown();
            $(".authorized-user").slideUp();
            $(".non-food-importer").slideUp();
            $(".btn-autho-user").removeClass("btn-register-active");
            $(".btn-importer").addClass("btn-register-active");
            $(".btn-non-impo").removeClass("btn-register-active");
        });
    </script>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementsByClassName("password")[0];
        var toggleIcon = document.querySelector(".togglePpassword i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        }
    }
</script>
</body>

</html>
