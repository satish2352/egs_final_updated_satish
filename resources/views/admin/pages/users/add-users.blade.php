@extends('admin.layout.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Users Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Users Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-users') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email ID</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="email" id="email"
                                                placeholder="" value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            @if ($errors->has('email'))
                                                <span class="red-text"><?php echo $errors->first('email', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="role_id">Role Type</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="role_id" name="role_id"
                                                onchange="myFunction(this.value)">
                                                <option value="">Select</option>
                                                @foreach ($roles as $role)
                                                    @if (old('role_id') == $role['id'])
                                                        <option value="{{ $role['id'] }}" selected>
                                                            {{ $role['role_name'] }}</option>
                                                    @else
                                                        <option value="{{ $role['id'] }}">{{ $role['role_name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('role_id'))
                                                <span class="red-text"><?php echo $errors->first('role_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>&nbsp<span class="red-text">*</span>
                                            <input type="password" class="password form-control" name="password"
                                                id="password" placeholder="" value="{{ old('password') }}">
                                            <span id="togglePassword" class="togglePpassword password-toggle"
                                                onclick="togglePasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                            @if ($errors->has('password'))
                                                <span class="red-text"><?php echo $errors->first('password', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>&nbsp<span
                                                class="red-text">*</span>
                                            <input type="password" class="password_confirmation form-control"
                                                id="password_confirmation" name="password_confirmation"
                                                value="{{ old('password_confirmation') }}">
                                            <span id="toggleConfirmPassword" class=" toggleConfirmPpassword password-toggle"
                                                onclick="toggleConfirmPasswordVisibility()">
                                                <i class="fa fa-eye-slash"></i>
                                            </span>
                                            <span id="password-error" class="error-message red-text"></span>
                                            @if ($errors->has('password_confirmation'))
                                                <span class="red-text"><?php echo $errors->first('password_confirmation', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="f_name">First Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="f_name" id="f_name"
                                                placeholder="" value="{{ old('f_name') }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('f_name'))
                                                <span class="red-text"><?php echo $errors->first('f_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="m_name">Middle Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="m_name" id="m_name"
                                                placeholder="" value="{{ old('m_name') }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('m_name'))
                                                <span class="red-text"><?php echo $errors->first('m_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="l_name">Last Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="l_name" id="l_name"
                                                placeholder="" value="{{ old('l_name') }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('l_name'))
                                                <span class="red-text"><?php echo $errors->first('l_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="number">Mobile Number</label>&nbsp<span
                                                class="red-text">*</span>
                                            <input type="text" class="form-control" name="number" id="number"
                                                pattern="[789]{1}[0-9]{9}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                                                maxlength="10" minlength="10" placeholder=""
                                                value="{{ old('number') }}"
                                                onkeyup="addvalidateMobileNumber(this.value)">
                                            <span id="validation-message" class="red-text"></span>
                                            @if ($errors->has('number'))
                                                <span class="red-text"><?php echo $errors->first('number', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="aadhar_no">Aadhar No.</label>&nbsp<span
                                                class="red-text">*</span>
                                            <input type="text" class="form-control" name="aadhar_no" maxlength="14"
                                                id="aadhar_no" placeholder="" value="{{ old('aadhar_no') }}" onkeyup="formatAadharNumber(this)">
                                            @if ($errors->has('number'))
                                                <span class="red-text"><?php echo $errors->first('aadhar_no', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="address" id="address"
                                                placeholder="" value="{{ old('address') }}">
                                            @if ($errors->has('address'))
                                                <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="district">District</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="district" id="district">
                                                <option value="">Select District</option>
                                                @foreach ($dynamic_district as $district)
                                                    @if (old('district') == $district['location_id'])
                                                        <option value="{{ $district['location_id'] }}" selected>
                                                            {{ $district['name'] }}</option>
                                                    @else
                                                        <option value="{{ $district['location_id'] }}">
                                                            {{ $district['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('district'))
                                                <span class="red-text"><?php echo $errors->first('district', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="taluka">Taluka</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="taluka" id="taluka">
                                                <option value="">Select Taluka</option>
                                            </select>
                                            @if ($errors->has('taluka'))
                                                <span class="red-text"><?php echo $errors->first('taluka', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="village">Village</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="village" id="village">
                                                <option value="">Select Village</option>
                                            </select>
                                            @if ($errors->has('village'))
                                                <span class="red-text"><?php echo $errors->first('village', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="user_profile">Profile Photo</label>&nbsp<span
                                                class="red-text">*</span><br>
                                            <input type="file" name="user_profile" id="user_profile" accept="image/*"
                                                value="{{ old('user_profile') }}"><br>
                                            @if ($errors->has('user_profile'))
                                                <span class="red-text"><?php echo $errors->first('user_profile', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="pincode">Pincode</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="pincode" id="pincode"
                                                placeholder="" value="{{ old('pincode') }}"
                                                onkeyup="addvalidatePincode(this.value)">
                                            <span id="validation-message-pincode" class="red-text"></span>
                                            @if ($errors->has('pincode'))
                                                <span class="red-text"><?php echo $errors->first('pincode', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        
                                    </div>
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12" style="border: 1px solid #040479;padding: 2%;">
                                        <h5 class="d-flex justify-content-center mb-4">User Working Details</h5>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="usertype_name">User Type</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_type" id="user_type">
                                                        <option value="">Select User Type</option>
                                                        @foreach ($dynamic_usertype as $usertype)
                                                            @if (old('usertype_name') == $usertype['id'])
                                                                <option value="{{ $usertype['id'] }}" selected>
                                                                    {{ $usertype['usertype_name'] }}</option>
                                                            @else
                                                                <option value="{{ $usertype['id'] }}">
                                                                    {{ $usertype['usertype_name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('user_type'))
                                                        <span class="red-text"><?php echo $errors->first('user_type', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="district">User District</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_district" id="user_district">
                                                        <option value="">Select District</option>
                                                        @foreach ($dynamic_district as $district)
                                                            @if (old('district') == $district['location_id'])
                                                                <option value="{{ $district['location_id'] }}" selected>
                                                                    {{ $district['name'] }}</option>
                                                            @else
                                                                <option value="{{ $district['location_id'] }}">
                                                                    {{ $district['name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('district'))
                                                        <span class="red-text"><?php echo $errors->first('district', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="taluka">User Taluka</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_taluka" id="user_taluka">
                                                        <option value="">Select Taluka</option>
                                                    </select>
                                                    @if ($errors->has('taluka'))
                                                        <span class="red-text"><?php echo $errors->first('taluka', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="village">User Village</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_village" id="user_village">
                                                        <option value="">Select Village</option>
                                                    </select>
                                                    @if ($errors->has('village'))
                                                        <span class="red-text"><?php echo $errors->first('village', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                        <div id="data_for_role">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                                        <div class="form-group form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    {{ old('is_active') ? 'checked' : '' }}>
                                                Is Active
                                                <i class="input-helper"></i><i class="input-helper"></i></label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-users') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function submitRegister() {
                document.getElementById("frm_register").submit();
            }
        </script>
        <script>
            function addvalidateMobileNumber(number) {
                var mobileNumberPattern = /^\d*$/;
                var validationMessage = document.getElementById("validation-message");

                if (mobileNumberPattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Please enter only numbers.";
                }
            }
        </script>
        <script>
            function addvalidatePincode(number) {
                var pincodePattern = /^\d*$/;
                var validationMessage = document.getElementById("validation-message-pincode");

                if (pincodePattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Please enter only numbers.";
                }
            }
        </script>


   

        <script>
            $(document).ready(function() {

                $('#district').change(function(e) {
                    e.preventDefault();
                    var districtId = $('#district').val();
                    console.log(districtId);
                    $('#taluka').html('<option value="">Select Taluka</option>');

                    if (districtId !== '') {
                        $.ajax({
                            url: '{{ route('taluka') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.taluka.length > 0) {
                                    $.each(response.taluka, function(index, taluka) {
                                        $('#taluka').append('<option value="' + taluka
                                            .location_id +
                                            '">' + taluka.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                $('#taluka').change(function(e) {
                    e.preventDefault();
                    var talukaId = $('#taluka').val();
                    console.log(talukaId);
                    $('#village').html('<option value="">Select Village</option>');

                    if (talukaId !== '') {
                        $.ajax({
                            url: '{{ route('village') }}',
                            type: 'GET',
                            data: {
                                talukaId: talukaId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.village.length > 0) {
                                    $.each(response.village, function(index, village) {
                                        $('#village').append('<option value="' + village
                                            .location_id +
                                            '">' + village.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>
        
        <!-- <script>
            $(document).ready(function() {

                $('#state').change(function(e) {
                    e.preventDefault();
                    var stateId = $('#state').val();
                    // console.log(stateId);
                    $('#city').html('<option value="">Select City</option>');

                    if (stateId !== '') {
                        $.ajax({
                            url: '{{ route('cities') }}',
                            type: 'GET',
                            data: {
                                stateId: stateId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response);
                                if (response.city.length > 0) {
                                    $.each(response.city, function(index, city) {
                                        $('#city').append('<option value="' + city
                                            .location_id +
                                            '">' + city.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script> -->
        <script>
            function myFunction(role_id) {
                // alert(role_id);
                $("#data_for_role").empty();
                $.ajax({
                    url: "{{ route('list-role-wise-permission') }}",
                    method: "POST",
                    data: {
                        "role_id": role_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#data_for_role").empty();
                        $("#data_for_role").append(data);
                    },
                    error: function(data) {}
                });
            }
        </script>

        <!--  -->

        <script>
            $(document).ready(function() {

                $('#user_district').change(function(e) {
                    e.preventDefault();
                    var districtId = $('#user_district').val();
                    console.log(districtId);
                    $('#user_taluka').html('<option value="">Select Taluka</option>');

                    if (districtId !== '') {
                        $.ajax({
                            url: '{{ route('taluka') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.taluka.length > 0) {
                                    $.each(response.taluka, function(index, taluka) {
                                        $('#user_taluka').append('<option value="' + taluka
                                            .location_id +
                                            '">' + taluka.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {

                $('#user_taluka').change(function(e) {
                    e.preventDefault();
                    var talukaId = $('#user_taluka').val();
                    console.log(talukaId);
                    $('#user_village').html('<option value="">Select Village</option>');

                    if (talukaId !== '') {
                        $.ajax({
                            url: '{{ route('village') }}',
                            type: 'GET',
                            data: {
                                talukaId: talukaId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.village.length > 0) {
                                    $.each(response.village, function(index, village) {
                                        $('#user_village').append('<option value="' + village
                                            .location_id +
                                            '">' + village.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>


        <!--  -->
        <script>
            function formatAadharNumber(input) {
                let value = input.value.replace(/\s/g, ''); // Remove existing spaces
                value = value.replace(/\D/g, ''); // Remove non-numeric characters
                let formattedInput = '';
                for (let i = 0; i < value.length; i++) {
                    formattedInput += value[i];
                    if ((i + 1) % 4 === 0 && i !== value.length - 1) {
                        formattedInput += ' ';
                    }
                }
                input.value = formattedInput;
            }
        </script>

        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const email = $('#email').val();
                    const role_id = $('#role_id').val();
                    const password = $('#password').val();
                    const password_confirmation = $('#password_confirmation').val();
                    const f_name = $('#f_name').val();
                    const m_name = $('#m_name').val();
                    const l_name = $('#l_name').val();
                    const number = $('#number').val();
                    const aadhar_no = $('#aadhar_no').val();
                    const address = $('#address').val();
                    const district = $('#district').val();
                    const taluka = $('#taluka').val();
                    const village = $('#village').val();
                    const user_profile = $('#user_profile').val();
                    const pincode = $('#pincode').val();

                    // Enable the submit button if all fields are valid
                    if (email && role_id && password && password_confirmation && f_name && m_name && l_name && number && aadhar_no && address
                    && district && taluka && village && user_profile && pincode) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input,textarea, select, #user_profile').on('input change',
                    checkFormValidity);

                    $.validator.addMethod("number", function(value, element) {
                    return this.optional(element) || /^[0-9]{10}$/.test(value);
                }, "Please enter a valid 10-digit number.");

                $.validator.addMethod("email", function(value, element) {
                    // Regular expression for email validation
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return this.optional(element) || emailRegex.test(value);
                }, "Please enter a valid email address.");

        //Aadhar Card NUmber Validation
            $.validator.addMethod("aadharNumber", function(value, element) {
                var aadharPattern = /^\d{4}\s\d{4}\s\d{4}$/;
                    return this.optional(element) || aadharPattern.test(value);
                }, "Please enter a valid Aadhar number");

                // Initialize the form validation
                $("#frm_register").validate({
                    rules: {
                        email: {
                            required: true,
                            email:true,
                        },
                        role_id: {
                            required: true,
                        },
                        password: {
                            required: true,
                        },
                        password_confirmation: {
                            required: true,
                        },
                        f_name: {
                            required: true,
                        },
                        m_name: {
                            required: true,
                        },
                        l_name: {
                            required: true,
                        },
                        number: {
                            required: true,
                            number:true,
                        },
                        aadhar_no: {
                            required: true,
                            aadharNumber: true
                        },
                        address: {
                            required: true,
                        },
                        district: {
                            required: true,
                        },
                        taluka: {
                            required: true,
                        },
                        village: {
                            required: true,
                        },
                        user_profile: {
                            required: true,
                        },
                        pincode: {
                            required: true,
                        },

                    },
                    messages: {
                        email: {
                            required: "Please Enter the Eamil",
                        },
                        role_id: {
                            required: "Please Select Role Name",
                        },
                        password: {
                            required: "Please Enter the Password",
                        },
                        password_confirmation: {
                            required: "Please Enter the Confirmation Password",
                        },
                        f_name: {
                            required: "Please Enter the First Name",
                        },
                        m_name: {
                            required: "Please Enter the Middle Name",
                        },
                        l_name: {
                            required: "Please Enter the Last Name",
                        },
                        number: {
                            required: "Please Enter the Number",
                        },
                        aadhar_no: {
                            required: "Please Enter the Aadhar No",
                            pattern: "Please enter a valid Aadhar number (e.g., 1234 5678 9101)", // Custom error message for Aadhar card number validation
                        },
                        address: {
                            required: "Please Enter the Address",
                        },
                        district: {
                            required: "Please Select District",
                        },
                        taluka: {
                            required: "Please Select Taluka",
                        },
                        village: {
                            required: "Please Select Village",
                        },
                        user_profile: {
                            required: "Upload Media File",
                            accept: "Only png, jpeg, and jpg image files are allowed.", // Update the error message for the accept rule
                        },
                        pincode: {
                            required: "Please Enter the Pincode",
                        },
                    },

                });
            });

            $.validator.setDefaults({
            onfocusout: function (element, event) {
                if (!this.checkable(element) && (event.type !== 'focusout' || !this.hasInput(element))) {
                    this.element(element);
                }
            }
        });
        </script>
    @endsection
