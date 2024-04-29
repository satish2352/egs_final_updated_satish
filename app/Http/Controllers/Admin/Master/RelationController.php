<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RelationModel;
use App\Http\Services\Admin\Master\RelationServices;
use Validator;
use Illuminate\Validation\Rule;
class RelationController extends Controller
{

   public function __construct()
    {
        $this->service = new RelationServices();
    }
    public function index()
    {
        try {
            $relation_data = $this->service->getAll();
            return view('admin.pages.master.relation.list-relation', compact('relation_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        return view('admin.pages.master.relation.add-relation');
    }

    public function store(Request $request) {
        $rules = [
            'relation_title' => 'required|unique:relation|regex:/^[a-zA-Z\s]+$/u|max:255',
            // 'marathi_title' => 'required|unique:gender|max:255',
         ];
        $messages = [   
            'relation_title'       =>  'Please enter title.',
            'relation_title.regex' => 'Please  enter text only.',
            'relation_title.unique' => 'Title already exist.',
            // 'marathi_title.unique' => 'शीर्षक आधीच अस्तित्वात आहे.',
            'relation_title.max'   => 'Please  enter text length upto 255 character only.',
            // 'marathi_title.required'       =>'कृपया शीर्षक प्रविष्ट करा.',
            // 'marathi_title.unique'  =>  'तुमचा घटना शीर्षक आधीपासून अस्तित्वात आहे .',
            // 'marathi_title.max'   => 'कृपया केवळ २५५ वर्णांपर्यंत मजकूराची लांबी प्रविष्ट करा.',            
        ];

        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('add-relation')
                    ->withInput()
                    ->withErrors($validation);
            }
            else
            {
                $add_relation_data = $this->service->addAll($request);
                // dd($add_relation_data);
                if($add_relation_data)
                {

                    $msg = $add_relation_data['msg'];
                    $status = $add_relation_data['status'];
                    if($status=='success') {
                        return redirect('list-relation')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('add-relation')->withInput()->with(compact('msg','status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-relation')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->edit_id);
        $relation_data = $this->service->getById($edit_data_id);
        return view('admin.pages.master.relation.edit-relation', compact('relation_data'));
   
    }

    public function update(Request $request)
{
    $id = $request->input('id'); // Assuming the 'id' value is present in the request
    $rules = [
        'relation_title' => ['required', 'max:255','regex:/^[a-zA-Z\s]+$/u', Rule::unique('relation', 'relation_title')->ignore($id, 'id')],
    ];

    $messages = [
        'relation_title.required' => 'Please enter the title.',
        'relation_title.regex' => 'Please  enter text only.',
        'relation_title.max' => 'Please enter an  title with a maximum of 255 characters.',
        'relation_title.unique' => 'The title already exists.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_maritalstatus_data = $this->service->updateAll($request);

            if ($update_maritalstatus_data) {
                $msg = $update_maritalstatus_data['msg'];
                $status = $update_maritalstatus_data['status'];

                if ($status == 'success') {
                    return redirect('list-relation')->with(compact('msg', 'status'));
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
            $relation_data = $this->service->getById($request->show_id);
            return view('admin.pages.master.relation.show-relation', compact('relation_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne(Request $request){
        // dd($request);
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('list-relation')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request){
        try {
            $maritalstatus_data = $this->service->deleteById($request->delete_id);
            if ($maritalstatus_data) {
                $msg = $maritalstatus_data['msg'];
                $status = $maritalstatus_data['status'];
                if ($status == 'success') {
                    return redirect('list-relation')->with(compact('msg', 'status'));
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