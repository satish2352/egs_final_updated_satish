@extends('admin.layout.master')

@section('content')
    <?php
    // $restricted_options = ['add_5', 'delete_5', 'add_6', 'delete_6', 'add_11', 'delete_11', 'add_17', 'delete_17', 'add_18', 'delete_18', 'add_19', 'delete_19', 'add_20', 'delete_20', 'add_21', 'delete_21', 'add_22', 'delete_22', 'add_23', 'delete_23', 'add_24', 'delete_24', 'add_25', 'delete_25', 'add_26', 'delete_26', 'add_27', 'delete_27', 'add_29', 'delete_29', 'add_30', 'delete_30', 'add_31', 'delete_31', 'add_32', 'update_32', 'add_33', 'update_33', 'add_34', 'update_34', 'add_46', 'update_46', 'add_51', 'delete_51', 'add_53', 'delete_53', 'add_54', 'delete_54'];
    ?>

    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Role
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-role') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="roleformid" method="post" role="form"
                                action="{{ route('add-role') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="role_name">Role Name</label>&nbsp<span class="red-text">*</span>
                                                <input type="text" class="form-control role_name mb-2" name="role_name"
                                                    id="role_name" value="{{ old('role_name') }}"
                                                    placeholder="Enter the Role Name">
                                                @if ($errors->has('role_name'))
                                                    <span class="red-text"><?php echo $errors->first('role_name', ':message'); ?></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr. No.</th>
                                                            <th>Functionality Name</th>
                                                            <th>List</th>
                                                            <th>Add</th>
                                                            <th>Update</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permissions as $key => $permission)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    <input type="hidden" class="form-check-input"
                                                                        name="permission_id_{{ $permission['id'] }}"
                                                                        id="permission_id_{{ $permission['id'] }}"
                                                                        value="{{ $permission['id'] }}"
                                                                        data-parsley-multiple="permission_id">
                                                                    {{ $permission['permission_name'] }}
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label">
                                                                        <?php $per_list = 'per_list_' . $permission['id']; ?>
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="per_list_{{ $permission['id'] }}"
                                                                            id="per_list_{{ $permission['id'] }}"
                                                                            value="list_{{ $permission['id'] }}"
                                                                            data-parsley-multiple="per_list"
                                                                            {{ old($per_list) ? 'checked' : '' }}>

                                                                        <i class="input-helper"></i><i
                                                                            class="input-helper"></i></label>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label">
                                                                        <?php $add_name = 'per_add_' . $permission['id']; ?>
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="per_add_{{ $permission['id'] }}"
                                                                            id="per_add_{{ $permission['id'] }}"
                                                                            value="add_{{ $permission['id'] }}"
                                                                            data-parsley-multiple="per_add"
                                                                            {{ old($add_name) ? 'checked' : '' }}>

                                                                        <i class="input-helper"></i><i
                                                                            class="input-helper"></i></label>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label">
                                                                        <?php $per_update = 'per_update_' . $permission['id']; ?>
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="per_update_{{ $permission['id'] }}"
                                                                            id="per_update_{{ $permission['id'] }}"
                                                                            value="update_{{ $permission['id'] }}"
                                                                            data-parsley-multiple="per_update"
                                                                            {{ old($per_update) ? 'checked' : '' }}>

                                                                        <i class="input-helper"></i><i
                                                                            class="input-helper"></i></label>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label">
                                                                        <?php $per_delete = 'per_delete_' . $permission['id']; ?>
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="per_delete_{{ $permission['id'] }}"
                                                                            id="per_delete_{{ $permission['id'] }}"
                                                                            value="delete_{{ $permission['id'] }}"
                                                                            data-parsley-multiple="per_delete"
                                                                            {{ old($per_delete) ? 'checked' : '' }}>

                                                                        <i class="input-helper"></i><i
                                                                            class="input-helper"></i></label>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 text-center pt-3">
                                            <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                                Save &amp; Submit
                                            </button>
                                            {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                            <span><a href="{{ route('list-role') }}"
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
            $("#regForm").validate({
                rules: {
                  role_name: {
                        required: true,
                        charactersOnly: true // Use custom validation method
                    },
                  
                  },
                messages: {
                    role_name: {
                        required: "Title name is required",
                        charactersOnly: "Only characters are allowed." // Custom error message for custom validation method
                    },
                   
                   
                }
            });
        });
    </script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Focus on the input box with id "inputBox" when the page loads
    document.getElementById("role_name").focus();
});
</script>

        <script>
            $(document).ready(function() {
                myFunction($("#role_id").val());
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
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const role_name = $('#role_name').val();
                    // Enable the submit button if all fields are valid
                    if (role_name) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input').on('input change', checkFormValidity);

                // Initialize the form validation
                $("#regForm").validate({
                    rules: {
                        role_name: {
                            required: true,
                        },
                    },
                    messages: {
                        role_name: {
                            required: "Please Enter the Role Name",
                        },
                    },
                });
            });
        </script>
    @endsection
