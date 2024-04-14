@extends('admin.layout.master')
@section('content')
    <?php
    // $restricted_options = ['add_5', 'delete_5', 'add_6', 'delete_6', 'add_11', 'delete_11', 'add_17', 'delete_17', 'add_18', 'delete_18', 'add_19', 'delete_19', 'add_20', 'delete_20', 'add_21', 'delete_21', 'add_22', 'delete_22', 'add_23', 'delete_23', 'add_24', 'delete_24', 'add_25', 'delete_25', 'add_26', 'delete_26', 'add_27', 'delete_27', 'add_29', 'delete_29', 'add_30', 'delete_30', 'add_31', 'delete_31', 'add_32', 'update_32', 'add_33', 'update_33', 'add_34', 'update_34', 'add_46', 'update_46', 'add_51', 'delete_51', 'add_53', 'delete_53', 'add_54', 'delete_54'];
    ?>

    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Users Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-role') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Users Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="roleformid" method="post" role="form"
                                action="{{ route('update-role') }}" enctype="multipart/form-data">

                                @csrf
                                <input type="hidden" name="edit_id" id="edit_id" value="{{ $user_data['roles']['id'] }}">
                                <div class="row">

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="role_name">Role Type</label>&nbsp<span class="red-text">*</span><br>
                                            <input type="text" id="role_name" name="role_name" class="role_name form-control mb-1"
                                                value="@if (old('role_name')) {{ old('role_name') }} @else {{ $user_data['roles']['role_name'] }} @endif"><br>
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
                                                    @foreach ($user_data['permissions'] as $key => $permission)
                                                        <?php
                                                        $permissions_id = '';
                                                        $per_list = false;
                                                        $per_add = false;
                                                        $per_update = false;
                                                        $per_delete = false;
                                                        $data_all = $user_data['permissions_user'];
                                                        foreach ($data_all as $key_new => $permissions_data) {
                                                            if ($permissions_data['permissions_id'] == $permission['id']) {
                                                                $permissions_id = $permissions_data['permissions_id'];
                                                                if ($permissions_data['per_list']) {
                                                                    $per_list = true;
                                                                }
                                                                if ($permissions_data['per_add']) {
                                                                    $per_add = true;
                                                                }
                                                                if ($permissions_data['per_update']) {
                                                                    $per_update = true;
                                                                }
                                                                if ($permissions_data['per_delete']) {
                                                                    $per_delete = true;
                                                                }
                                                            }
                                                        }
                                                        ?>

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
                                                                    <?php //$add_name = 'per_add_' . $permission['id'];
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="per_list_{{ $permission['id'] }}"
                                                                        id="per_list_{{ $permission['id'] }}"
                                                                        value="list_{{ $permission['id'] }}"
                                                                        data-parsley-multiple="per_list"
                                                                        @if ($per_list) {{ 'checked' }} @endif>

                                                                    <i class="input-helper"></i><i
                                                                        class="input-helper"></i></label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check-label">
                                                                    <?php //$add_name = 'per_add_' . $permission['id'];
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="per_add_{{ $permission['id'] }}"
                                                                        id="per_add_{{ $permission['id'] }}"
                                                                        value="add_{{ $permission['id'] }}"
                                                                        data-parsley-multiple="per_add"
                                                                        @if ($per_add) {{ 'checked' }} @endif>

                                                                    <i class="input-helper"></i><i
                                                                        class="input-helper"></i></label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check-label">
                                                                    <?php //$per_update = 'per_update_' . $permission['id'];
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="per_update_{{ $permission['id'] }}"
                                                                        id="per_update_{{ $permission['id'] }}"
                                                                        value="update_{{ $permission['id'] }}"
                                                                        data-parsley-multiple="per_update"
                                                                        @if ($per_update) {{ 'checked' }} @endif>

                                                                    <i class="input-helper"></i><i
                                                                        class="input-helper"></i></label>
                                                            </td>
                                                            <td>
                                                                <label class="form-check-label">
                                                                    <?php // $per_delete = 'per_delete_' . $permission['id'];
                                                                    ?>
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="per_delete_{{ $permission['id'] }}"
                                                                        id="per_delete_{{ $permission['id'] }}"
                                                                        value="delete_{{ $permission['id'] }}"
                                                                        data-parsley-multiple="per_delete"
                                                                        @if ($per_delete) {{ 'checked' }} @endif>

                                                                    <i class="input-helper"></i><i
                                                                        class="input-helper"></i></label>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-6 mt-3">
                                        <div class="form-group form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    @if (old('role_name')) {{ old('role_name') }} @else 
                                                    @if ($user_data['roles']['is_active']) {{ 'checked' }} @endif
                                                    @endif
                                                >
                                                Is Active
                                                <i class="input-helper"></i><i class="input-helper"></i></label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                            Save &amp; Update
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
                    validationMessage.textContent = "Only numbers are allowed.";
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
                    validationMessage.textContent = "Only numbers are allowed.";
                }
            }
        </script>
        <script>
            $(document).ready(function() {
                function checkFormValidity() {
                    const role_name = $('#role_name').val();
                }
                // Call the checkFormValidity function on file input change
                $('input').on('change', function() {
                    checkFormValidity();
                    validator.element(this); // Revalidate the file input
                });
                // Initialize the form validation
                var form = $("#regForm");
                var validator = form.validate({
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
    @endsection
