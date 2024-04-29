@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Add Document Type
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-documenttype') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Document Type</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ url('add-documenttype') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="document_type_name">Document Type</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="document_type_name"
                                                id="document_type_name" value="{{ old('document_type_name') }}"
                                                placeholder="Enter the Title">
                                            @if ($errors->has('document_type_name'))
                                                <span class="red-text"><?php echo $errors->first('document_type_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="doc_color">Colour</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" id="colorPicker" name="doc_color">
                                            @if ($errors->has('doc_color'))
                                                <span class="red-text"><?php echo $errors->first('doc_color', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-documenttype') }}"
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
                    const document_type_name = $('#document_type_name').val();
                    //const marathi_title = $('#marathi_title').val();

                    // Enable the submit button if all fields are valid
                    // if (document_type_name && marathi_title) 
                    if (document_type_name) {
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
                        document_type_name: {
                            required: true,
                        },
                    },
                    messages: {
                        document_type_name: {
                            required: "Please Enter the Title",
                        },
                    },
                });
            });
        </script>

<script>
$("#colorPicker").spectrum({
    color: "#f00", // Default color (optional)
    showInput: true, // Show text input for hex color (optional)
    showAlpha: true, // Show alpha slider (optional)
    preferredFormat: "hex", // Format of the color value (optional)
});
</script>

    @endsection
