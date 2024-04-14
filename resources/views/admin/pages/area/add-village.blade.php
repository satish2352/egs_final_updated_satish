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
                    Village Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-village') }}">Area Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Add Taluka</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-village') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
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
                                            <label for="name">Village Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="" value="{{ old('name') }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('name'))
                                                <span class="red-text"><?php echo $errors->first('name', ':message'); ?></span>
                                            @endif
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
                                        <span><a href="{{ route('list-village') }}"
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
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const name = $('#name').val();
                    // Enable the submit button if all fields are valid
                    if (name) {
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


                $(document).ready(function() {
            $.validator.addMethod("aadharValidation", function(value, element) {
                console.log('ffffffffffffffffff',value);
    // Regular expression pattern for Aadhaar number validation
    var aadhaarPattern = /^\d{12}$/;

    console.log(aadhaarPattern.test(value));
    // Check if the input matches the pattern
    if (!aadhaarPattern.test(value)) {
        return false;
    }
    // Aadhaar checksum validation algorithm
    var sum = 0;
    for (var i = 0; i < 11; i++) {
        sum += parseInt(value.charAt(i)) * (12 - i);
    }
    var remainder = sum % 11;
    if (remainder != 0) {
        return false;
    }

    // Aadhaar number is valid
    return true;
}, "Invalid Aadhaar number");
            });

                // Initialize the form validation
                $("#frm_register").validate({
                    rules: {
                        email: {
                            required: true,
                            remote: {
                            url: '{{ route('check-email-exists') }}',
                            type: 'get',
                            data: {
                                email: function() {
                                    return $('#email').val();
                                }
                            }
                        },
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
                            pattern: /^\d{4}\s\d{4}\s\d{4}$/,
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
                            remote: "This Email already exists.",
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

          
        </script>
    @endsection
