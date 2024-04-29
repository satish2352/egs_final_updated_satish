@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-role', session('permissions'));
    ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Labour Duration Report
                   

                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-role') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Labour Duration Report</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                          <div class="row">

                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="month_id" id="month_id">
                                    <option value="">Select Month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                    </select>
                                    @if ($errors->has('skill_id'))
                                        <span class="red-text"><?php //echo $errors->first('skill_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="year_id" id="year_id">
                                        <option value="">Select Year</option>
                                        @for ($i = date('Y'); $i >= 2010; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('skill_id'))
                                        <span class="red-text"><?php //echo $errors->first('skill_id', ':message'); ?></span>
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
                                    @if ($errors->has('skill_id'))
                                        <span class="red-text"><?php echo $errors->first('skill_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <!-- <label for="m_name">Skill</label> -->
                                    <select class="form-control" name="skill_id" id="skill_id" disabled>
                                        <option value="">Select Skill</option>
                                        <option value="Carpentry">Carpentry</option>
                                        <option value="Plumbing">Plumbing</option>
                                        <option value="Electrician">Electrician</option>
                                        <option value="Masonry">Masonry</option>
                                        <option value="Painting">Painting</option>
                                        <option value="Welding">Welding</option>
                                    </select>
                                    @if ($errors->has('skill_id'))
                                        <span class="red-text"><?php echo $errors->first('skill_id', ':message'); ?></span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-success" id="submitButton">
                                        Search1
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
alert('jjjjjjjjjjjjjj');
                $('#submitButton').click(function(e) {
                    e.preventDefault();
                    // var districtId = $('#district_id').val()
                    // if(districtId==undefined){
                    //     districtId="";
                    // }
                    // var talukaId = $('#taluka_id').val();
                    // var villageId = $('#village_id').val();

                    var monthId=$('#month_id').val();
                    var yearId=$('#year_id').val();

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
                            url: '{{ route('list-labour-duration-filter-report') }}',
                            type: 'GET',
                            data: {
                                // districtId: districtId,
                                // talukaId: talukaId,
                                // villageId: villageId,
                                SkillId: SkillId,
                                monthId: monthId,
                                yearId: yearId,
                            },
                            // headers: {
                            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            // },
                            success: function(response) {
                                console.log(response.labour_ajax_data);
                                if (response.labour_ajax_data.length > 0) {
                                    // $('#order-listing tbody').empty();
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
                                    $('#order-listing tbody').empty();
                                    $('#order-listing tbody').append('<tr><td colspan="7" style="text-align:center;"><b>No Record Found</b></td></tr>');
                                }

                            }
                        });
                    }
                });
            });
        </script>
       
    @endsection
