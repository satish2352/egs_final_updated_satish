@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Add User Type
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-usertype') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> User Type</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ url('add-usertype') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="usertype_name">User Type</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="usertype_name"
                                                id="usertype_name" value="{{ old('usertype_name') }}"
                                                placeholder="Enter the Title">
                                            @if ($errors->has('usertype_name'))
                                                <span class="red-text"><?php echo $errors->first('usertype_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="marathi_title">लिंग</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="marathi_title"
                                                id="marathi_title" value="{{ old('marathi_title') }}"
                                                placeholder="शीर्षक प्रविष्ट करा">
                                            @if ($errors->has('marathi_title'))
                                                <span class="red-text"><?php //echo $errors->first('marathi_title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div> -->
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-usertype') }}"
                                                class="btn btn-sm btn-primary">Back</a></span>
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
                    const usertype_name = $('#usertype_name').val();
                    //const marathi_title = $('#marathi_title').val();

                    // Enable the submit button if all fields are valid
                    // if (usertype_name && marathi_title) 
                    if (usertype_name) {
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
                        usertype_name: {
                            required: true,
                        },
                        marathi_title: {
                            required: true,
                        },
                    },
                    messages: {
                        usertype_name: {
                            required: "Please Enter the Title",
                        },
                        marathi_title: {
                            required: "कृपया शीर्षक प्रविष्ट करा",
                        },
                    },
                });
            });
        </script>
    @endsection
