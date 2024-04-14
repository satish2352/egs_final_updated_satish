@if(count($permissions)>0)
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Functionality Name</th>
                        <th>Add</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $key => $permission)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <input type="hidden" class="form-check-input" name="permission_id_{{ $permission['id'] }}"
                                    id="permission_id_{{ $permission['id'] }}" value="{{ $permission['id'] }}"
                                    data-parsley-multiple="permission_id">
                                {{ $permission['permission_name'] }}
                            </td>
                            <td>
                                <label class="form-check-label">
                                    <?php $add_name = 'per_add_' . $permission['id']; ?>
                                    <input type="checkbox" class="form-check-input"
                                        name="per_add_{{ $permission['id'] }}" id="per_add_{{ $permission['id'] }}"
                                        value="add_{{ $permission['id'] }}" data-parsley-multiple="per_add"
                                        @if ($permission['per_add']) {{ 'checked' }} @endif disabled>

                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                            </td>
                            <td>
                                <label class="form-check-label">
                                    <?php $per_update = 'per_update_' . $permission['id']; ?>
                                    <input type="checkbox" class="form-check-input"
                                        name="per_update_{{ $permission['id'] }}"
                                        id="per_update_{{ $permission['id'] }}" value="update_{{ $permission['id'] }}"
                                        data-parsley-multiple="per_update"
                                        @if ($permission['per_update']) {{ 'checked' }} @endif disabled>

                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                            </td>
                            <td>
                                <label class="form-check-label">
                                    <?php $per_delete = 'per_delete_' . $permission['id']; ?>
                                    <input type="checkbox" class="form-check-input"
                                        name="per_delete_{{ $permission['id'] }}"
                                        id="per_delete_{{ $permission['id'] }}" value="delete_{{ $permission['id'] }}"
                                        data-parsley-multiple="per_delete"
                                        @if ($permission['per_delete']) {{ 'checked' }} @endif disabled>

                                    <i class="input-helper"></i><i class="input-helper"></i></label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <h5 style="color:red;align-item:center"> {{ 'No Data Found For Selected Role' }}</h5>
@endif
