@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Edit Marital Status
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-maritalstatus') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Update Marital Status
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ route('update-maritalstatus') }}" method="post"
                                id="regForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="maritalstatus">Marital Status</label>&nbsp<span class="red-text">*</span>
                                            <input class="form-control mb-2" name="maritalstatus" id="maritalstatus"
                                                placeholder="Enter the Title"
                                                value="@if (old('maritalstatus')) {{ old('maritalstatus') }}@else{{ $maritalstatus_data->maritalstatus }} @endif">
                                            @if ($errors->has('maritalstatus'))
                                                <span class="red-text"><?php echo $errors->first('maritalstatus', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton">Save &amp;
                                            Update</button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-maritalstatus') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="id" class="form-control"
                                    value="{{ $maritalstatus_data->id }}" placeholder="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                function checkFormValidity() {
                    const marital_status = $('#maritalstatus').val();
                    // const marathi_title = $('#marathi_title').val();
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
                        marital_status: {
                            required: true,
                        },
                        // marathi_title: {
                        //     required: true,
                        // },
                    },
                    messages: {
                        marital_status: {
                            required: "Please Enter the Title",
                        },
                        // marathi_title: {
                        //     required: "कृपया शीर्षक प्रविष्ट करा",
                        // },
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
