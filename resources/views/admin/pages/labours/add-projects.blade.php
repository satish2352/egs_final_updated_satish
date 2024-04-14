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
                    Projects Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Projects Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-projects') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="project_name">Project Title</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="project_name" id="project_name"
                                                placeholder="" value="{{ old('project_name') }}">
                                            @if ($errors->has('project_name'))
                                                <span class="red-text"><?php echo $errors->first('project_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="state">State</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="state" name="state">
                                                <option>Select State</option>
                                                @foreach ($dynamic_state as $state)
                                                    @if (old('state') == $state['location_id'])
                                                        <option value="{{ $state['location_id'] }}" selected>
                                                            {{ $state['name'] }}</option>
                                                    @else
                                                        <option value="{{ $state['location_id'] }}">
                                                            {{ $state['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="district">District</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="district" id="district">
                                                <option value="">Select District</option>
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
                                            <label for="latitude">Latitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="latitude" id="latitude"
                                                placeholder="" value="{{ old('latitude') }}">
                                            @if ($errors->has('latitude'))
                                                <span class="red-text"><?php echo $errors->first('latitude', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="longitude" id="longitude"
                                                placeholder="" value="{{ old('longitude') }}">
                                            @if ($errors->has('longitude'))
                                                <span class="red-text"><?php echo $errors->first('longitude', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="start_date">Start date</label>&nbsp<span class="red-text">*</span>
                                            <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="start_date" id="start_date"
                                                value="{{ old('start_date') }}">
                                            @if ($errors->has('start_date'))
                                                <span class="red-text"><?php echo $errors->first('start_date', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="end_date">End date</label>&nbsp<span class="red-text">*</span>
                                            <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="end_date" id="end_date"
                                                value="{{ old('end_date') }}">
                                            @if ($errors->has('end_date'))
                                                <span class="red-text"><?php echo $errors->first('end_date', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>&nbsp<span
                                                class="red-text">*</span>
                                            <textarea class="form-control description" name="description" id="description"
                                                placeholder="Enter the Description" name="description">{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="red-text"><?php echo $errors->first('description', ':message'); ?></span>
                                            @endif
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
        <!-- <script>
            function addvalidateMobileNumber(number) {
                var mobileNumberPattern = /^\d*$/;
                var validationMessage = document.getElementById("validation-message");

                if (mobileNumberPattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Please enter only numbers.";
                }
            }
        </script> -->
        <!-- <script>
            function addvalidatePincode(number) {
                var pincodePattern = /^\d*$/;
                var validationMessage = document.getElementById("validation-message-pincode");

                if (pincodePattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Please enter only numbers.";
                }
            }
        </script> -->


        <script>
            $(document).ready(function() {

                $('#state').change(function(e) {
                    e.preventDefault();
                    var stateId = $('#state').val();
                    // console.log(stateId);
                    $('#district').html('<option value="">Select District</option>');

                    if (stateId !== '') {
                        $.ajax({
                            url: '{{ route('district') }}',
                            type: 'GET',
                            data: {
                                stateId: stateId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response);
                                if (response.district.length > 0) {
                                    $.each(response.district, function(index, district) {
                                        $('#district').append('<option value="' + district
                                            .location_id +
                                            '">' + district.name + '</option>');
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
        </script> -->

        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const project_name = $('#project_name').val();
                    const description = $('#description').val();
                    const latitude = $('#latitude').val();
                    const longitude = $('#longitude').val();
                    const state = $('#state').val();
                    const district = $('#district').val();
                    const taluka = $('#taluka').val();
                    const village = $('#village').val();

                    // console.log('project_name',project_name);
                    // console.log('description',description);
                    // console.log('latitude',latitude);
                    // console.log('longitude',longitude);
                    // console.log('state',project_name);
                    // console.log('district',district);
                    // console.log('taluka',project_name);
                    // console.log('village',village);

                    // Enable the submit button if all fields are valid
                    if (project_name && description && latitude && longitude && state && district && taluka &&
                    village) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input,textarea, select').on('input change',
                    checkFormValidity);

                //     $.validator.addMethod("number", function(value, element) {
                //     return this.optional(element) || /^[0-9]{10}$/.test(value);
                // }, "Please enter a valid 10-digit number.");

                // $.validator.addMethod("email", function(value, element) {
                //     // Regular expression for email validation
                //     const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                //     return this.optional(element) || emailRegex.test(value);
                // }, "Please enter a valid email address.");

                // Initialize the form validation
                $("#regForm").validate({
                    rules: {
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
                        designation: {
                            required: true,
                        },
                        address: {
                            required: true,
                        },
                        state: {
                            required: true,
                        },
                        city: {
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
                            // remote: "This Email already exists."
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
                        designation: {
                            required: "Please Enter the Designation",
                        },
                        address: {
                            required: "Please Enter the Address",
                        },

                        state: {
                            required: "Please Select State",
                        },
                        city: {
                            required: "Please Select State",
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
