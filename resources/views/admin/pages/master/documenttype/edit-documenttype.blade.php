@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Edit Document Type
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-documenttype') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Update Document Type
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ route('update-documenttype') }}" method="post"
                                id="regForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="document_type_name">Document Type</label>&nbsp<span class="red-text">*</span>
                                            <input class="form-control mb-2" name="document_type_name" id="document_type_name"
                                                placeholder="Enter the Title"
                                                value="@if (old('document_type_name')) {{ old('document_type_name') }}@else{{ $documenttype_data->document_type_name }} @endif">
                                            @if ($errors->has('document_type_name'))
                                                <span class="red-text"><?php echo $errors->first('document_type_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="document_type_name">Document Color</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" id="colorPicker" name="doc_color"
                                            value="{{ old('doc_color', $documenttype_data->doc_color) }}">
                                            
                                            @if ($errors->has('doc_color'))
                                                <span class="red-text"><?php echo $errors->first('doc_color', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton">Save &amp;
                                            Update</button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-documenttype') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="id" class="form-control"
                                    value="{{ $documenttype_data->id }}" placeholder="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                function checkFormValidity() {
                    const documenttype = $('#documenttype').val();
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
                        documenttype: {
                            required: true,
                        },
                        // marathi_title: {
                        //     required: true,
                        // },
                    },
                    messages: {
                        documenttype: {
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
        <script>
$("#colorPicker").spectrum({
    // color: "#f00", // Default color (optional)
    showInput: true, // Show text input for hex color (optional)
    showAlpha: true, // Show alpha slider (optional)
    preferredFormat: "hex", // Format of the color value (optional)
});
</script>
    @endsection
