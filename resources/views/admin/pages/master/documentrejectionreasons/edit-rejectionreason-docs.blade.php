@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Edit Document Rejection Reasons
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-rejection-reason-docs') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Update Document Rejection Reasons
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ route('update-rejection-reason-docs') }}" method="post"
                                id="regForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="reason_name">Rejection Reason</label>&nbsp<span class="red-text">*</span>
                                            <input class="form-control mb-2" name="reason_name" id="reason_name"
                                                placeholder="Enter the Title"
                                                value="@if (old('reason_name')) {{ old('reason_name') }}@else{{ $rejectionreasons_data->reason_name }} @endif">
                                            @if ($errors->has('reason_name'))
                                                <span class="red-text"><?php echo $errors->first('reason_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton">Save &amp;
                                            Update</button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-rejection-reasons') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="id" class="form-control"
                                    value="{{ $rejectionreasons_data->id }}" placeholder="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                function checkFormValidity() {
                    const registrationstatus = $('#registrationstatus').val();
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
                        status_name: {
                            required: true,
                        },
                        // marathi_title: {
                        //     required: true,
                        // },
                    },
                    messages: {
                        status_name: {
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
