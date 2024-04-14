@extends('admin.layout.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Projects Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-projects') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Projects Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="frm_register" method="post" role="form"
                                action="{{ route('update-projects') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="project_name">Project Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="project_name" id="project_name"
                                                placeholder="" value="{{ $project_data['data_projects']['project_name'] }}">
                                            @if ($errors->has('project_name'))
                                                <span class="red-text"><?php echo $errors->first('project_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div> 

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="district">District</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="district" id="district">
                                                        <option value="">Select District</option>
                                                        @foreach ($dynamic_district as $district)
                                                        <option value="{{ $district['location_id'] }}"
                                                        @if ($district['location_id'] == $project_data['data_projects']['district']) <?php echo 'selected'; ?> @endif>
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
                                            <label for="latitude">Latitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="latitude" id="latitude"
                                                placeholder="" value="{{ $project_data['data_projects']['latitude'] }}">
                                            @if ($errors->has('latitude'))
                                                <span class="red-text"><?php echo $errors->first('latitude', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div> 
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="longitude" id="longitude"
                                                placeholder="" value="{{ $project_data['data_projects']['longitude'] }}">
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
                                                value="{{ $project_data['data_projects']['start_date'] }}">
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
                                                value="{{ $project_data['data_projects']['end_date'] }}">
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
                                                placeholder="Enter the Description" name="description">{{ $project_data['data_projects']['description'] }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="red-text"><?php echo $errors->first('description', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    @if ($project_data['data_projects']['is_active']) <?php echo 'checked'; ?> @endif>
                                                Is Active
                                                <i class="input-helper"></i><i class="input-helper"></i></label>
                                        </div>
                                    </div>

                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                        <div id="data_for_role">
                                        </div>
                                    </div> -->

                                    <div class="col-md-12 col-sm-12 text-center mt-3">
                                        <input type="hidden" class="form-check-input" name="edit_id" id="edit_id"
                                            value="{{ $project_data['data_projects']['id'] }}">
                                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                                Save &amp; Update
                                            </button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-projects') }}"
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
          

function getDistrictTaluka(districtId, taluka_id) {

$('#taluka').html('<option value="">Select District</option>');
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
                
                // getStateDistrict('{{ $project_data['data_projects']['state'] }}', '{{ $project_data['data_projects']['district'] }}');
                getDistrictTaluka('{{ $project_data['data_projects']['district'] }}', '{{ $project_data['data_projects']['taluka'] }}');
                getTalukaVillage('{{ $project_data['data_projects']['taluka'] }}', '{{ $project_data['data_projects']['village'] }}');
                getState('{{ $project_data['data_projects']['state'] }}');

                $("#state").on('change', function() {
                    getStateDistrict($("#state").val(),'');
                });

                $("#district").on('change', function() {
                    getDistrictTaluka($("#district").val(),'');
                });

                $("#taluka").on('change', function() {
                    getTalukaVillage($("#taluka").val(),'');
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
        var validator = form.validate({
            rules: {
                // email: {
                //     required: true,
                // },
                role_id: {
                    required: true,
                },
                // password: {
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
                // email: {
                //     required: "Please Enter the Eamil",
                // },
                role_id: {
                    required: "Please Select Role Name",
                },
                // password: {
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
            submitHandler: function(form) {
                form.submit();
            }
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
            // const email = $('#email').val();
            const role_id = $('#role_id').val();
            // const password = $('#password').val();
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
                // email: {
                //     required: true,
                // },
                role_id: {
                    required: true,
                },
                // password: {
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
                // email: {
                //     required: "Please Enter the Eamil",
                // },
                role_id: {
                    required: "Please Select Role Name",
                },
                // password: {
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
