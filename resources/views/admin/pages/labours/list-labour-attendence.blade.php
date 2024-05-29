@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-users', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <!-- <h3 class="page-title">
                    Labours Master List <a href="{{ route('add-projects') }}" class="btn btn-sm btn-primary ml-3">+ Add</a>
                </h3> -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-labours') }}">Labours Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Labour Attendance </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="from_date">From Date</label>&nbsp<span
                                        class="red-text">*</span>
                                    <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                        name="from_date" id="from_date" value="">
                                    @if ($errors->has('from_date'))
                                        <span class="red-text"><?php echo $errors->first('from_date', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="to_date">To Date</label>&nbsp<span
                                        class="red-text">*</span>
                                    <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                        name="to_date" id="to_date" value="">
                                    @if ($errors->has('to_date'))
                                        <span class="red-text"><?php echo $errors->first('to_date', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                <label for="to_date">Project</label>&nbsp<span
                                        class="red-text">*</span>
                                    <select class="form-control" name="project_id" id="project_id">
                                        <option value="">Select Project</option>
                                        @foreach ($dynamic_projects as $projects_for_data)    
                                        <option value="{{ $projects_for_data['id'] }}">{{ $projects_for_data['project_name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('project_id'))
                                        <span class="red-text"><?php echo $errors->first('project_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                        </div>

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
                           
                        </div>
                          @endif  
                          <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                            Search
                                        </button>
                                        </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="table-responsive">
                                        <table id="order-listing" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Project Name</th>
                                                    <th>Labour Name</th>
                                                    <th>Mobile Number</th>
                                                    <th>Mgnrega ID</th>
                                                    <th>Attendance Type</th>
                                                    <th>Attendance Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($labours as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->project_name }}</td>
                                                        <td>{{ $item->full_name }}</td>
                                                        <td>{{ $item->mobile_number }}</td>
                                                        <td>{{ $item->mgnrega_card_id }}</td>
                                                        <td>
                                                            @if($item->attendance_day=="half_day")
                                                                Half Day
                                                            @else
                                                                Full Day
                                                            @endif
                                                        {{ $item->attendance_day }}</td>
                                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                        
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
                                    var newattendanceday= '';
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw();
                                    
                                    $.each(response.labour_attendance_ajax_data, function(index, labour_attendance_data) {
                                    index++;

                                    if(labour_attendance_data.attendance_day == "half_day")
                                    {
                                        newattendanceday='Half Day';
                                    }else if(labour_attendance_data.attendance_day == "full_day")
                                    {
                                        newattendanceday='Full Day';
                                    }

                                    table.row.add([ index,
                                            labour_attendance_data.project_name,
                                            labour_attendance_data.full_name,
                                            labour_attendance_data.mobile_number,
                                            labour_attendance_data.mgnrega_card_id,
                                            newattendanceday,
                                            labour_attendance_data.created_at]).draw(false);
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

        <!-- content-wrapper ends -->
    @endsection
