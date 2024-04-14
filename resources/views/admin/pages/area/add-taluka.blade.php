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
                    Area Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-taluka') }}">Area Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Add Taluka</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-taluka') }}" enctype="multipart/form-data">
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
                                            <label for="name">Taluka Name</label>&nbsp<span class="red-text">*</span>
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
                                        <span><a href="{{ route('list-taluka') }}"
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

    


        <!--  -->

        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const name = $('#name').val();
                    const district = $('#district').val();
                    // Enable the submit button if all fields are valid
                    if (name && district) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input,textarea, select, #user_profile').on('input change',
                    checkFormValidity);


                // Initialize the form validation
                $("#frm_register").validate({
                    rules: {
                        district: {
                            required: true,
                        },
                        name: {
                            required: true,
                        },

                    },
                    messages: {
                        district: {
                            required: "Please Select District",
                        },
                        name: {
                            required: "Please Enter the Taluka Name",
                        },
                    },

                });
            });

          
        </script>
    @endsection
