@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-7">

            <div class="row justify-content-center">
                <div class="col-12 grid-margin ">

                    <div class="row">
                        <div class="col-lg-11 col-md-11 col-sm-11 d-flex justify-content-start align-items-center">
                            <h3 class="page-title">
                                Approved Gramsevak Document Details
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
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <label>Name :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ $data_gram_doc_details['user_data']['f_name'] }} {{ $data_gram_doc_details['user_data']['m_name'] }}
                                                {{ $data_gram_doc_details['user_data']['l_name'] }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <label>District :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($data_gram_doc_details['user_data']['district']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <label>Taluka :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($data_gram_doc_details['user_data']['taluka']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-1 col-md-1 col-sm-1">
                                            <label>Village :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($data_gram_doc_details['user_data']['village']) }}</label>
                                        </div>
                                    </div>
                                    <input type="hidden" class="tok" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                    <div class="row mt-4">
                                        

                                        <!-- <div class="row mt-4"> -->
                                            <div class="col-12 grid-margin">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                @include('admin.layout.alert')
                                                                <div class="table-responsive">
                                                                <h5 class="d-flex justify-content-center mb-4">Document Verification Details</h5>
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
                                                                        @foreach ($data_gram_doc_details['user_doc_data'] as $item)
                                                                        
                                                                        @if((session()->get('role_id')=='2'))

                                                                            <tr>
                                                                                <input type="hidden" name="edit_id" id="edit_id" value="{{ $item->id }}" />
                                                                                <input type="hidden" name="show_id" id="show_id" value="{{ $data_gram_doc_details['user_data']['id'] }}" />

                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $item->document_type_name }}</td>
                                                                                <td><a href="{{ Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') }}{{ $item->document_pdf }}" download target="_blank">
                                                                                        {{ $item->document_pdf }}
                                                                                    </a>
                                                                                </td>
                                                                                <td>Approved</td>
                                                                                <td>NA</td>
                                                                            </tr>
                                                                        @elseif((session()->get('role_id')=='3' || session()->get('role_id')=='1'))

                                                                            <tr>
                                                                                <input type="hidden" name="edit_id" id="edit_id" value="{{ $item->id }}" />

                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $item->document_type_name }}</td>
                                                                                <td><a href="{{ Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') }}{{ $item->document_pdf }}" download target="_blank">
                                                                                        {{ $item->document_pdf }}
                                                                                    </a>
                                                                                </td>
                                                                                <td><select class="form-control is_approved" name="is_approved" id="is_approved" disabled>
                                                                                        <option value="">Select Status</option>
                                                                                        @foreach ($dynamic_registrationstatus as $registration_status_data)
                                                                                        <option value="{{ $registration_status_data['id'] }}"
                                                                                        @if ($registration_status_data['id'] == $item->is_approved) <?php echo 'selected'; ?> @endif>
                                                                                            {{ $registration_status_data['status_name'] }}</option>
                                                                                        @endforeach    
                                                                                    </select>
                                                                                </td>
                                                                                <td><select class="form-control" name="reason_doc_id" id="reason_doc_id" disabled>
                                                                                    <option value="">Select Reason</option>

                                                                                    @if($item->reason_doc_id=='1001')
                                                                                        <option value="1001" <?php echo 'selected'; ?>>Others</option>
                                                                                        @else
                                                                                        <option value="1001">Others</option>
                                                                                        @endif
                                                                                    <!-- <option value="1001">Others</option> -->
                                                                                        @foreach ($dynamic_reasons as $dynamic_reasons_data)
                                                                                        <option value="{{ $dynamic_reasons_data['id'] }}"
                                                                                        @if ($dynamic_reasons_data['id'] == $item->reason_doc_id) <?php echo 'selected'; ?> @endif>{{ $dynamic_reasons_data['reason_name'] }}</option>
                                                                                        @endforeach  
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                @if($item->reason_doc_id=='1001')
                                                                                     <textarea class="form-control other_remark" name="other_remark" id="other_remark"
                                                                                    placeholder="Enter the Remark" name="other_remark" disabled>{{$item->other_remark}}</textarea>
                                                                                @else
                                                                                NA    
                                                                                @endif    
                                                                                </td>
                                                                                <!-- <td>NA</td> -->
                                                                            </tr>
                                                                            @endif 
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    <script>
        $(document).ready(function() {
            $(document).on("change", ".is_approved", function () {
                var IsApprovedVal=$(this).val();
                var closestTr = $(this).closest("tr");
                var ReasonVal=closestTr.find('select[name="reason_doc_id"]').val();

                var ReasonSelectBox = closestTr.find('select[name="reason_doc_id"]');
                var Remarktextarea = closestTr.find('textarea[name="other_remark"]');
                
                if ($(this).val() != '3') {
                    ReasonSelectBox.prop('disabled', true);
                    if(ReasonVal!='1001')
                    {    
                    Remarktextarea.prop('disabled', true);
                    }else{
                    Remarktextarea.prop('disabled', false);
                    }
                } else {
                    ReasonSelectBox.prop('disabled', false);
                    if(ReasonVal!='1001')
                    {    
                    Remarktextarea.prop('disabled', true);
                    }else{
                    Remarktextarea.prop('disabled', false);
                    }
                }
            });
        });
    </script>

<script>
        $(document).ready(function() {
            $(document).on("change", ".reason_doc_id", function () {
                var ReasonVal=$(this).val();
                var closestTr = $(this).closest("tr");

                // var ReasonSelectBox = closestTr.find('select[name="reason_doc_id"]');
                var Remarktextarea = closestTr.find('textarea[name="other_remark"]');
                
                if ($(this).val() != '1001') {
                    // ReasonSelectBox.prop('disabled', true);
                    Remarktextarea.prop('disabled', true);
                } else {
                    // ReasonSelectBox.prop('disabled', false);
                    Remarktextarea.prop('disabled', false);
                }
            });
        });
    </script>

<script>
            $(document).ready(function() {

                // $('#submitButton').click(function(e) {
                    $(document).on('click','.submitButton', function (e) {
                    e.preventDefault();

                    var closestTr = $(this).closest("tr");
                    var is_approved= closestTr.find('select[name="is_approved"]').val();
                    var reason_doc_id = closestTr.find('select[name="reason_doc_id"]').val();
                    var other_remark = closestTr.find('textarea[name="other_remark"]').val();
                    var token_val = $('.tok').val();
                    var show_id = $('#show_id').val();
                    var edit_id = closestTr.find('#edit_id').val();

                    if (is_approved !== '') {
                        $.ajax({
                            url: '{{ route('update-gram-document-status') }}',
                            type: 'GET',
                            data: {
                                is_approved: is_approved,
                                reason_doc_id: reason_doc_id,
                                other_remark: other_remark,
                                edit_id: edit_id,
                                show_id: show_id,
                            },
                            success: function(response) {
                                if(response==true)
                                {
                                    location.reload();
                                }else{
                                    alert('No Record Updated');
                                }
                              

                            }
                        });
                    }
                });
            });
        </script>

    @endsection

