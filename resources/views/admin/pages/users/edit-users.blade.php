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
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Users Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="frm_register" method="post" role="form"
                                action="{{ route('update-users') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email ID</label>&nbsp<span class="red-text">*</span>
                                        <input type="text" class="form-control" name="email" id="email"
                                            placeholder="" value="{{$user_data['data_users']['email']}}">
                                        @if ($errors->has('email'))
                                        <span
                                            class="red-text"><?php echo $errors->first('email', ':message'); ?></span>
                                        @endif
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Personal Email ID</label>&nbsp<span class="red-text">*</span>
                                        <input type="text" class="form-control" name="personal_email" id="personal_email"
                                            placeholder="" value="{{$user_data['data_users']['personal_email']}}">
                                        @if ($errors->has('personal_email'))
                                        <span
                                            class="red-text"><?php echo $errors->first('personal_email', ':message'); ?></span>
                                        @endif
                                    </div>
                                </div> 
                                {{--  <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="u_uname">User Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="u_uname" id="u_uname"
                                                placeholder="" value="{{ $user_data['data_users']['u_uname'] }}">
                                            @if ($errors->has('u_uname'))
                                                <span class="red-text"><?php echo $errors->first('u_uname', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>&nbsp<span class="red-text">*</span>
                                        <input type="text" class="form-control" name="password" id="password"
                                            placeholder="" value="{{decrypt($user_data['data_users']['password'])}}">
                                        @if ($errors->has('password'))
                                        <span
                                            class="red-text"><?php echo $errors->first('password', ':message'); ?></span>
                                        @endif
                                    </div>
                                </div> --}}

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="role_id">Role Type</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="role_id" name="role_id"
                                                onchange="myFunction(this.value)">
                                                <option>Select</option>
                                                @foreach ($user_data['roles'] as $role)
                                                    <option value="{{ $role['id'] }}"
                                                        @if ($role['id'] == $user_data['data_users']['role_id']) <?php echo 'selected'; ?> @endif>
                                                        {{ $role['role_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('role_id'))
                                                <span class="red-text"><?php echo $errors->first('role_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="f_name">First Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="f_name" id="f_name"
                                                placeholder="" value="{{ $user_data['data_users']['f_name'] }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('f_name'))
                                                <span class="red-text"><?php echo $errors->first('f_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="m_name">Middle Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="m_name" id="m_name"
                                                placeholder="" value="{{ $user_data['data_users']['m_name'] }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('m_name'))
                                                <span class="red-text"><?php echo $errors->first('m_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="l_name">Last Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="l_name" id="l_name"
                                                placeholder="" value="{{ $user_data['data_users']['l_name'] }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('l_name'))
                                                <span class="red-text"><?php echo $errors->first('l_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="number">Number</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="number" id="number"
                                                placeholder="" value="{{ $user_data['data_users']['number'] }}"
                                                onkeyup="editvalidateMobileNumber(this.value)"
                                                pattern="[789]{1}[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"  maxlength="10" minlength="10"
                                                >
                                            <span id="edit-message" class="red-text"></span>
                                            @if ($errors->has('number'))
                                                <span class="red-text"><?php echo $errors->first('number', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="aadhar_no">Aadhar No</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="aadhar_no" id="aadhar_no"
                                                placeholder="" value="{{ $user_data['data_users']['aadhar_no'] }}"
                                                onkeyup="formatAadharNumber(this)">
                                            @if ($errors->has('aadhar_no'))
                                                <span class="red-text"><?php echo $errors->first('aadhar_no', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="address" id="address"
                                                placeholder="" value="{{ $user_data['data_users']['address'] }}">
                                            @if ($errors->has('address'))
                                                <span class="red-text"><?php echo $errors->first('address', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="district">User District</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="district" id="district">
                                                        <option value="">Select District</option>
                                                        @foreach ($dynamic_district as $district)
                                                        <option value="{{ $district['location_id'] }}"
                                                        @if ($district['location_id'] == $user_data['data_users']['district']) <?php echo 'selected'; ?> @endif>
                                                        {{ $district['name'] }}</option>
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
                                            <select class="form-control mb-2" name="taluka" id="taluka">
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
                                            <select class="form-control mb-2" name="village" id="village">
                                                <option value="">Select Village</option>
                                            </select>
                                            @if ($errors->has('village'))
                                                <span class="red-text"><?php echo $errors->first('village', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="pincode">Pincode</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="pincode" id="pincode"
                                                placeholder="" value="{{ $user_data['data_users']['pincode'] }}"
                                                onkeyup="editvalidatePincode(this.value)">
                                            <span id="edit-message-pincode" class="red-text"></span>
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
                                                        <option value="{{ $usertype['id'] }}"
                                                        @if ($usertype['id'] == $user_data['data_users']['user_type']) <?php echo 'selected'; ?> @endif>
                                                        {{ $usertype['usertype_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('usertype_name'))
                                                        <span class="red-text"><?php echo $errors->first('usertype_name', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="district">User District</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_district" id="user_district">
                                                        <option value="">Select District</option>
                                                        @foreach ($dynamic_district as $district)
                                                        <option value="{{ $district['location_id'] }}"
                                                        @if ($district['location_id'] == $user_data['data_users']['user_district']) <?php echo 'selected'; ?> @endif>
                                                        {{ $district['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('district'))
                                                        <span class="red-text"><?php echo $errors->first('district', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="user_taluka">User Taluka</label>&nbsp<span class="red-text">*</span>
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
                                                    <label for="user_village">User Village</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="user_village" id="user_village">
                                                        <option value="">Select Village</option>
                                                    </select>
                                                    @if ($errors->has('user_village'))
                                                        <span class="red-text"><?php echo $errors->first('user_village', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>    

                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    @if ($user_data['data_users']['is_active']) <?php echo 'checked'; ?> @endif>
                                                Is Active
                                                <i class="input-helper"></i><i class="input-helper"></i></label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                        <div id="data_for_role">
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 text-center mt-3">
                                        <input type="hidden" class="form-check-input" name="edit_id" id="edit_id"
                                            value="{{ $user_data['data_users']['id'] }}">
                                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                                Save &amp; Update
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
            
            function getState(stateId) {
                $('#state').html('<option value="">Select State</option>');
                if (stateId !== '') {
                    $.ajax({
                        url: '{{ route('states') }}',
                        type: 'GET',
                        data: {
                            stateId: stateId
                        },
                        success: function(response) {
                            if (response.state.length > 0) {
                                $.each(response.state, function(index, state) {
                                    $('#state').append('<option value="' + state
                                        .location_id +
                                        '" selected>' + state.name + '</option>');
                                });
                                $('#state').val(stateId);
                            }
                        }
                    });
                }
            }

            function getStateDistrict(stateId, district_id) {

                $('#district').html('<option value="">Select District</option>');
                if (stateId !== '') {
                    $.ajax({
                        url: '{{ route('district') }}',
                        type: 'GET',
                        data: {
                            stateId: stateId
                        },

                        success: function(response) {
                            if (response.district.length > 0) {
                                $.each(response.district, function(index, district) {
                                    $('#district').append('<option value="' + district
                                        .location_id +
                                        '" selected>' + district.name + '</option>');
                                });
                                if (district_id != null) {
                                    $('#district').val(district_id);
                                } else {
                                    $('#district').val("");
                                }
                            }
                        }
                    });
                }
            }

            function getDistrictTaluka(districtId, taluka_id) {

                $('#taluka').html('<option value="">Select District</option>');
                $('#village').html('<option value="">Select Village</option>');
                if (districtId !== '') {
                    $.ajax({
                        url: '{{ route('taluka') }}',
                        type: 'GET',
                        data: {
                            districtId: districtId
                        },

                        success: function(response) {
                            if (response.taluka.length > 0) {
                                $.each(response.taluka, function(index, taluka) {
                                    $('#taluka').append('<option value="' + taluka
                                        .location_id +
                                        '" selected>' + taluka.name + '</option>');
                                });
                                if (taluka_id != null) {
                                    $('#taluka').val(taluka_id);
                                } else {
                                    $('#taluka').val("");
                                }
                            }
                        }
                    });
                }
            }

            function getWorkingDistrictTaluka(districtId, taluka_id) {

                $('#user_taluka').html('<option value="">Select District</option>');
                $('#user_village').html('<option value="">Select District</option>');
                if (districtId !== '') {
                    $.ajax({
                        url: '{{ route('taluka') }}',
                        type: 'GET',
                        data: {
                            districtId: districtId
                        },

                        success: function(response) {
                            if (response.taluka.length > 0) {
                                $.each(response.taluka, function(index, taluka) {
                                    $('#user_taluka').append('<option value="' + taluka
                                        .location_id +
                                        '" selected>' + taluka.name + '</option>');
                                });
                                if (taluka_id != null) {
                                    $('#user_taluka').val(taluka_id);
                                } else {
                                    $('#user_taluka').val("");
                                }
                            }
                        }
                    });
                }
            }

            function getTalukaVillage(talukaId, village_id) {

                $('#village').html('<option value="">Select Village</option>');
                if (talukaId !== '') {
                    $.ajax({
                        url: '{{ route('village') }}',
                        type: 'GET',
                        data: {
                            talukaId: talukaId
                        },

                        success: function(response) {
                            if (response.village.length > 0) {
                                $.each(response.village, function(index, village) {
                                    $('#village').append('<option value="' + village
                                        .location_id +
                                        '" selected>' + village.name + '</option>');
                                });
                                if (village_id != null) {
                                    $('#village').val(village_id);
                                } else {
                                    $('#village').val("");
                                }
                            }
                        }
                    });
                }
            }

            function getWorkingTalukaVillage(talukaId, village_id) {

            $('#user_village').html('<option value="">Select Village</option>');
                if (talukaId !== '') {
                    $.ajax({
                        url: '{{ route('village') }}',
                        type: 'GET',
                        data: {
                            talukaId: talukaId
                        },

                        success: function(response) {
                            if (response.village.length > 0) {
                                $.each(response.village, function(index, village) {
                                    $('#user_village').append('<option value="' + village
                                        .location_id +
                                        '" selected>' + village.name + '</option>');
                                });
                                if (village_id != null) {
                                    $('#user_village').val(village_id);
                                } else {
                                    $('#user_village').val("");
                                }
                            }
                        }
                    });
                }
            }
        </script>

        <script type="text/javascript">
            function submitRegister() {
                document.getElementById("frm_register").submit();
            }
        </script>
        <script>
            function editvalidateMobileNumber(number) {
                var mobileNumberPattern = /^\d*$/;
                var validationMessage = document.getElementById("edit-message");

                if (mobileNumberPattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Only numbers are allowed.";
                }
            }
        </script>
        <script>
            function editvalidatePincode(number) {
                var pincodePattern = /^\d*$/;
                var validationMessage = document.getElementById("edit-message-pincode");

                if (pincodePattern.test(number)) {
                    validationMessage.textContent = "";
                } else {
                    validationMessage.textContent = "Only numbers are allowed.";
                }
            }
        </script>

        <script>
            $(document).ready(function() {
                myFunction($("#role_id").val());
                getDistrictTaluka('{{ $user_data['data_users']['district'] }}', '{{ $user_data['data_users']['taluka'] }}');
                getWorkingDistrictTaluka('{{ $user_data['data_users']['user_district'] }}', '{{ $user_data['data_users']['user_taluka'] }}');
                getTalukaVillage('{{ $user_data['data_users']['taluka'] }}', '{{ $user_data['data_users']['village'] }}');
                getWorkingTalukaVillage('{{ $user_data['data_users']['user_taluka'] }}', '{{ $user_data['data_users']['user_village'] }}');

                $("#state").on('change', function() {
                    getStateDistrict($("#state").val(),'');
                });

                $("#district").on('change', function() {
                    getDistrictTaluka($("#district").val(),'');
                });
                $("#user_district").on('change', function() {
                    getWorkingDistrictTaluka($("#user_district").val(),'');
                });

                $("#taluka").on('change', function() {
                    getTalukaVillage($("#taluka").val(),'');
                });

                $("#user_taluka").on('change', function() {
                    getWorkingTalukaVillage($("#user_taluka").val(),'');
                });
            });

            function myFunction(role_id) {
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
         <script>
            $(document).ready(function() {
                $.validator.addMethod('mypassword', function(value, element) {
                        return this.optional(element) || (value.match(/[a-z]/) && value.match(/[A-Z]/) && value
                            .match(/[0-9]/));
                    },
                    'Password must contain at least one uppercase, lowercase and numeric');

                $("#frm_register1").validate({
                    rules: {

                        password: {
                            //required: true,
                            minlength: 6,
                            mypassword: true

                        },
                        password_confirmation: {
                            //required: true,
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        password: {
                            required: "Please enter your new password",
                            minlength: "Password should be minimum 8 characters"
                        },
                        password_confirmation: {
                            required: "Please Enter Password Same as New Password for Confirmation",
                            equalTo: "Password does not Match! Please check the Password"
                        }
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $("#show_hide_password a").on('click', function(event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');
                        $('#show_hide_password i').addClass("bx-hide");
                        $('#show_hide_password i').removeClass("bx-show");
                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password i').removeClass("bx-hide");
                        $('#show_hide_password i').addClass("bx-show");
                    }
                });
            });
        </script>
  <script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const role_id = $('#role_id').val();
            const f_name = $('#f_name').val();
            const m_name = $('#m_name').val();
            const l_name = $('#l_name').val();
            const number = $('#number').val();
            const designation = $('#designation').val();
            const address = $('#address').val();
            const state = $('#state').val();
            const city = $('#city').val();
            // const user_profile = $('#user_profile').val();
            const pincode = $('#pincode').val();
            
        }

        // Call the checkFormValidity function on file input change
        $('input, #english_image, #marathi_image').on('change', function() {
            checkFormValidity();
            validator.element(this); // Revalidate the file input
        });
        // Initialize the form validation
        var form = $("#regForm");
        // Initialize the form validation
                $("#frm_register").validate({
                    rules: {
                        email: {
                            required: true,
                        //     remote: {
                        //     url: '{{ route('check-email-exists') }}',
                        //     type: 'get',
                        //     data: {
                        //         email: function() {
                        //             return $('#email').val();
                        //         }
                        //     }
                        // },
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
                            number:true,
                            // aadharValidation: true,
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
                        aadhar_no: {
                            required: "Please Enter the Aadhar No",
                            // aadharValidation: "Please enter a valid Aadhaar number",
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

        // Submit the form when the "Update" button is clicked
        $("#submitButton").click(function() {
            // Validate the form
            if (form.valid()) {
                form.submit();
            }
        });
    });
</script>    



{{-- <script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            // const u_email = $('#u_email').val();
            const role_id = $('#role_id').val();
            // const u_password = $('#u_password').val();
            // const password_confirmation = $('#password_confirmation').val();
            const f_name = $('#f_name').val();
            const m_name = $('#m_name').val();
            const l_name = $('#l_name').val();
            const number = $('#number').val();
            const designation = $('#designation').val();
            const address = $('#address').val();
            const state = $('#state').val();
            const city = $('#city').val();
            // const user_profile = $('#user_profile').val();
            const pincode = $('#pincode').val();

            // Enable the submit button if all fields are valid
            if (role_id && f_name && m_name && l_name && number && designation && address && state && city && pincode) {
                $('#submitButton').prop('disabled', false);
            } else {
                $('#submitButton').prop('disabled', true);
            }
        }

        // Call the checkFormValidity function on input change
        $('input,textarea, select, #user_profile').on('input change',
            checkFormValidity);

        // Initialize the form validation
        $("#regForm").validate({
            rules: {
                // u_email: {
                //     required: true,
                // },
                role_id: {
                    required: true,
                },
                // u_password: {
                //     required: true,
                // },
                // password_confirmation: {
                //     required: true,
                // },
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
                // user_profile: {
                //     required: true,
                // },
                pincode: {
                    required: true,
                },

            },
            messages: {
                // u_email: {
                //     required: "Please Enter the Eamil",
                // },
                role_id: {
                    required: "Please Select Role Name",
                },
                // u_password: {
                //     required: "Please Enter the Password",
                // },
                // password_confirmation: {
                //     required: "Please Enter the Confirmation Password",
                // },
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
                // user_profile: {
                //     required: "Upload Media File",
                //     accept: "Only png, jpeg, and jpg image files are allowed.", // Update the error message for the accept rule
                // },
                pincode: {
                    required: "Please Enter the Pincode",
                },
            },

        });
    });
</script> --}}
    @endsection
