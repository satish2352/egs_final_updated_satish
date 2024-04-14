@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-users', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Not Approved Docuemnt List
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Master Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Gramsevaks </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">

                <div class="row">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="table-responsive">
                                        <table id="order-listing" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Document Type</th>
                                                    <th>Document Name</th>
                                                    <th>Registration Status</th>
                                                    <th>Not Approved Reason</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data_gram_doc_details as $item)
                                                <tr>
                                                    <input type="hidden" name="edit_id" id="edit_id" value="{{ $item->id }}" />

                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->document_type_name }}</td>
                                                    <td><a href="{{ Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') }}{{ $item->document_pdf }}" download target="_blank">
                                                            {{ $item->document_pdf }}
                                                        </a>
                                                    </td>
                                                    <td>Not Approved</td>
                                                    <td>NA</td>
                                                    <td>NA</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {

                $('#district_id').change(function(e) {
                    e.preventDefault();
                    var districtId = $('#district_id').val();

                    if (districtId !== '') {
                        $.ajax({
                            url: '{{ route('taluka') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId
                            },
                            success: function(response) {
                                if (response.taluka.length > 0) {
                                    $('#taluka_id').empty();
                                    $('#village_id').empty();
                                    $('#taluka_id').html('<option value="">Select Taluka</option>');
                                    $('#village_id').html('<option value="">Select Village</option>');
                                    $.each(response.taluka, function(index, taluka) {
                                        $('#taluka_id').append('<option value="' + taluka
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

                $('#taluka_id').change(function(e) {
                    e.preventDefault();
                    var talukaId = $('#taluka_id').val();

                    if (talukaId !== '') {
                        $.ajax({
                            url: '{{ route('village') }}',
                            type: 'GET',
                            data: {
                                talukaId: talukaId
                            },
                            success: function(response) {
                                if (response.village.length > 0) {
                                    $('#village_id').empty();
                                    $('#village_id').html('<option value="">Select Village</option>');
                                    $.each(response.village, function(index, village) {
                                        $('#village_id').append('<option value="' + village
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

                $('#submitButton').click(function(e) {
                    e.preventDefault();
                    var districtId = $('#district_id').val()
                    if(districtId==undefined){
                        districtId="";
                    }
                    var talukaId = $('#taluka_id').val();
                    var villageId = $('#village_id').val();
                    var FromDate = $('#from_date').val();
                    var ToDate = $('#to_date').val();
                    var ProjectId = $('#project_id').val();

                    // var IsApprovedId = $('#is_approved_val').val();
                    // console.log(talukaId);
                    // $('#village_id').html('<option value="">Select Village</option>');

                    if (districtId !== '' || talukaId !== '' || villageId !== '' ||
                        ProjectId !== '' || (FromDate !== '' && ToDate !== '')) {
                        $.ajax({
                            url: '{{ route('list-labours-attendance-filter') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId,
                                talukaId: talukaId,
                                villageId: villageId,
                                FromDate: FromDate,
                                ToDate: ToDate,
                                ProjectId: ProjectId,
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                if (response.labour_attendance_ajax_data.length > 0) {
                                    $('#order-listing tbody').empty();
                                    
                                    $.each(response.labour_attendance_ajax_data, function(index, labour_attendance_data) {
                                        console.log(labour_attendance_data.created_at);
                                        var lid=index + parseInt(1);
                                        $('#order-listing tbody').append('<tr><td>' + lid +'</td><td>' + labour_attendance_data.project_name + '</td><td>' + labour_attendance_data.full_name +'</td><td>' + labour_attendance_data.mobile_number + '</td><td>' + labour_attendance_data.mgnrega_card_id + '</td><td>' + labour_attendance_data.attendance_day+ '</td><td>' + labour_attendance_data.created_at + '</td></tr>');
                                    });
                                }else{
                                    $('#order-listing tbody').empty();
                                    $('#order-listing tbody').append('<tr><td colspan="7" style="text-align:center;"><b>No Record Found</b></td></tr>');

                                    // alert("No Record Found");
                                }

                            }
                        });
                    }
                });
            });
        </script>
     
        <form method="POST" action="{{ url('/show-gramsevak-doc-new') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
        <!-- content-wrapper ends -->
    @endsection
