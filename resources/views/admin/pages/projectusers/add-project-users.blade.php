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
                        <li class="breadcrumb-item"><a href="{{ route('list-project-wise-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Projects Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-project-wise-users') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="project_id">Project</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="project_id" name="project_id">
                                                <option value="" >Select Project</option>
                                                @foreach ($dynamic_projects as $projects)
                                                    @if (old('project_id') == $projects['id'])
                                                        <option value="{{ $projects['id'] }}" attr-dist="{{ $projects['District'] }}"
                                                        attr-tal="{{ $projects['taluka'] }}" attr-vil="{{ $projects['village'] }}" selected>
                                                            {{ $projects['project_name'] }}</option>
                                                    @else
                                                        <option value="{{ $projects['id'] }}" attr-dist="{{ $projects['District'] }}"
                                                        attr-tal="{{ $projects['taluka'] }}" attr-vil="{{ $projects['village'] }}">{{ $projects['project_name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('project_id'))
                                                <span class="red-text"><?php echo $errors->first('project_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="user_type_id">User Type</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="user_type_id" id="user_type_id">
                                                <option value="">Select User Type</option>
                                                @foreach ($dynamic_user_types as $user_type)
                                                    @if (old('user_type_id') == $user_type['id'])
                                                        <option value="{{ $user_type['id'] }}" selected>
                                                            {{ $user_type['usertype_name'] }}</option>
                                                    @else
                                                        <option value="{{ $user_type['id'] }}">
                                                            {{ $user_type['usertype_name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_type_id'))
                                                <span class="red-text"><?php echo $errors->first('user_type_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="user_id">User Name</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="user_id" id="user_id">
                                                <!-- <option value="">Select User</option> -->
                                            </select>
                                            @if ($errors->has('user_id'))
                                                <span class="red-text"><?php echo $errors->first('user_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                   
                                    
                                   
                                   
                                    
                                    
                                    <br>
                                    

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
            $(document).ready(function() {

                $('#user_type_id').change(function(e) {
                    e.preventDefault();
                    var usertypeId = $('#user_type_id').val();
                    var pro_id = $('#project_id').val();
                    var dist_val = $('#project_id').find(':selected').attr('attr-dist');
                    var tal_val = $('#project_id').find(':selected').attr('attr-tal');
                    var vil_val = $('#project_id').find(':selected').attr('attr-vil');
                    // console.log(stateId);
                    $('#user_id').html('<option value="">Select User</option>');

                    if (usertypeId !== '') {
                        $.ajax({
                            url: '{{ route('usertype-users') }}',
                            type: 'GET',
                            data: {
                                pro_id: pro_id,
                                usertypeId: usertypeId,
                                dist_val: dist_val,
                                tal_val: tal_val,
                                vil_val: vil_val
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.usertype_userdata.length > 0) {
                                    $.each(response.usertype_userdata, function(index, usertype_userdata) {
                                        $('#user_id').append('<option value="' + usertype_userdata
                                            .id +
                                            '">' + usertype_userdata.f_name + '</option>');
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
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const project_id = $('#project_id').val();
                    const user_type_id = $('#user_type_id').val();
                    const user_id = $('#user_id').val();

                    // Enable the submit button if all fields are valid
                    if (project_id && user_type_id && user_id) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input,textarea, select').on('input change',
                    checkFormValidity);
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
