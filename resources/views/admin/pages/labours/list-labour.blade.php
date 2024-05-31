@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-users', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    {{ $labour_data_type }}
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-labours') }}">Labours Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Labours </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                        <!-- <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                        action="{{ route('list-labours') }}" enctype="multipart/form-data"> -->
                        @if(session()->get('role_id')=='1')
                        <div class="row">

                       
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="district_id" id="district_id">
                                        <option value="">Select District</option>
                                        @foreach ($district_data as $district_for_data)    
                                        <option value="{{ $district_for_data['location_id'] }}">{{ $district_for_data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_id'))
                                        <span class="red-text"><?php echo $errors->first('district_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="taluka_id" id="taluka_id">
                                        <option value="">Select Taluka</option>
                                    </select>
                                    @if ($errors->has('taluka_id'))
                                        <span class="red-text"><?php echo $errors->first('taluka_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                           
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="village_id" id="village_id">
                                    <option value="">Select Village</option>
                                    </select>
                                    @if ($errors->has('village_id'))
                                        <span class="red-text"><?php echo $errors->first('village_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                            Search
                                        </button>
                                        </div>
                            </div>
                        </div>
                          @elseif(session()->get('role_id')=='2')
                        <div class="row">

                        
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="taluka_id" id="taluka_id">
                                        <option value="">Select Taluka</option>
                                        @foreach ($taluka_data as $taluka_for_data)    
                                        <option value="{{ $taluka_for_data['location_id'] }}">{{ $taluka_for_data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('taluka_id'))
                                        <span class="red-text"><?php echo $errors->first('taluka_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="village_id" id="village_id">
                                    <option value="">Select Village</option>
                                    </select>
                                    @if ($errors->has('village_id'))
                                        <span class="red-text"><?php echo $errors->first('village_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                            Search
                                        </button>
                                        </div>
                            </div>
                        </div>
                          @endif  


                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="table-responsive">
                                        <table id="order-listing" class="table table-bordered">
                                            <thead>
                                            <input type="hidden" class="form-control" name="is_approved_val" id="is_approved_val"
                                                placeholder="" value="{{ $labour_type }}">
                                                <input type="hidden" class="form-control" name="is_resubmitted_val" id="is_resubmitted_val"
                                                placeholder="" value="{{ $resubmitted_type }}">
                                                <tr>
                                                
                                                    <th>Sr. No.</th>
                                                    <th>User Name</th>
                                                    <th>Labour Name</th>
                                                    <th>Mobile Number</th>
                                                    <th>Mgnrega ID</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @foreach ($labours as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}</td>
                                                        <td>{{ $item->full_name }}</td>
                                                        <td>{{ $item->mobile_number }}</td>
                                                        <td>{{ $item->mgnrega_card_id }}</td>
                                                        <td>
                                                            @if (session()->get('role_id')=='1')
                                                                @if ($item->is_approved=='1' && $item->is_resubmitted=='0')
                                                                    Sent For approval To The Officer
                                                                @elseif ($item->is_approved=='2')
                                                                    Approved By Officer
                                                                @elseif ($item->is_approved=='3' && $item->is_resubmitted=='0')
                                                                    Disapproved By Officer    
                                                                @elseif ($item->is_approved=='1' && $item->is_resubmitted=='1')
                                                                    Resend To Officer For Verification    
                                                                @endif
                                                            @elseif (session()->get('role_id')=='2')
                                                                @if ($item->is_approved=='1' && $item->is_resubmitted=='0')
                                                                    Received For approval
                                                                @elseif ($item->is_approved=='2')
                                                                    Approved
                                                                @elseif ($item->is_approved=='3' && $item->is_resubmitted=='0')
                                                                    Disapproved
                                                                @elseif ($item->is_approved=='1' && $item->is_resubmitted=='1')
                                                                    Received For Reverification       
                                                                @endif
                                                            @elseif (session()->get('role_id')=='3')
                                                                @if ($item->is_approved=='1' && $item->is_resubmitted=='0')
                                                                    Sent For approval
                                                                @elseif ($item->is_approved=='2')
                                                                    Approved
                                                                @elseif ($item->is_approved=='3' && $item->is_resubmitted=='0')
                                                                    Received For Correction
                                                                @elseif ($item->is_approved=='1' && $item->is_resubmitted=='1')
                                                                    Sent For Reverification               
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="d-flex">
                                                       

                                                            <a data-id="{{ $item->id }}"
                                                                class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-eye"></i></a>
                                                           

                                                        </td>
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
                    var IsApprovedId = $('#is_approved_val').val();
                    var IsResubmittedId = $('#is_resubmitted_val').val();
                    // console.log(talukaId);
                    // $('#village_id').html('<option value="">Select Village</option>');

                    if (districtId !== '' || talukaId !== '' || villageId !== '') {
                        $.ajax({
                            url: '{{ route('list-labours-filter') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId,
                                talukaId: talukaId,
                                villageId: villageId,
                                IsApprovedId: IsApprovedId,
                                IsResubmittedId:IsResubmittedId
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response.labour_ajax_data);
                                if (response.labour_ajax_data.length > 0) {
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw(); 
                                    
                                    $.each(response.labour_ajax_data, function(index, labour_data) {
                                        index++;
                                        var statusText = "";
                                        if (labour_data.is_approved == '1') {
                                            statusText = "Received For Approval";
                                        } else if (labour_data.is_approved == '2') {
                                            statusText = "Approved";
                                        } else if (labour_data.is_approved == '3') {
                                            statusText = "Send For Correction";
                                        }

                                        table.row.add([ index,
                                            labour_data.f_name + ' ' + labour_data.m_name + ' ' + labour_data.l_name,
                                            labour_data.full_name,
                                            labour_data.mobile_number,
                                            labour_data.mgnrega_card_id,
                                            statusText,
                                            '<a onClick="getData(' + labour_data.id + ')" class="show-btn btn btn-sm btn-outline-primary m-1"><i class="fas fa-eye"></i></a>']).draw(false);
                                    });
                                }else{
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw();
                                    // $('#order-listing tbody').append('<tr><td colspan="7" style="text-align:center;"><b>No Record Found</b></td></tr>');

                                    // alert("No Record Found");
                                }

                            }
                        });
                    }
                });
            });
        </script>
<script>

    // $(document).ready(() => {
        function getData(data){
        $("#show_id").val(data);
        $("#showform").submit();
    }
// });
</script>
<form method="POST" action="{{ url('/show-labours') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
    @endsection
