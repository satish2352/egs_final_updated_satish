@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-role', session('permissions'));
    ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Skill Wise Attendance Report
                   

                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-role') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Skill Wise Attendance Report</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                          <div class="row">

                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <select class="form-control" name="week_id" id="week_id">
                                        <option value="">Select Week</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} Week</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <select class="form-control" name="month_id" id="month_id">
                                    <option value="">Select Month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                    </select>
                                    @if ($errors->has('month_id'))
                                        <span class="red-text"><?php //echo $errors->first('month_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <div class="form-group">
                                    <select class="form-control" name="year_id" id="year_id">
                                        <option value="">Select Year</option>
                                        @for ($i = date('Y'); $i >= 2010; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('year_id'))
                                        <span class="red-text"><?php //echo $errors->first('year_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="skillorunskill_id" id="skillorunskill_id">
                                        <option value="">Select Skill</option>
                                        <option value="skill">Skill</option>
                                        <option value="unskill">Unskill</option>
                                    </select>
                                    @if ($errors->has('skillorunskill_id'))
                                        <span class="red-text"><?php echo $errors->first('skillorunskill_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <!-- <label for="m_name">Skill</label> -->
                                    <select class="form-control" name="skill_id" id="skill_id" disabled>

                                        <option value="">Select Skill</option>
                                       @foreach ($skills_data as $skills_for_data)    
                                        <option value="{{ $skills_for_data['id'] }}">{{ $skills_for_data['skill_name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('skill_id'))
                                        <span class="red-text"><?php echo $errors->first('skill_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                        @if(session()->get('role_id')=='1')
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
                        @elseif(session()->get('role_id')=='2')
                        

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
                        @elseif(session()->get('role_id')=='3')    
                       

                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="village_id" id="village_id">
                                    <option value="">Select Village</option>
                                    @foreach ($village_data as $village_for_data)    
                                        <option value="{{ $village_for_data['location_id'] }}">{{ $village_for_data['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('village_id'))
                                        <span class="red-text"><?php echo $errors->first('village_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                        @endif    
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-success" id="submitButton">
                                        Search
                                    </button>
                                </div>
                            </div>
                          </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="table-responsive">
                                    <table id="order-listing" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                <input type="hidden" class="form-control" name="is_approved_val" id="is_approved_val"
                                                placeholder="" value="">
                                                    <th>Sr. No.</th>
                                                    <th>Skill Name</th>
                                                    <th>Avarage Attendance</th>
                                                    <!-- <th>skill Name</th>
                                                    <th>Mobile Number</th>
                                                    <th>Mgnrega ID</th>
                                                    <th>Status</th> -->
                                                    <!-- <th>Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <!-- @foreach ($labours as $item) -->
                                                
                                                    <!-- <tr> -->
                                                    
                                                        <!-- <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}</td>
                                                        <td>{{ $item->full_name }}</td>
                                                        <td>{{ $item->mobile_number }}</td>
                                                        <td>{{ $item->mgnrega_card_id }}</td>
                                                        <td>
                                                        
                                                            @if ($item->is_approved=='1')
                                                                Received For Approval
                                                            @elseif($item->is_approved=='2')
                                                                Approved
                                                            @elseif($item->is_approved=='3')
                                                                Send For Correction
                                                            @endif
                                                        </td>
                                                        <td class="d-flex">
                                                       

                                                            <a data-id="{{ $item->id }}"
                                                                class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-eye"></i></a>
                                                           

                                                        </td> -->
                                                    <!-- </tr> -->
                                                <!-- @endforeach -->

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
            $(document).ready(function(){
                $('#skillorunskill_id').on('change', function() {
                    var selectedOption = $(this).val();
                    if(selectedOption === 'unskill') {
                        $('#skill_id').prop('disabled', true);
                    } else {
                        $('#skill_id').prop('disabled', false);
                    }
                });
            });
        </script>

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
        </script><script>
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

                    var monthId=$('#month_id').val();
                    var yearId=$('#year_id').val();
                    var weekNumber=$('#week_id').val();

                    if($('#skillorunskill_id').val()=='skill')
                    {
                    var SkillId = $('#skill_id').val();
                    }else if($('#skillorunskill_id').val()=='unskill')
                    {
                    var SkillId = '1';
                    }
                    var IsApprovedId = $('#is_approved_val').val();

                    if (monthId !== '' || yearId !== '' || SkillId !== '') {
                        $.ajax({
                            // url: '{{ route('export-location-report') }}',
                            url: '{{ route('list-labour-attendance-skill-wise-report') }}',
                            type: 'GET',
                            data: {
                                districtId: districtId,
                                talukaId: talukaId,
                                villageId: villageId,
                                SkillId: SkillId,
                                monthId: monthId,
                                yearId: yearId,
                                weekNumber: weekNumber
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response.labour_ajax_data);
                                console.log(response.labour_ajax_data.length);
                                if (response.labour_ajax_data.length > 0) {
                                    // alert('jjjjjjjjjjjjjjjjjjjj');
                                    // $('#order-listing tbody').empty();
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw();
                                    
                                    $.each(response.labour_ajax_data, function(index, labour_data) {
                                        index++;

                                        table.row.add([ index,
                                            labour_data.skill_name,
                                            labour_data.average_attendance_percentage+ ' %']).draw(false);
                                    });
                                }else{
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw();
                                    // $('#order-listing tbody').append('<tr><td colspan="7" style="text-align:center;"><b>No Record Found</b></td></tr>');
                                }

                            }
                        });
                    }
                });
            });
        </script>
       
    @endsection
