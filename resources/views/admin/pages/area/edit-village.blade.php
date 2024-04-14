@extends('admin.layout.master')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Village Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-village') }}">Area</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Village Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="regForm" name="frm_register" method="post" role="form"
                                action="{{ route('update-village') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <div class="row">
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="district">User District</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="district" id="district">
                                                        <option value="">Select District</option>
                                                        @foreach ($dynamic_district as $district)
                                                        <option value="{{ $district['location_id'] }}"
                                                        @if ($district['location_id'] == $village_data['district_id']) <?php echo 'selected'; ?> @endif>
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
                                            <label for="name">Village Name</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="name" id="name"
                                                placeholder="" value="{{ $village_data['name'] }}"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s.]/g, '').replace(/(\..*)\./g, '$1');">
                                            @if ($errors->has('name'))
                                                <span class="red-text"><?php echo $errors->first('name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    

                                    <br>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group form-check form-check-flat form-check-primary">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="is_active"
                                                    id="is_active" value="y" data-parsley-multiple="is_active"
                                                    @if ($village_data['is_active']) <?php echo 'checked'; ?> @endif>
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
                                            value="{{ $village_data['location_id'] }}">
                                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                                Save &amp; Update
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

        </script>

        <script type="text/javascript">
            function submitRegister() {
                document.getElementById("frm_register").submit();
            }
        </script>

        <script>
            $(document).ready(function() {
                getDistrictTaluka('{{ $village_data['district_id'] }}', '{{ $village_data['parent_id'] }}');


                $("#district").on('change', function() {
                    getDistrictTaluka($("#district").val(),'');
                });
            });

        </script>

  <script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const district = $('#district').val();
            const taluka = $('#taluka').val();
            const name = $('#name').val();
            
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
                        district: {
                            required: true,
                        },
                        taluka: {
                            required: true,
                        },
                        name: {
                            required: true,
                        },
                        

                    },
                    messages: {
                        district: {
                            required: "Please Select District Name",
                        },
                        taluka: {
                            required: "Please Select Taluka",
                        },
                        name: {
                            required: "Please Enter the Village Name",
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



<script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const district = $('#district').val();
            const taluka = $('#taluka').val();
            const name = $('#name').val();

            // Enable the submit button if all fields are valid
            if (district && taluka && name) {
                $('#submitButton').prop('disabled', false);
            } else {
                $('#submitButton').prop('disabled', true);
            }
        }

        // Call the checkFormValidity function on input change
        $('input,textarea, select, #user_profile').on('input change',
            checkFormValidity);

    });
</script>
    @endsection
