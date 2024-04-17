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
    width: 5%;
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
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-gender', session('permissions'));
    ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Document Type List
                    @if (in_array('per_add', $data_permission))
                        <a href="{{ route('add-documenttype') }}" class="btn btn-sm btn-primary ml-3">+
                            Add</a>
                    @endif

                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-documenttype') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Document Type</li>
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
                                        <table id="order" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Title</th>
                                                    <th>Document Color</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documenttype_data as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ strip_tags($item->document_type_name) }}</td>
                                                        <td><div class="color-box" style="background-color: {{ $item->doc_color }};width: 50px; height: 50px;"></div></td>
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
                                                        <td>
                                                            <div class="d-flex">
                                                                @if (in_array('per_update', $data_permission))
                                                                    <a
                                                                        href="{{ route('edit-documenttype', base64_encode($item->id)) }}"
                                                                        class="btn btn-sm btn-outline-primary m-1"
                                                                        title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                                                @endif

                                                                <a data-id="{{ $item->id }}"
                                                                    class="show-btn btn btn-sm btn-outline-primary m-1"
                                                                    title="Show"><i class="fas fa-eye"></i></a>
                                                                @if (in_array('per_delete', $data_permission))
                                                                    <a data-id="{{ $item->id }}"
                                                                        class="delete-btn btn btn-sm btn-outline-danger m-1"
                                                                        title="Delete"><i class="fas fa-archive"></i></a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                

                                            </tbody>
                                            
                                        </table>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-9">

                                                </div>
                                                <div class="col-md-3">
                                                    <div class="pagination">
                                                        {{ $documenttype_data->links() }}
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
            <div class="row">
            
</div>
        </div>

        <script>
    $(document).ready(function() {
        $('#order').DataTable({
            // DataTables options
            // Example:
            // "paging": false, // Disable DataTables pagination
        });
    });
</script>
        <form method="POST" action="{{ url('/delete-documenttype') }}" id="deleteform">
            @csrf
            <input type="hidden" name="delete_id" id="delete_id" value="">
        </form>
        <form method="POST" action="{{ url('/show-documenttype') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
        <form method="POST" action="{{ url('/update-one-documenttype') }}" id="activeform">
            @csrf
            <input type="hidden" name="active_id" id="active_id" value="">
        </form>
    @endsection
