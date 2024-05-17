<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentReasons;
use App\Http\Services\Admin\Master\RejectionReasonsDocumentServices;
use Validator;
use Illuminate\Validation\Rule;
class RejectionReasonsDocumentController extends Controller
{

   public function __construct()
    {
        $this->service = new RejectionReasonsDocumentServices();
    }
    public function index()
    {
        try {
            $rejectionreasons_data = $this->service->getAll();
            return view('admin.pages.master.documentrejectionreasons.list-rejectionreason-docs', compact('rejectionreasons_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.documentrejectionreasons.add-rejectionreason-docs');
    }

    public function store(Request $request) {
        $rules = [
            'reason_name' => 'required|unique:tbl_doc_reason|regex:/^[a-zA-Z\s]+$/u|max:255',
         ];
        $messages = [   
            'reason_name'       =>  'Please enter title.',
            'reason_name.regex' => 'Please  enter text only.',
            'reason_name.unique' => 'Title already exist.',
            'reason_name.max'   => 'Please  enter text length upto 255 character only.',           
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-rejection-reason-docs')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_rejectionreasons_data = $this->service->addAll($request);
                if($add_rejectionreasons_data)
                {

                    $msg = $add_rejectionreasons_data['msg'];
                    $status = $add_rejectionreasons_data['status'];
                    if($status=='success') {
                        return redirect('list-rejection-reason-docs')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-rejection-reason-docs')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-rejection-reason-docs')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $rejectionreasons_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.documentrejectionreasons.edit-rejectionreason-docs', compact('rejectionreasons_data'));
   
    }
    

    public function update(Request $request)
{
    $id = $request->input('id'); // Assuming the 'id' value is present in the request
    $rules = [
        'reason_name' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('tbl_reason', 'reason_name')->ignore($id, 'id')],
    ];

    $messages = [
        'reason_name.required' => 'Please enter the title.',
        'reason_name.regex' => 'Please  enter text only.',
        'reason_name.max' => 'Please enter an  title with a maximum of 255 characters.',
        'reason_name.unique' => 'The title already exists.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_rejectionreasons_data = $this->service->updateAll($request);

            if ($update_rejectionreasons_data) {
                $msg = $update_rejectionreasons_data['msg'];
                $status = $update_rejectionreasons_data['status'];

                if ($status == 'success') {
                    return redirect('list-rejection-reason-docs')->with(compact('msg', 'status'));
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

    public function show(Request $request)
    {
        try {
            $rejection_reasons_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.documentrejectionreasons.show-rejectionreason-docs', compact('rejection_reasons_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-rejection-reason-docs')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $registrationstatus_data = $this->service->deleteById($request->delete_id);
            if ($registrationstatus_data) {
                $msg = $registrationstatus_data['msg'];
                $status = $registrationstatus_data['status'];
                if ($status == 'success') {
                    return redirect('list-rejection-reason-docs')->with(compact('msg', 'status'));
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

}