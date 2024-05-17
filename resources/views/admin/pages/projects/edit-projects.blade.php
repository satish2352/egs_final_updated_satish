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
                
                getDistrictTaluka('{{ $project_data['data_projects']['district'] }}', '{{ $project_data['data_projects']['taluka'] }}');
                getTalukaVillage('{{ $project_data['data_projects']['taluka'] }}', '{{ $project_data['data_projects']['village'] }}');

               

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
            const project_name = $('#project_name').val();
            const description = $('#description').val();
            const latitude = $('#latitude').val();
            const longitude = $('#longitude').val();
            const district = $('#district').val();
            const taluka = $('#taluka').val();
            const village = $('#village').val();
            
        }

        // Initialize the form validation
      

        $("#regForm").validate({
                    rules: {
                        project_name: {
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
                        latitude: {
                            required: true,
                            latitude: true,
                        },
                        longitude: {
                            required: true,
                            longitude: true,
                        },
                        start_date: {
                            required: true,
                        },
                        end_date: {
                            required: true,
                        },
                        description: {
                            required: true,
                        },

                    },
                    messages: {
                        project_name: {
                            required: "Please Enter the Eamil",
                            // remote: "This Email already exists."
                        },
                        district: {
                            required: "Please Enter the Password",
                        },
                        taluka: {
                            required: "Please Enter the Confirmation Password",
                        },
                        village: {
                            required: "Please Enter the First Name",
                        },
                        latitude: {
                            required: "Please Enter the Middle Name",
                        },
                        longitude: {
                            required: "Please Enter the Last Name",
                        },
                        start_date: {
                            required: "Please Enter the Number",
                        },
                        end_date: {
                            required: "Please Enter the Designation",
                        },
                        description: {
                            required: "Please Enter the Address",
                        },
                    },

                });

    });
</script>    



{{-- <script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const project_name = $('#project_name').val();
            const description = $('#description').val();
            const latitude = $('#latitude').val();
            const longitude = $('#longitude').val();
            const district = $('#district').val();
            const taluka = $('#taluka').val();
            const village = $('#village').val();

            // Enable the submit button if all fields are valid
            f (project_name && description && latitude && longitude && district && taluka &&
                    village) {
                $('#submitButton').prop('disabled', false);
            } else {
                $('#submitButton').prop('disabled', true);
            }
        }

        // Call the checkFormValidity function on input change
        $('input,textarea, select, #user_profile').on('input change',
            checkFormValidity);
    });
</script> --}}
    @endsection
