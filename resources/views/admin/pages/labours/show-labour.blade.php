@extends('admin.layout.master')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- CSS -->
<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* background-color: rgb(0, 0, 0); */
        /* background-color: rgba(0, 0, 0, 0.9); */
        background-color: rgb(0 0 0 / 25%);
    }

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    .close {
        position: absolute;
        /* top: 15px;
        right: 35px; */
        top: 18%;
        right: 50px;
        /* color: #f1f1f1; */
        color: #0d0c0c;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        /* color: #bbb; */
        color: #0d0c0c;
        text-decoration: none;
        cursor: pointer;
    }

    .download_btn{
        display: flex;
        justify-content: right;
        top: 20px;
    }
</style>
    <div class="main-panel">
        <div class="content-wrapper mt-7">

            <div class="row justify-content-center">
                <div class="col-10 grid-margin ">

                    <div class="row">
                        <div class="col-lg-11 col-md-11 col-sm-11 d-flex justify-content-start align-items-center">
                            <h3 class="page-title">
                                Labour Details
                            </h3>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 d-flex justify-content-end align-items-center">
                            <div>
                                <a href="#" onclick="history.back()" class="btn btn-sm btn-primary ml-3">Back</a>
                            </div>
                        </div>

                    </div>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>labour Name :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ $labour_detail['data_users_data']['full_name'] }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Gender:</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ $labour_detail['data_users_data']['gender_name'] }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mobile Number :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['mobile_number']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mgnrega ID :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['mgnrega_card_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Skills :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['skill_name']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>District :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['district_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Taluka :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['taluka_id']) }}</label>
                                        </div>
                                    </div>


                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Village :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['village_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Latitude :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['latitude']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Longitude :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users_data']['longitude']) }}</label>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Profile Image :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['profile_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['profile_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Aadhaar Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['aadhar_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['aadhar_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Voter Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['voter_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['voter_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mgnrega Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['mgnrega_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users_data']['mgnrega_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                        <!-- Modal for Image Preview -->
                                        <div id="imageModal" class="modal">
                                            <span class="close">&times;</span>
                                            <img class="modal-content" id="modalImage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    @include('admin.layout.alert')
                                                    <div class="table-responsive">
                                                    <h5 class="d-flex justify-content-center mb-4">Family Details</h5>
                                                        <table id="order-listing" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr. No.</th>
                                                                    <th>Full Name</th>
                                                                    <th>Date of Birth</th>
                                                                    <th>Gender</th>
                                                                    <th>Relationship</th>
                                                                    <th>Marital Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <?php //dd($labour_detail['data_family_users']); ?> -->
                                                            @foreach ($labour_detail['data_family_users'] as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item['full_name'] }}</td>
                                                                    <td>{{ $item['date_of_birth'] }}</td>
                                                                    <td>{{ $item['gender_name'] }}</td>
                                                                    <td>{{ $item['relation_title'] }}</td>
                                                                    <td>{{ $item['maritalstatus'] }}</td>
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

                            @if($labour_detail['data_users_data']['is_approved']=='1' && $labour_detail['data_users_data']['is_resubmitted']=='1')    
                            <div class="row mt-4">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    @include('admin.layout.alert')
                                                    <div class="table-responsive">
                                                    <h5 class="d-flex justify-content-center mb-4">Rejection History Details</h5>
                                                        <table id="order-listing" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr. No.</th>
                                                                    <th>Registration Status</th>
                                                                    <th>Not Approved Reason</th>
                                                                    <th>Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach ($labour_detail['data_verification_history'] as $history_item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $history_item['status_name'] }}</td>
                                                                    <td>{{ $history_item['reason_name'] }}</td>
                                                                    <td>
                                                                        @if($history_item['other_remark']!='null')
                                                                            {{ $history_item['other_remark'] }}
                                                                        @else
                                                                            NA
                                                                        @endif    
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
                        @elseif($labour_detail['data_users_data']['is_approved']=='3' && $labour_detail['data_users_data']['is_resubmitted']=='0')    
                            <div class="row mt-4">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    @include('admin.layout.alert')
                                                    <div class="table-responsive">
                                                    <h5 class="d-flex justify-content-center mb-4">Rejection History Details</h5>
                                                        <table id="order-listing" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr. No.</th>
                                                                    <th>Registration Status</th>
                                                                    <th>Not Approved Reason</th>
                                                                    <th>Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <?php //dd($labour_detail['data_family_users']); ?> -->
                                                            @foreach ($labour_detail['data_verification_history'] as $history_item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $history_item['status_name'] }}</td>
                                                                    <td>{{ $history_item['reason_name'] }}</td>
                                                                    <td>
                                                                        @if($history_item['other_remark']!='null')
                                                                            {{ $history_item['other_remark'] }}
                                                                        @else
                                                                            NA
                                                                        @endif    
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
                        @endif


                        @if(($labour_detail['data_users_data']['is_approved']=='3' && session()->get('role_id')=='2' && $labour_detail['data_users_data']['is_resubmitted']=='1'))
                        <div class="col-lg-12 col-md-12 col-sm-12 mt-4" style="border: 1px solid #040479;padding: 2%;">
                                        <h5 class="d-flex justify-content-center mb-4">Labour Verification</h5>
                                        <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                        action="{{ route('update-labour-status') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $labour_detail['data_users_data']['id'] }}" />
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="is_approved">Registration Status</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="is_approved" id="is_approved">
                                                        <option value="">Select Status</option>

                                                        @foreach ($dynamic_registrationstatus as $registration_status_data)
                                                    <option value="{{ $registration_status_data['id'] }}">
                                                        {{ $registration_status_data['status_name'] }}</option>
                                                @endforeach    
                                                    </select>
                                                    @if ($errors->has('is_approved'))
                                                        <span class="red-text"><?php echo $errors->first('is_approved', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3" id="reason_div">
                                                <div class="form-group">
                                                    <label for="reason_id">Not Approve Reasons</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="reason_id" id="reason_id">
                                                        <option value="">Select Reason</option>
                                                       
                                                        <!-- <option value="1001">Others</option> -->
                                                            @foreach ($dynamic_reasons as $dynamic_reasons_data)
                                                            <option value="{{ $dynamic_reasons_data['id'] }}">{{ $dynamic_reasons_data['reason_name'] }}</option>
                                                            @endforeach  
                                                             

                                                    </select>
                                                    @if ($errors->has('reason_id'))
                                                        <span class="red-text"><?php echo $errors->first('reason_id', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="remark_div">
                                                <div class="form-group">
                                                    <label for="other_remark">Remark</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <textarea class="form-control other_remark" name="other_remark" id="other_remark"
                                                        placeholder="Enter the other_remark" name="other_remark"></textarea>
                                                    @if ($errors->has('other_remark'))
                                                        <span class="red-text"><?php echo $errors->first('other_remark', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        <span><a href="#" onclick="history.back()"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                        </div>
                                    </form>
                                </div>



                        @elseif(($labour_detail['data_users_data']['is_approved']=='1' && session()->get('role_id')=='2'))    
                            <div class="col-lg-12 col-md-12 col-sm-12 mt-4" style="border: 1px solid #040479;padding: 2%;">
                                        <h5 class="d-flex justify-content-center mb-4">Labour Verification</h5>
                                        <form class="forms-sample" id="frm_register" name="frm_register" method="post" role="form"
                                        action="{{ route('update-labour-status') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $labour_detail['data_users_data']['id'] }}" />
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="is_approved">Registration Status</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="is_approved" id="is_approved">
                                                        <option value="">Select Status</option>

                                                        @foreach ($dynamic_registrationstatus as $registration_status_data)
                                                    <option value="{{ $registration_status_data['id'] }}"
                                                        @if ($registration_status_data['id'] == $labour_detail['data_users_data']['is_approved']) <?php echo 'selected'; ?> @endif>
                                                        {{ $registration_status_data['status_name'] }}</option>
                                                @endforeach    
                                                    </select>
                                                    @if ($errors->has('is_approved'))
                                                        <span class="red-text"><?php echo $errors->first('is_approved', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3" id="reason_div">
                                                <div class="form-group">
                                                    <label for="reason_id">Not Approve Reasons</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="reason_id" id="reason_id">
                                                        <option value="">Select Reason</option>
                                                        @if($labour_detail['data_users_data']['reason_id']=='1001')
                                                        <option value="1001" <?php echo 'selected'; ?>>Others</option>
                                                        @else
                                                        <option value="1001">Others</option>
                                                        @endif
                                                            @foreach ($dynamic_reasons as $dynamic_reasons_data)
                                                            <option value="{{ $dynamic_reasons_data['id'] }}"
                                                            @if ($dynamic_reasons_data['id'] == $labour_detail['data_users_data']['reason_id']) <?php echo 'selected'; ?> @endif>
                                                            {{ $dynamic_reasons_data['reason_name'] }}</option>
                                                           
                                                            @endforeach  
                                                             

                                                    </select>
                                                    @if ($errors->has('reason_id'))
                                                        <span class="red-text"><?php echo $errors->first('reason_id', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="remark_div">
                                                <div class="form-group">
                                                    <label for="other_remark">Remark</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <textarea class="form-control other_remark" name="other_remark" id="other_remark"
                                                        placeholder="Enter the other_remark" name="other_remark"></textarea>
                                                    @if ($errors->has('other_remark'))
                                                        <span class="red-text"><?php echo $errors->first('other_remark', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        <span><a href="#" onclick="history.back()"
                                                class="btn btn-sm btn-primary ">Back</a></span>
                                    </div>
                                        </div>
                                    </form>
                                </div>
                        @elseif($labour_detail['data_users_data']['is_approved']=='3' && $labour_detail['data_users_data']['is_resubmitted']=='0' && session()->get('role_id')=='1' || session()->get('role_id')=='2'
                                    || session()->get('role_id')=='3')    
                                    <div class="col-lg-12 col-md-12 col-sm-12 mt-4" style="border: 1px solid #040479;padding: 2%;">
                                        <h5 class="d-flex justify-content-center mb-4">Labour Verification</h5>
                                       
                                        <div class="row">


                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="is_approved">Registration Status</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="is_approved" id="is_approved" disabled>
                                                        <option value="">Select Status</option>

                                                        @foreach ($dynamic_registrationstatus as $registration_status_data)
                                                    <option value="{{ $registration_status_data['id'] }}"
                                                        @if ($registration_status_data['id'] == $labour_detail['data_users_data']['is_approved']) <?php echo 'selected'; ?> @endif>
                                                        {{ $registration_status_data['status_name'] }}</option>
                                                @endforeach    
                                                    </select>
                                                    @if ($errors->has('is_approved'))
                                                        <span class="red-text"><?php echo $errors->first('is_approved', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>

                                        @if($labour_detail['data_users_data']['is_approved']=='3')
                                            <div class="col-lg-3 col-md-3 col-sm-3" id="reason_div">
                                                <div class="form-group">
                                                    <label for="reason_id">Not Approve Reasons</label>&nbsp<span class="red-text">*</span>
                                                    <select class="form-control" name="reason_id" id="reason_id" disabled>
                                                        <option value="">Select Reason</option>
                                                        @if($labour_detail['data_users_data']['reason_id']=='1001')
                                                        <option value="1001" <?php echo 'selected'; ?>>Others</option>
                                                        @else
                                                        <option value="1001">Others</option>
                                                        @endif
                                                            @foreach ($dynamic_reasons as $dynamic_reasons_data)
                                                            <option value="{{ $dynamic_reasons_data['id'] }}"
                                                            @if ($dynamic_reasons_data['id'] == $labour_detail['data_users_data']['reason_id']) <?php echo 'selected'; ?> @endif>
                                                            {{ $dynamic_reasons_data['reason_name'] }}</option>
                                                           
                                                            @endforeach  
                                                    </select>
                                                    @if ($errors->has('reason_id'))
                                                        <span class="red-text"><?php echo $errors->first('reason_id', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        @if($labour_detail['data_users_data']['reason_id']=='1001')
                                            <div class="col-lg-6 col-md-6 col-sm-6" id="remark_div">
                                                <div class="form-group">
                                                    <label for="other_remark">Remark</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <textarea class="form-control other_remark" name="other_remark" id="other_remark"
                                                        placeholder="Enter the other_remark" name="other_remark" disabled>{{ $labour_detail['data_users_data']['other_remark'] }}</textarea>
                                                    @if ($errors->has('other_remark'))
                                                        <span class="red-text"><?php echo $errors->first('other_remark', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif   
                                        </div>
                                    </div>
                                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- JavaScript -->
<script>
    // When the user clicks on an image, open the modal
    $(document).on("click", ".preview-image", function() {
        $("#modalImage").attr("src", $(this).attr("src"));
        $("#imageModal").css("display", "block");
    });

    // When the user clicks on the close button or outside the modal, close it
    $(document).on("click", ".close, .modal", function() {
        $("#imageModal").css("display", "none");
    });

    $("#is_approved").on('change', function() {
        var status_val=$(this).val();
        if(status_val == '2')
        {
            $('#reason_div').hide();
            $('#remark_div').hide();
        }else
        {
            $('#reason_div').show();
            $('#remark_div').show();
        }
    });

    $("#reason_id").on('change', function() {
        var reason_val=$(this).val();
        // alert(reason_val);
        if(reason_val != '1001')
        {
            $('#remark_div').hide();
        }else
        {
            $('#remark_div').show();
        }
    });
</script>

<script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const is_approved = $('#is_approved').val();
                    // const description = $('#description').val();
                    // const latitude = $('#latitude').val();
                    // const longitude = $('#longitude').val();
                    // const state = $('#state').val();
                    // const district = $('#district').val();
                    // const taluka = $('#taluka').val();
                    // const village = $('#village').val();

                    // Enable the submit button if all fields are valid
                    // if (project_name && description && latitude && longitude && state && district && taluka &&
                    // village) {
                        if (is_approved) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                $('input,textarea, select').on('input change',
                    checkFormValidity)
            });

            $("#submitButton").click(function() {
            // Validate the form
            // if (form.valid()) {
                form.submit();
            // }
        });
    </script>
    @endsection



