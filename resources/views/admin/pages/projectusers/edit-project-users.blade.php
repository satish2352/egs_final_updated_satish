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
                        <li class="breadcrumb-item"><a href="{{ route('list-project-wise-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Projects Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="frm_register" method="post" role="form"
                                action="{{ route('update-project-wise-users') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="project_id">Projects</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="project_id" name="project_id"
                                                onchange="myFunction(this.value)">
                                                <option>Select</option>
                                                @foreach ($project_user_data['project'] as $project_d)
                                                    <option value="{{ $project_d['id'] }}"
                                                        @if ($project_d['id'] == $project_user_data['data_project_users']['project_id']) <?php echo 'selected'; ?> @endif>
                                                        {{ $project_d['project_name'] }}</option>
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
                                            <select class="form-control" id="user_type_id" name="user_type_id">
                                                <option>Select</option>
                                                @foreach ($project_user_data['user_type'] as $user_type_d)
                                                    <option value="{{ $user_type_d['id'] }}"
                                                        @if ($user_type_d['id'] == $project_user_data['data_project_users']['user_type_id']) <?php echo 'selected'; ?> @endif>
                                                        {{ $user_type_d['usertype_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_type_id'))
                                                <span class="red-text"><?php echo $errors->first('user_type_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="user_id">Users</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" id="user_id" name="user_id"
                                                onchange="myUserTypeFunction(this.value)">
                                                <option>Select</option>
                                                @foreach ($project_user_data['user_data'] as $user_d)
                                                    <option value="{{ $user_d['id'] }}"
                                                        @if ($user_d['id'] == $project_user_data['data_project_users']['user_id']) <?php echo 'selected'; ?> @endif>
                                                        {{ $user_d['f_name'] }} {{ $user_d['m_name'] }} {{ $user_d['l_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_id'))
                                                <span class="red-text"><?php echo $errors->first('user_id', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                               
                                    <br>
                               
                                    <div class="col-md-12 col-sm-12 text-center mt-3">
                                        <input type="hidden" class="form-check-input" name="edit_id" id="edit_id"
                                            value="{{ $project_user_data['data_project_users']['id'] }}">
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
        $('input,textarea, select, #user_profile').on('input change',
            checkFormValidity);






        // Initialize the form validation
        $("#regForm").validate({
            rules: {
                project_id: {
                    required: true,
                },
                user_type_id: {
                    required: true,
                },
                user_id: {
                    required: true,
                }
            },
            messages: {
                project_id: {
                    required: "Please Select Project",
                },
                user_type_id: {
                    required: "Please Select User Type",
                },
                user_id: {
                    required: "Please Select User",
                }
            },

        });
    });
</script>

<script>
           
            function myFunction(project_id) {
                alert(project_id);
            const user_type_id = $('#user_type_id').val();

                // $("#project_id").empty();
                $.ajax({
                    url: "{{ route('list-project-wise-user-edit') }}",
                    method: "GET",
                    data: {
                        "project_id": project_id,
                        "user_type_id":user_type_id
                    },
                    success: function(response) {
                            if (response.user_data_new.length > 0) {
                $("#user_id").empty();

                                $.each(response.user_data_new, function(index, user_data_new) {
                                    $('#user_id').append('<option value="' + user_data_new
                                        .id +
                                        '" selected>' + user_data_new.f_name + user_data_new.m_name + user_data_new.l_name + '</option>');
                                });
                                // $('#state').val(stateId);
                            }
                        }
                });
            }


            function myUserTypeFunction(user_type_id) {
                alert('adadsa');
            const project_id = $('#project_id').val();

                // $("#project_id").empty();
                $.ajax({
                    url: "{{ route('list-user-type-wise-user-edit') }}",
                    method: "GET",
                    data: {
                        "project_id": project_id,
                        "user_type_id":user_type_id
                    },
                    success: function(response) {
                            if (response.user_data_new.length > 0) {
                $("#user_id").empty();

                                $.each(response.user_data_new, function(index, user_data_new) {
                                    $('#user_id').append('<option value="' + user_data_new
                                        .id +
                                        '" selected>' + user_data_new.f_name + user_data_new.m_name + user_data_new.l_name + '</option>');
                                });
                                // $('#state').val(stateId);
                            }
                        }
                });
            }
        </script>
    @endsection
