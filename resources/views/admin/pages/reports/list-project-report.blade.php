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
                        <div class="row">

                       
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" name="project_id" id="project_id">
                                        <option value="">Select Project</option>
                                        <?php  $cnt=1;?>
                                        @foreach ($projects_data as $project_for_data) 
                                        @if($cnt=='1')   
                                        <option value="{{ $project_for_data['id'] }}" selected>{{ $project_for_data['project_name'] }}</option>
                                        @else
                                        <option value="{{ $project_for_data['id'] }}">{{ $project_for_data['project_name'] }}</option>
                                        @endif
                                        <?php $cnt++; ?>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('project_id'))
                                        <span class="red-text"><?php echo $errors->first('project_id', ':message'); ?></span>
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

                            
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-success" id="submitButton">
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
                                                    <th>User Name</th>
                                                    <th>Project Name</th>
                                                    <th>Labour Name</th>
                                                    <th>Mobile Number</th>
                                                    <th>Mgnrega ID</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td> 
                                                      <td></td> 
                                                      <td></td> 
                                                      <td></td> 
                                                      <td></td> 
                                                      
                                                    </tr>

                                                

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

                $('#submitButton').click(function(e) {
                    e.preventDefault();
                    
                    var ProjectId = $('#project_id').val();

                    if($('#skillorunskill_id').val()=='skill')
                    {
                    var SkillId = $('#skill_id').val();
                    }else if($('#skillorunskill_id').val()=='unskill')
                    {
                    var SkillId = '1';
                    }
                    var IsApprovedId = $('#is_approved_val').val();
                    // console.log(talukaId);
                    // $('#village_id').html('<option value="">Select Village</option>');

                    

                    if (ProjectId !== '' || SkillId !== '') {
                        $.ajax({
                            url: '{{ route('list-project-wise-labour-reports') }}',
                            type: 'GET',
                            data: {
                                ProjectId: ProjectId,
                                SkillId: SkillId,
                                IsApprovedId: IsApprovedId,
                            },
                            success: function(response) {
                                if (response.labour_ajax_data.length > 0) {
                                    // $('#order-listing tbody').empty();
                                    var table = $('#order-listing').DataTable();
                                    table.clear().draw(); // Clear existing data

                                    
                                    $.each(response.labour_ajax_data, function(index, labour_data) {
                                        // console.log(index);
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
                                            labour_data.pro_name,
                                            labour_data.full_name,
                                            labour_data.mobile_number,
                                            labour_data.mgnrega_card_id,
                                            statusText,
                                            '<a onClick="getData(' + labour_data.id + ')" class="show-btn btn btn-sm btn-outline-primary m-1"><i class="fas fa-eye"></i></a>']).draw(false);
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
<script>

    // $(document).ready(() => {
        function getData(data){
        $("#show_id").val(data);
        $("#showform").submit();
    }
// });
</script>

<script>
            $(document).ready(function(){
                $('#skillorunskill_id').on('change', function() {
                    var selectedOption = $(this).val();
                    if(selectedOption === 'unskill') {
                        $('#skill_id').prop('disabled', true);
                        // $('#skill_id').val('1');
                    } else {
                        $('#skill_id').prop('disabled', false);
                        // $('#skill_id').val('');

                    }
                });
            });
        </script>
<form method="POST" action="{{ url('/show-labours') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
    @endsection
