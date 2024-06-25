@extends('admin.layout.master')

@section('content')
    <?php $data_permission = getPermissionForCRUDPresentOrNot('list-users', session('permissions')); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-7">
            <div class="page-header">
                <h3 class="page-title">
                    Users Master List <a href="{{ route('add-users') }}" class="btn btn-sm btn-primary ml-3">+ Add</a>
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('list-users') }}">Master Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Users </li>
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
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $recordNumber = $register_user->firstItem();
                                            @endphp
                                                @foreach ($register_user as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->f_name }} {{ $item->m_name }} {{ $item->l_name }}
                                                            ({{ $item->email }})
                                                        </td>
                                                        <td>{{ $item->role_name }}</td>


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


                                                        <td class="d-flex">
                                                        @if (in_array('per_update', $data_permission))
                                                            <a href="{{ route('edit-users', base64_encode($item->id)) }}"
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
                                                    @php
                                                        $recordNumber++;
                                                    @endphp
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <div class="col-md-8">
                                                    <div class="pagination">
                                                        @if ($register_user->lastPage() > 1)
                                                            <ul class="pagination">
                                                                <li class="{{ ($register_user->currentPage() == 1) ? ' disabled' : '' }}">
                                                                    @if ($register_user->currentPage() > 1)
                                                                        <a href="{{ $register_user->url($register_user->currentPage() - 1) }}">Previous</a>
                                                                    @else
                                                                        <span>Previous</span>
                                                                    @endif
                                                                </li>
                                                                @php
                                                                    $currentPage = $register_user->currentPage();
                                                                    $lastPage = $register_user->lastPage();
                                                                    $startPage = max($currentPage - 5, 1);
                                                                    $endPage = min($currentPage + 4, $lastPage);
                                                                @endphp
                                                                @if ($startPage > 1)
                                                                    <li>
                                                                        <a href="{{ $register_user->url(1) }}">1</a>
                                                                    </li>
                                                                    @if ($startPage > 2)
                                                                        <li>
                                                                            <span>...</span>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @for ($i = $startPage; $i <= $endPage; $i++)
                                                                    <li class="{{ ($currentPage == $i) ? ' active' : '' }}">
                                                                        <a href="{{ $register_user->url($i) }}">{{ $i }}</a>
                                                                    </li>
                                                                @endfor
                                                                @if ($endPage < $lastPage)
                                                                    @if ($endPage < $lastPage - 1)
                                                                        <li>
                                                                            <span>...</span>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <a href="{{ $register_user->url($lastPage) }}">{{ $lastPage }}</a>
                                                                    </li>
                                                                @endif
                                                                <li class="{{ ($currentPage == $lastPage) ? ' disabled' : '' }}">
                                                                    @if ($currentPage < $lastPage)
                                                                        <a href="{{ $register_user->url($currentPage + 1) }}">Next</a>
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
        <form method="POST" action="{{ url('/delete-users') }}" id="deleteform">
            @csrf
            <input type="hidden" name="delete_id" id="delete_id" value="">
        </form>
        <form method="POST" action="{{ url('/show-users') }}" id="showform">
            @csrf
            <input type="hidden" name="show_id" id="show_id" value="">
        </form>
        {{-- <form method="GET" action="{{ url('/edit-users') }}" id="editform">
            @csrf
            <input type="hidden" name="edit_id" id="edit_id" value="">
        </form> --}}
        <form method="POST" action="{{ url('/update-active-user') }}" id="activeform">
            @csrf
            <input type="hidden" name="active_id" id="active_id" value="">
        </form>

        <!-- content-wrapper ends -->
    @endsection
