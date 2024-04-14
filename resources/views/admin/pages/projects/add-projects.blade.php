@extends('admin.layout.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .text-center {
            text-align: center;
        }

        #map {
            width: 100%;
            height: 400px;
        }
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
</head>
<body>
<div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Projects Master
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Projects Master</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                action="{{ route('add-projects') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="project_name">Project Title</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="project_name" id="project_name"
                                                placeholder="" value="{{ old('project_name') }}">
                                            @if ($errors->has('project_name'))
                                                <span class="red-text"><?php echo $errors->first('project_name', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                   
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
                                            <label for="taluka">Taluka</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="taluka" id="taluka">
                                                <option value="">Select Taluka</option>
                                            </select>
                                            @if ($errors->has('taluka'))
                                                <span class="red-text"><?php echo $errors->first('taluka', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="village">Village</label>&nbsp<span class="red-text">*</span>
                                            <select class="form-control" name="village" id="village">
                                                <option value="">Select Village</option>
                                            </select>
                                            @if ($errors->has('village'))
                                                <span class="red-text"><?php echo $errors->first('village', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="latitude" id="latitude"
                                                placeholder="" value="{{ old('latitude') }}">
                                            @if ($errors->has('latitude'))
                                                <span class="red-text"><?php echo $errors->first('latitude', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control" name="longitude" id="longitude"
                                                placeholder="" value="{{ old('longitude') }}">
                                            @if ($errors->has('longitude'))
                                                <span class="red-text"><?php echo $errors->first('longitude', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="start_date">Start date</label>&nbsp<span class="red-text">*</span>
                                            <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="start_date" id="start_date"
                                                value="{{ old('start_date') }}">
                                            @if ($errors->has('start_date'))
                                                <span class="red-text"><?php echo $errors->first('start_date', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="end_date">End date</label>&nbsp<span class="red-text">*</span>
                                            <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="end_date" id="end_date"
                                                value="{{ old('end_date') }}">
                                            @if ($errors->has('end_date'))
                                                <span class="red-text"><?php echo $errors->first('end_date', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>&nbsp<span
                                                class="red-text">*</span>
                                            <textarea class="form-control description" name="description" id="description"
                                                placeholder="Enter the Description" name="description">{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="red-text"><?php echo $errors->first('description', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 user_tbl">
                                        <div id="data_for_role">
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
                                        <span><a href="{{ route('list-projects') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

    </div>
                </div>
<div id="map"></div>

            </div>

<script>
    let map, activeInfoWindow, markers = [];

    /* ----------------------------- Initialize Map ----------------------------- */
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: 19.984001081936558, lng: 73.7814192693961},
            zoom: 15
        });

        map.addListener("click", function (event) {
            mapClicked(event);
        });

        initMarkers();
    }

    /* --------------------------- Initialize Markers --------------------------- */
    function initMarkers() {
        const initialMarkers = <?php echo json_encode($initialMarkers); ?>;
// console.log('initialMarkers',initialMarkers);
        for (let index = 0; index < initialMarkers.length; index++) {
            const markerData = initialMarkers[index];
            const marker = new google.maps.Marker({
                position: markerData.position,
                label: {
            text: markerData.label.text,
            color: markerData.label.color,
            fontSize: markerData.label.fontSize || '20px', // Default font size if not provided
            fontFamily: markerData.label.fontFamily || 'Arial', // Default font family if not provided
            fontWeight: markerData.label.fontWeight || 'bold', // Default font weight if not provided
            fontStyle: markerData.label.fontStyle || 'normal' // Default font style if not provided
        },
                // label: markerData.label,
                draggable: markerData.draggable,
                map: map,
                icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 15, // Adjust size as needed
            fillColor: 'green', // Adjust color as needed
            fillOpacity: 10, // Adjust opacity as needed
            strokeWeight: 0// No border
        }

            });
            markers.push(marker);

            const infowindow = new google.maps.InfoWindow({
                content: `<b>${markerData.position.lat}, ${markerData.position.lng}</b>`
            });
            marker.addListener("click", (event) => {
                if (activeInfoWindow) {
                    activeInfoWindow.close();
                }

                infowindow.open({
                    anchor: marker,
                    shouldFocus: false,
                    map: map
                });
                activeInfoWindow = infowindow;
                markerClicked(marker, index);
            });

            marker.addListener("dragend", (event) => {
                markerDragEnd(event, index);
            });
        }
    }

    /* ------------------------- Handle Map Click Event ------------------------- */
    function mapClicked(event) {
        console.log(map);
        console.log(event.latLng.lat(), event.latLng.lng());
    }

    /* ------------------------ Handle Marker Click Event ----------------------- */
    function markerClicked(marker, index) {
        console.log(map);
        console.log(marker.position.lat());
        console.log(marker.position.lng());
    }

    /* ----------------------- Handle Marker DragEnd Event ---------------------- */
    function markerDragEnd(event, index) {
        console.log(map);
        console.log(event.latLng.lat());
        console.log(event.latLng.lng());
    }
</script>

<!-- Load the Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIEHb7JkyL1mwS8R24pSdVO4p2Yi_8v98&callback=initMap" async defer></script>
</body>
<script type="text/javascript">
            function submitRegister() {
                document.getElementById("frm_register").submit();
            }
        </script>
        <script>
            $(document).ready(function() {

                $('#district').change(function(e) {
                    e.preventDefault();
                    var districtId = $('#district').val();
                    console.log(districtId);
                    $('#taluka').html('<option value="">Select Taluka</option>');

                    if (districtId !== '') {
                        $.ajax({
                            url: '{{ route('taluka') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.taluka.length > 0) {
                                    $.each(response.taluka, function(index, taluka) {
                                        $('#taluka').append('<option value="' + taluka
                                            .location_id +
                                            '">' + taluka.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>

<script>
            $(document).ready(function() {

                $('#taluka').change(function(e) {
                    e.preventDefault();
                    var talukaId = $('#taluka').val();
                    console.log(talukaId);
                    $('#village').html('<option value="">Select Village</option>');

                    if (talukaId !== '') {
                        $.ajax({
                            url: '{{ route('village') }}',
                            type: 'GET',
                            data: {
                                talukaId: talukaId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                // console.log(response);
                                if (response.village.length > 0) {
                                    $.each(response.village, function(index, village) {
                                        $('#village').append('<option value="' + village
                                            .location_id +
                                            '">' + village.name + '</option>');
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const project_name = $('#project_name').val();
                    const description = $('#description').val();
                    const latitude = $('#latitude').val();
                    const longitude = $('#longitude').val();
                    const district = $('#district').val();
                    const taluka = $('#taluka').val();
                    const village = $('#village').val();

                    // console.log('project_name',project_name);
                    // console.log('description',description);
                    // console.log('latitude',latitude);
                    // console.log('longitude',longitude);
                    // console.log('state',project_name);
                    // console.log('district',district);
                    // console.log('taluka',project_name);
                    // console.log('village',village);

                    // Enable the submit button if all fields are valid
                    if (project_name && description && latitude && longitude && district && taluka &&
                    village) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input,textarea, select').on('input change',
                    checkFormValidity);

                //     $.validator.addMethod("number", function(value, element) {
                //     return this.optional(element) || /^[0-9]{10}$/.test(value);
                // }, "Please enter a valid 10-digit number.");

                // $.validator.addMethod("email", function(value, element) {
                //     // Regular expression for email validation
                //     const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                //     return this.optional(element) || emailRegex.test(value);
                // }, "Please enter a valid email address.");

                // Initialize the form validation
                $("#regForm").validate({
                    rules: {
                        project_name: {
                            required: true,
                        },
                        district: {
                            required: true,
                        },
                        taluka: {
                            required: true,
                        },
                        village: {
                            required: true,
                        },
                        latitude: {
                            required: true,
                        },
                        longitude: {
                            required: true,
                        },
                        start_date: {
                            required: true,
                        },
                        end_date: {
                            required: true,
                        },
                        description: {
                            required: true,
                        },

                    },
                    messages: {
                        project_name: {
                            required: "Please Enter the Eamil",
                            // remote: "This Email already exists."
                        },
                        district: {
                            required: "Please Enter the Password",
                        },
                        taluka: {
                            required: "Please Enter the Confirmation Password",
                        },
                        village: {
                            required: "Please Enter the First Name",
                        },
                        latitude: {
                            required: "Please Enter the Middle Name",
                        },
                        longitude: {
                            required: "Please Enter the Last Name",
                        },
                        start_date: {
                            required: "Please Enter the Number",
                        },
                        end_date: {
                            required: "Please Enter the Designation",
                        },
                        description: {
                            required: "Please Enter the Address",
                        },
                    },

                });
            });
        </script>
    @endsection

