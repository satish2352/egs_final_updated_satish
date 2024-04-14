<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Documenttype;
use App\Http\Services\Admin\Master\DocumenttypeServices;
use Validator;
use Illuminate\Validation\Rule;
class DocumenttypeController extends Controller
{

   public function __construct()
    {
        $this->service = new DocumenttypeServices();
    }
    public function index()
    {
        try {
            $documenttype_data = $this->service->getAll();
            return view('admin.pages.master.documenttype.list-documenttype', compact('documenttype_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.documenttype.add-documenttype');
    }

    public function store(Request $request) {
        $rules = [
            'document_type_name' => 'required|unique:documenttype|max:255'
            // 'marathi_title' => 'required|unique:documenttype|max:255',
         ];
        $messages = [   
            'document_type_name'       =>  'Please enter title.',
            'document_type_name.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'document_type_name.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-documenttype')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_documenttype_data = $this->service->addAll($request);
                if($add_documenttype_data)
                {

                    $msg = $add_documenttype_data['msg'];
                    $status = $add_documenttype_data['status'];
                    if($status=='success') {
                        return redirect('list-documenttype')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-documenttype')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-documenttype')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $documenttype_data = $this->service->getById($edit_data_id);
        // dd($documenttype_data);
        return view('admin.pages.master.documenttype.edit-documenttype', compact('documenttype_data'));
   
    }
   
    public function update(Request $request)
{
    $id = $request->input('id'); // Assuming the 'id' value is present in the request
    $rules = [
        'document_type_name' => ['required', 'max:255', Rule::unique('documenttype', 'document_type_name')->ignore($id, 'id')],
        // 'marathi_title' => ['required', 'max:255', Rule::unique('documenttype', 'marathi_title')->ignore($id, 'id')],
    ];

    $messages = [
        'document_type_name.required' => 'Please enter an title.',
        'document_type_name.max' => 'Please enter an  title with a maximum of 255 characters.',
        'document_type_name.unique' => 'The title already exists.',
        // 'marathi_title.required' => 'कृपया  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.max' => 'कृपया २५५ अक्षरांपर्यंत  शीर्षक प्रविष्ट करा.',
        // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_documenttype_data = $this->service->updateAll($request);

            if ($update_documenttype_data) {
                $msg = $update_documenttype_data['msg'];
                $status = $update_documenttype_data['status'];

                if ($status == 'success') {
                    return redirect('list-documenttype')->with(compact('msg', 'status'));
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
            $documenttype_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.documenttype.show-documenttype', compact('documenttype_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-documenttype')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $documenttype_data = $this->service->deleteById($request->delete_id);
            if ($documenttype_data) {
                $msg = $documenttype_data['msg'];
                $status = $documenttype_data['status'];
                if ($status == 'success') {
                    return redirect('list-documenttype')->with(compact('msg', 'status'));
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