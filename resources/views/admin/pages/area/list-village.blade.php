@extends('admin.layout.master')

@section('content')
<style>
/* Pagination styles */
.pagination {
    margin: 20px 0;
}

.pagination ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.pagination ul li {
    display: inline;
    margin-right: 5px;
}

.pagination ul li a,
.pagination ul li span {
    padding: 5px 10px;
    border: 1px solid #ccc;
    text-decoration: none;
    color: #333;
}

.pagination ul li.active a {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

.pagination ul li.disabled span {
    color: #ccc;
}

img, svg {
    vertical-align: middle;
    width: 2%;
}

div.dataTables_wrapper div.dataTables_info {
    display: none;
}
div.dataTables_wrapper div.dataTables_paginate ul.pagination{
    display: none; 
}
.pagination .flex .flex{
    display: none; 
}
</style>
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-district', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Village List <a href="{{ route('add-village') }}" class="btn btn-sm btn-primary ml-3">+ Add</a>
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-village') }}">Area Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Village List </li>
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
                                        
                                        <table id="" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>District Name</th>
                                                    <th>Taluka Name</th>
                                                    <th>Village Name</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $recordNumber = $data_village->firstItem();
                                            @endphp
                                                @foreach ($data_village as $item)
                                                    <tr>
                                                        <td>{{ $recordNumber }}</td>
                                                        <td>{{ $item->district_name }}</td>
                                                        <td>{{ $item->taluka_name }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            <label class="switch">
                                                                <input data-id="{{ $item->location_id }}" type="checkbox"
                                                                    {{ $item->is_active ? 'checked' : '' }}
                                                                    class="active-btn btn btn-sm btn-outline-primary m-1"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="{{ $item->is_active ? 'Active' : 'Inactive' }}">
                                                                <span class="slider round "></span>
                                                            </label>

                                                        </td>

                                                        <td class="d-flex">
                                                        @if (in_array('per_update', $data_permission))
                                                            <a href="{{ route('edit-village', base64_encode($item->location_id)) }}"
                                                                class="edit-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-pencil-alt"></i></a>
                                                        @endif            
                                                            <!-- <a data-id="{{ $item->location_id }}"
                                                                class="show-btn btn btn-sm btn-outline-primary m-1"><i
                                                                    class="fas fa-eye"></i></a> -->
                                                        @if (in_array('per_delete', $data_permission))            
                                                            <a data-id="{{ $item->location_id }}"
                                                                class="delete-btn btn btn-sm btn-outline-danger m-1"
                                                                title="Delete Tender"><i class="fas fa-archive"></i></a>
                                                        @endif    

                                                        </td>
                                                    </tr>
                                                    @php
                                                        $recordNumber++;
                                                    @endphp
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-4">

                                                </div>

                                                <div class="col-md-8">
                                                    <div class="pagination">
                                                        @if ($data_village->lastPage() > 1)
                                                            <ul class="pagination">
                                                                <li class="{{ ($data_village->currentPage() == 1) ? ' disabled' : '' }}">
                                                                    @if ($data_village->currentPage() > 1)
                                                                        <a href="{{ $data_village->url($data_village->currentPage() - 1) }}">Previous</a>
                                                                    @else
                                                                        <span>Previous</span>
                                                                    @endif
                                                                </li>
                                                                @php
                                                                    $currentPage = $data_village->currentPage();
                                                                    $lastPage = $data_village->lastPage();
                                                                    $startPage = max($currentPage - 5, 1);
                                                                    $endPage = min($currentPage + 4, $lastPage);
                                                                @endphp
                                                                @if ($startPage > 1)
                                                                    <li>
                                                                        <a href="{{ $data_village->url(1) }}">1</a>
                                                                    </li>
                                                                    @if ($startPage > 2)
                                                                        <li>
                                                                            <span>...</span>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @for ($i = $startPage; $i <= $endPage; $i++)
                                                                    <li class="{{ ($currentPage == $i) ? ' active' : '' }}">
                                                                        <a href="{{ $data_village->url($i) }}">{{ $i }}</a>
                                                                    </li>
                                                                @endfor
                                                                @if ($endPage < $lastPage)
                                                                    @if ($endPage < $lastPage - 1)
                                                                        <li>
                                                                            <span>...</span>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <a href="{{ $data_village->url($lastPage) }}">{{ $lastPage }}</a>
                                                                    </li>
                                                                @endif
                                                                <li class="{{ ($currentPage == $lastPage) ? ' disabled' : '' }}">
                                                                    @if ($currentPage < $lastPage)
                                                                        <a href="{{ $data_village->url($currentPage + 1) }}">Next</a>
                                                                    @else
                                                                        <span>Next</span>
                                                                    @endif
                                                                </li>
                                                                <!-- <li>
                                                                    <span>Page {{ $currentPage }}</span>
                                                                </li> -->
                                                            </ul>
                                                        @endif
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
        
        <form method="POST" action="{{ url('/delete-users') }}" id="deleteform">
            @csrf
            <input type="hidden" name="delete_id" id="delete_id" value="">
        </form>
        <form method="POST" action="{{ url('/show-users') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
        {{-- <form method="GET" action="{{ url('/edit-village') }}" id="editform">
            @csrf
            <input type="hidden" name="edit_id" id="edit_id" value="">
        </form> --}}
        <form method="POST" action="{{ url('//update-active-village') }}" id="activeform">
            @csrf
            <input type="hidden" name="active_id" id="active_id" value="">
        </form>

        <!-- content-wrapper ends -->
    @endsection
