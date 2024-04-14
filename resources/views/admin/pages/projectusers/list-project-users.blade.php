@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-users', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Project Labour Master List <a href="{{ route('add-project-wise-users') }}" class="btn btn-sm btn-primary ml-3">+ Add</a>
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-project-wise-users') }}">Project Labour Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Project Labour </li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
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
                                                    <th>Project Name</th>
                                                    <th>User Type</th>
                                                    <th>User Name</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($project_users as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->project_name }}</td>
                                                        <td>{{ $item->usertype_name }}</td>
                                                        <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}</td>


                                                        <td>
                                                            <label class="switch">
                                                                <input data-id="{{ $item->id }}" type="checkbox"
                                                                    {{ $item->is_active ? 'checked' : '' }}
                                                                    class="active-btn btn btn-sm btn-outline-primary m-1"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                                                <span class="slider round "></span>
                                                            </label>

                                                        </td>


                                                        {{-- <td>@if ($item->is_active)
                                                        <button type="button" class="btn btn-success btn-sm">Active</button>
                                                        @else 
                                                        <button type="button" class="btn btn-danger btn-sm">In Active</button>
                                                        
                                                        @endif</td> --}}
                                                        <td class="d-flex">
                                                        @if (in_array('per_update', $data_permission))
                                                            <a href="{{ route('edit-project-wise-users', base64_encode($item->id)) }}"
                                                                class="edit-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-pencil-alt"></i></a>
                                                        @endif

                                                            <a data-id="{{ $item->id }}"
                                                                class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-eye"></i></a>
                                                        @if (in_array('per_delete', $data_permission))            
                                                            <a data-id="{{ $item->id }}"
                                                                class="delete-btn btn btn-sm btn-outline-danger m-1"
                                                                title="Delete Tender"><i class="fas fa-archive"></i></a>
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
        </div>
        <form method="POST" action="{{ url('/delete-users') }}" id="deleteform">
            @csrf
            <input type="hidden" name="delete_id" id="delete_id" value="">
        </form>
        <form method="POST" action="{{ url('/show-projects') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
        {{-- <form method="GET" action="{{ url('/edit-project-wise-users') }}" id="editform">
            @csrf
            <input type="hidden" name="edit_id" id="edit_id" value="">
        </form> --}}
        <form method="POST" action="{{ url('/update-active-project_users') }}" id="activeform">
            @csrf
            <input type="hidden" name="active_id" id="active_id" value="">
        </form>

        <!-- content-wrapper ends -->
    @endsection
