<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Roles,
    Permissions
};
use Illuminate\Validation\Rule;
use App\Http\Services\Admin\Menu\RoleServices;
use Validator;
class RoleController extends Controller
{

    public function __construct()
    {
        $this->service = new RoleServices();
    }
    public function index()
    {
        try {
            $roles = $this->service->getAllRole();
            return view('admin.pages.menu.roles.list-role', compact('roles'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        try {
            $permissions = Permissions::where('is_active', true)
                ->select('id', 'route_name', 'permission_name', 'url')
                ->get()
                ->toArray();
            return view('admin.pages.menu.roles.add-role', compact('permissions'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function store(Request $request)
    {

        try {

            $rules = [
                'role_name' => 'required|unique:roles|regex:/^[a-zA-Z\s]+$/u|max:255',
            ];
            $messages = [
                'role_name.required' => 'Please  enter title.',
                // 'role_name.unique' => 'Your role type is already exist.',
                'role_name.regex' => 'Please  enter text only.',
                'role_name.max' => 'Please  enter text length upto 255 character only.',
                'role_name.unique' => 'Title already exist.',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect('add-role')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_role = $this->service->addRole($request);
                if ($add_role) {
                    $msg = $add_role['msg'];
                    $status = $add_role['status'];
                    if ($status == 'success') {
                        return redirect('list-role')->with(compact('msg', 'status'));
                    } else {
                        return redirect('add-role')->withInput()->with(compact('msg', 'status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-role')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function show(Request $request)
    {
        try {
            $roles = $this->service->getById($request->show_id);
            return view('admin.pages.menu.roles.show-role', compact('roles'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function edit(Request $request)
    {
        try {
            $edit_data_id = base64_decode($request->edit_id);
            $user_data = $this->service->edit($edit_data_id);
            return view('admin.pages.menu.roles.edit-role', compact('user_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request)
    {
        try {
            
            $id = $request->edit_id; // Assuming the 'id' value is present in the request
            $rules = [
                'role_name' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('roles', 'role_name')->ignore($id, 'id')],
            ];
            $messages = [
                'role_name.required' => 'Please  enter  title.',
                'role_name.regex' => 'Please  enter text only.',
                'role_name.max' => 'Please  enter text length upto 255 character only.',
                'role_name.unique' => 'Role Name Already Exist.',
            ];

            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_role = $this->service->updateRole($request);
                if ($update_role) {
                    $msg = $update_role['msg'];
                    $status = $update_role['status'];
                    if ($status == 'success') {
                        return redirect('list-role')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function updateOneRole(Request $request)
    {
        try {
            $active_id = $request->active_id;
            $result = $this->service->updateOneRole($active_id);
            return redirect('list-role')->with('flash_message', 'Updated!');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function destroy(Request $request){
        try {
            $delete_role_type = $this->service->deleteById($request->delete_id);
            if ($delete_role_type) {
                $msg = $delete_role_type['msg'];
                $status = $delete_role_type['status'];
                if ($status == 'success') {
                    return redirect('list-role')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function listRoleWisePermission(Request $request)
    {
        try {
            $permissions = $this->service->listRoleWisePermission($request->role_id);
            return view('admin.pages.users.roles-permission', compact('permissions'));
        } catch (\Exception $e) {
            return $e;
        }

    }

}